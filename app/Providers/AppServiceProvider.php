<?php

namespace App\Providers;

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
        // Always force HTTPS in production.
        // This is necessary because reverse proxies (like Cloudflare or Nginx load balancers)
        // often cause HTTP->HTTPS redirects that downgrade POST requests to GET, resulting in 405 errors.
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
