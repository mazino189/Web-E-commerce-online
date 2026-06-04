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
        // register admin middleware globally //

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // environment variable for production //
        if (app()->environment('production')) {
            // force https in production //
            URL::forceScheme('https');
        }
    }
}
