<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirect permanen (301) dari domain lama ke domain kanonis (APP_URL).
 *
 * Latar: setelah pindah Railway (domain lama: reverb-production-db79,
 * app-portal-sekolah-production), klien lama — bookmark, PWA terinstal,
 * APK lama, cache browser — masih membuka domain lama dan server tetap
 * melayaninya, sehingga user "nyangkut" di link lama.
 *
 * Middleware ini memaksa setiap request yang host-nya tercantum di
 * LEGACY_HOSTS (env, koma-dipisah) pindah ke host APP_URL dengan path
 * + query yang sama. Hanya berjalan bila APP_URL terisi & host berbeda,
 * sehingga healthcheck /up dan domain kanonis tidak tersentuh.
 */
class RedirectLegacyHost
{
    /** @var string[] */
    private static array $defaultLegacy = [
        'reverb-production-db79.up.railway.app',
        'app-portal-sekolah-production.up.railway.app',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $host = strtolower($request->getHost());

        $configured = array_filter(array_map(
            fn ($h) => strtolower(trim($h)),
            explode(',', (string) env('LEGACY_HOSTS', implode(',', self::$defaultLegacy)))
        ));

        if ($configured !== [] && in_array($host, $configured, true)) {
            $canonical = strtolower((string) parse_url((string) config('app.url'), PHP_URL_HOST));
            if ($canonical !== '' && $canonical !== $host) {
                $target = 'https://'.$canonical.$request->getRequestUri();

                // API JSON: kembalikan info + URL baru (redirect 301 merusak POST/JSON client).
                if ($request->is('api/*') || $request->expectsJson()) {
                    return response()->json([
                        'message' => 'Endpoint pindah permanen.',
                        'new_url' => $target,
                    ], 410);
                }

                return redirect()->away($target, 301);
            }
        }

        return $next($request);
    }
}
