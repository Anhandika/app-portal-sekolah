<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Wrapper ringan Cloud Firestore via Admin SDK (kreait/firebase-php).
 * Best-effort & gagal aman: return null/false bila Firebase belum
 * dikonfigurasi, TIDAK melempar exception ke alur utama.
 */
class FirestoreService
{
    public static function db()
    {
        $sa = FcmService::serviceAccount();
        if (! config('firebase.enabled') || ! $sa) {
            return null;
        }
        try {
            return (new \Kreait\Firebase\Factory)->withServiceAccount($sa)->createFirestore()->database();
        } catch (\Throwable $e) {
            Log::warning('Firestore connect gagal: '.$e->getMessage());

            return null;
        }
    }

    /** Tulis (overwrite) dokumen. */
    public static function set(string $collection, string $docId, array $data): bool
    {
        try {
            $db = static::db();
            if (! $db) {
                return false;
            }
            $db->collection($collection)->document($docId)->set($data);

            return true;
        } catch (\Throwable $e) {
            Log::warning('Firestore set gagal: '.$e->getMessage());

            return false;
        }
    }

    /** Baca dokumen, return array atau null. */
    public static function get(string $collection, string $docId): ?array
    {
        try {
            $db = static::db();
            if (! $db) {
                return null;
            }
            $snap = $db->collection($collection)->document($docId)->snapshot();
            if (! $snap->exists()) {
                return null;
            }

            return $snap->data();
        } catch (\Throwable $e) {
            Log::warning('Firestore get gagal: '.$e->getMessage());

            return null;
        }
    }

    /** Tambah dokumen dengan ID otomatis, return ID atau null. */
    public static function add(string $collection, array $data): ?string
    {
        try {
            $db = static::db();
            if (! $db) {
                return null;
            }

            return $db->collection($collection)->add($data)->id();
        } catch (\Throwable $e) {
            Log::warning('Firestore add gagal: '.$e->getMessage());

            return null;
        }
    }
}