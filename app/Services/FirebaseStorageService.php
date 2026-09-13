<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FirebaseStorageService
{
    /**
     * Upload ke Firebase Storage jika enabled & kredensial ada, fallback ke disk public.
     * Return path/URL yang bisa disimpan di DB.
     */
    public static function put(string $dir, UploadedFile $file): string
    {
        if (!config('firebase.enabled') || !config('firebase.storage_bucket')) {
            return $file->store($dir, 'public');
        }

        $cred = config('firebase.credentials');
        $bucket = config('firebase.storage_bucket');

        // cek kredensial file/json ada
        $hasCred = $cred && (is_file($cred) || str_starts_with($cred, '{'));
        if (!$hasCred) {
            return $file->store($dir, 'public');
        }

        try {
            $factory = (new \Kreait\Firebase\Factory)
                ->withServiceAccount($cred)
                ->withDefaultStorageBucket($bucket);

            $storage = $factory->createStorage();
            $bucketObj = $storage->getBucket();

            $name = $dir.'/'.uniqid().'_'.preg_replace('/[^A-Za-z0-9._-]/','_', $file->getClientOriginalName());
            $content = file_get_contents($file->getRealPath());

            $object = $bucketObj->upload($content, ['name'=>$name]);
            // public url (akses publik diatur lewat Storage Rules: allow read)
            return 'https://firebasestorage.googleapis.com/v0/b/'.$bucket.'/o/'.rawurlencode($name).'?alt=media';
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Firebase upload fallback: '.$e->getMessage());
            return $file->store($dir, 'public');
        }
    }

    public static function enabled(): bool
    {
        if (!config('firebase.enabled') || !config('firebase.storage_bucket')) {
            return false;
        }
        $cred = config('firebase.credentials');
        return (bool) $cred && (is_file($cred) || str_starts_with($cred, '{'));
    }

    public static function url(?string $path): ?string
    {
        if (!$path) return null;
        if (str_starts_with($path, 'http')) return $path;
        return asset('storage/'.$path);
    }

    /**
     * Hapus file baik yang di cloud (URL Firebase) maupun lokal (path disk public).
     * Aman dipanggil untuk keduanya — tidak throw.
     */
    public static function delete(?string $path): void
    {
        if (!$path) return;
        try {
            if (str_starts_with($path, 'http')) {
                if (static::enabled()) {
                    // Ambil nama object dari URL .../o/{encoded-name}?alt=media
                    if (preg_match('#/o/(.+?)(\?|$)#', $path, $m)) {
                        $name = rawurldecode($m[1]);
                        $factory = (new \Kreait\Firebase\Factory)
                            ->withServiceAccount(config('firebase.credentials'))
                            ->withDefaultStorageBucket(config('firebase.storage_bucket'));
                        $factory->createStorage()->getBucket()->object($name)->delete();
                    }
                }
                return;
            }
            Storage::disk('public')->delete($path);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Firebase delete fallback: '.$e->getMessage());
            if (!str_starts_with($path, 'http')) {
                try { Storage::disk('public')->delete($path); } catch (\Throwable $e2) {}
            }
        }
    }
}
