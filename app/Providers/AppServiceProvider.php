<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('report-submissions', function (Request $request): array {
            $requestKey = hash('sha256', (string) $request->ip());

            return [
                Limit::perMinute(2)->by("minute:{$requestKey}"),
                Limit::perHour(5)->by("hour:{$requestKey}"),
            ];
        });

        RateLimiter::for('report-tracking', function (Request $request): array {
            $requestKey = hash('sha256', (string) $request->ip());

            return [
                Limit::perMinute(10)->by("minute:{$requestKey}"),
                Limit::perHour(30)->by("hour:{$requestKey}"),
            ];
        });

        RateLimiter::for('report-messages', function (Request $request): array {
            $report = $request->route('report');
            $reportKey = is_object($report) && method_exists($report, 'getKey') ? $report->getKey() : 'unknown';
            $requestKey = hash('sha256', implode('|', [
                (string) $request->ip(),
                $request->session()->getId(),
                (string) $reportKey,
            ]));

            return [
                Limit::perMinute(6)->by("minute:{$requestKey}"),
                Limit::perHour(30)->by("hour:{$requestKey}"),
            ];
        });
    }
}
