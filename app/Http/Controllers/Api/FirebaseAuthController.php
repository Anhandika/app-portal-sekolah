<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FirebaseAuthService;
use Illuminate\Http\Request;

/**
 * Login via Firebase Authentication (Email/Password).
 * Klien login dulu ke Firebase Auth (web SDK / Android SDK),
 * lalu tukar id_token menjadi Sanctum token backend.
 */
class FirebaseAuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'id_token' => 'required|string',
            'device_name' => 'nullable|string|max:255',
        ]);

        $user = FirebaseAuthService::userFromIdToken($data['id_token']);
        if (! $user) {
            return response()->json([
                'message' => 'Token Firebase tidak valid, atau akun belum terdaftar / sedang dinonaktifkan.',
            ], 401);
        }

        $token = $user->createToken($data['device_name'] ?? 'firebase-app', [$user->role])->plainTextToken;

        return response()->json([
            'token' => $token,
            'via' => 'firebase',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'kelas_id' => $user->kelas_id,
            ],
        ]);
    }
}