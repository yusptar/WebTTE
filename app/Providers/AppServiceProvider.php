<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
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
     *
     * Isi dari AuthServiceProvider dan RouteServiceProvider (Laravel 8)
     * dipindahkan ke sini sesuai struktur Laravel 13.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        $this->configureGates();
        $this->configureRateLimiting();
    }

    /**
     * Hak akses per role (dulu di AuthServiceProvider).
     */
    protected function configureGates(): void
    {
        Gate::define('admin', fn ($user) => $user->role == 'admin');

        Gate::define('admin-it', fn ($user) => $user->username == '20220294535');

        Gate::define('perawat', fn ($user) => $user->role == 'perawat');

        Gate::define('petugas', fn ($user) => $user->role == 'petugas');

        Gate::define('ppa', fn ($user) => $user->role == 'ppa');
    }

    /**
     * Rate limiter untuk route API (dulu di RouteServiceProvider).
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
