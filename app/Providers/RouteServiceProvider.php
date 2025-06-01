<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit; // DODAJ OVU LINIJU
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request; // DODAJ OVU LINIJU
use Illuminate\Support\Facades\RateLimiter; // DODAJ OVU LINIJU
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // Učitavanje API ruta iz routes/api.php
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Učitavanje Web ruta iz routes/web.php
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting()
    {
        // OVAJ DIO JE ISPRAVLJEN
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}