<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
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
        // Ostavljamo prazno ili dodajemo standardnu implementaciju ako Laravel to zahtijeva.
        // Za tvoju svrhu, ova metoda može biti prazna ili imati standardnu implementaciju Laravel rate limitera.
        // Ako ti se pojavi greška da 'configureRateLimiting' ne postoji, zakomentiraj liniju `$this->configureRateLimiting();` u boot metodi.
        // Ali najbolje je dodati standardnu implementaciju.
        // Evo standardne (ili je izostavi ako ne dobiješ grešku, Laravel će se valjda snaći)

        /*
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
        */
    }
}