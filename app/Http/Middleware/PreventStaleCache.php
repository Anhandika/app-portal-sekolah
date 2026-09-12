<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventStaleCache
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only apply to web (non-API) routes
        if (!$request->is('api/*')) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
            $response->headers->set('X-Accel-Buffering', 'no');
        }

        return $response;
    }
}
