<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [App\Http\Controllers\DashboardController::class, 'index']);

Auth::routes([ // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

// add login middleware
Route::group(['middleware' => ['auth']], function () {
    // Pra Integrasi TTE
    Route::get('/upload-rm', [App\Http\Controllers\TTEController::class, 'index'])->name('upload-rm');
    Route::get('/upload-tte', [App\Http\Controllers\TTEController::class, 'index'])->name('tte');
    Route::post('/store', [App\Http\Controllers\TTEController::class, 'store'])->name('store');
    Route::post('/kirim-tte', [App\Http\Controllers\TTEController::class, 'kirimTTE'])->name('kirimTTE');

    // Lainnya
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users');
    Route::post('/users-store', [App\Http\Controllers\UserController::class, 'store'])->name('users-store');
    Route::post('/users-update', [App\Http\Controllers\UserController::class, 'store'])->name('users-update');
    Route::get('/users-delete', [App\Http\Controllers\UserController::class, 'store'])->name('users-delete');
});