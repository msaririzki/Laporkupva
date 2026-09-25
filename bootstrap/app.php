<?php

use App\Http\Middleware\AddSecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->trustHosts(
            at: static function (): array {
                $configuredHost = parse_url((string) config('app.url'), PHP_URL_HOST);

                return array_values(array_filter([
                    is_string($configuredHost) && $configuredHost !== '' ? '^'.preg_quote($configuredHost, '/').'$' : null,
                    '^localhost$',
                    '^127\.0\.0\.1$',
                ]));
            },
            subdomains: false,
        );
        $middleware->append(AddSecurityHeaders::class);

        $middleware->redirectGuestsTo(
            fn (Request $request): string => route('filament.admin.auth.login'),
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
