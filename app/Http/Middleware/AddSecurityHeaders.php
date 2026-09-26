<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddSecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $contentSecurityPolicy = [
            "default-src 'self'",
            "base-uri 'self'",
            "connect-src 'self' https://api.mapbox.com https://events.mapbox.com https://nominatim.openstreetmap.org",
            "font-src 'self' data:",
            "form-action 'self'",
            "frame-ancestors 'self'",
            "frame-src 'self' blob:",
            "img-src 'self' data: blob: https://api.mapbox.com https://tile.openstreetmap.org https://*.tile.openstreetmap.org https://*.tiles.mapbox.com",
            "media-src 'self' blob:",
            "object-src 'none'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://unpkg.com",
            "style-src 'self' 'unsafe-inline' https://unpkg.com",
            "worker-src 'self' blob:",
        ];

        if ($request->isSecure()) {
            $contentSecurityPolicy[] = 'upgrade-insecure-requests';
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        if (! $response->headers->has('Content-Security-Policy')) {
            $response->headers->set('Content-Security-Policy', implode('; ', $contentSecurityPolicy));
        }
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');
        $response->headers->set('Permissions-Policy', 'accelerometer=(), camera=(), geolocation=(self), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=()');
        if (! $response->headers->has('Referrer-Policy')) {
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        }
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        return $response;
    }
}
