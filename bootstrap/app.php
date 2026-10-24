<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Pengganti app/Http/Kernel.php (Laravel 8).
        //
        // Middleware global bawaan Laravel 13 sudah mencakup semua yang dulu
        // terdaftar: TrustProxies, HandleCors (membaca config/cors.php),
        // PreventRequestsDuringMaintenance, ValidatePostSize, TrimStrings
        // (password tidak di-trim), dan ConvertEmptyStringsToNull.
        // TrustHosts tetap tidak diaktifkan, sama seperti sebelumnya.

        // Dulu Authenticate::redirectTo(): tamu diarahkan ke halaman login.
        $middleware->redirectGuestsTo(fn () => route('login'));

        // Dulu RedirectIfAuthenticated + RouteServiceProvider::HOME = '/'.
        $middleware->redirectUsersTo('/');

        // Grup "api" dulu memakai 'throttle:api' (60 request/menit).
        // Rate limiter "api" didefinisikan di AppServiceProvider.
        $middleware->throttleApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Dulu app/Exceptions/Handler.php hanya berisi pengaturan bawaan
        // ($dontFlash untuk password), yang sekarang sudah menjadi default.
    })->create();
