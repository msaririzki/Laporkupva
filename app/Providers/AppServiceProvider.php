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
        RateLimiter::for('report-submissions', function (Request $request): Limit {
            return Limit::perHour(5)->by(hash('sha256', (string) $request->ip()));
        });

        RateLimiter::for('report-tracking', function (Request $request): Limit {
            return Limit::perMinute(10)->by(hash('sha256', (string) $request->ip()));
        });
    }
}
