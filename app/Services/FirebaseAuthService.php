<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Verifikasi Firebase ID Token (hasil login Email/Password via Firebase
 * Authentication) dan petakan ke akun lokal berdasarkan email.
 *
 * Alur: klien (web/Android) login ke Firebase Auth -> kirim id_token ke
 * POST /api/auth/firebase -> server verifikasi via Admin SDK (kreait) ->
 * tukar menjadi Sanctum token agar bisa memakai seluruh API yang ada.
 */
class FirebaseAuthService
{
    /** Verifikasi ID token, return ['uid','email'] atau null. */
    public static function verifyIdToken(string $idToken): ?array
    {
        $sa = FcmService::serviceAccount();
        if (! config('firebase.enabled') || ! $sa) {
            return null;
        }
        try {
            $auth = (new \Kreait\Firebase\Factory)->withServiceAccount($sa)->createAuth();
            $verified = $auth->verifyIdToken($idToken);

            return [
                'uid' => $verified->claims()->get('sub'),
                'email' => $verified->claims()->get('email'),
            ];
        } catch (\Throwable $e) {
            Log::warning('Firebase verifyIdToken gagal: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Cari akun lokal dari ID token. Akun HARUS sudah ada & aktif
     * (dibuat admin / seeder) — Firebase di sini sebagai gerbang login,
     * bukan sumber pembuatan akun.
     */
    public static function userFromIdToken(string $idToken): ?User
    {
        $claims = static::verifyIdToken($idToken);
        if (! $claims || empty($claims['email'])) {
            return null;
        }
        $user = User::where('email', $claims['email'])->first();
        if (! $user || ! $user->aktif) {
            return null;
        }

        return $user;
    }
}