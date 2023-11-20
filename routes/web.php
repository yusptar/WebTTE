<?php

use Illuminate\Support\Facades\Auth;
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
    Route::get('upload-rm', [App\Http\Controllers\TTEController::class, 'index'])->name('upload-rm');
    Route::get('pembubuhan-tte', [App\Http\Controllers\TTEController::class, 'index_pembubuhan_tte'])->name('pembubuhan-tte');
    Route::get('list-dokumen-rm', [App\Http\Controllers\TTEController::class, 'index_list_dokumen_rm'])->name('list-dokumen-rm');
    Route::post('store-rm', [App\Http\Controllers\TTEController::class, 'store'])->name('store-rm');
    Route::post('update-tte', [App\Http\Controllers\TTEController::class, 'update'])->name('update-tte');
    
    // Route::post('/kirim-tte', [App\Http\Controllers\TTEController::class, 'kirimTTE'])->name('kirimTTE');

    // Lainnya
    Route::get('users', [App\Http\Controllers\UserController::class, 'index'])->name('users');
    Route::get('user-list', [App\Http\Controllers\UserController::class, 'user_list'])->name('user-list');
    Route::post('user-detail', [App\Http\Controllers\UserController::class, 'user_detail'])->name('user.detail');
    Route::post('users-store', [App\Http\Controllers\UserController::class, 'store'])->name('user-store');
    Route::post('users-update', [App\Http\Controllers\UserController::class, 'update'])->name('user-update');
    Route::post('users-delete', [App\Http\Controllers\UserController::class, 'destroy'])->name('user-delete');

    Route::get('/status-user-tte', [App\Http\Controllers\APITTEController::class, 'getStatusUser'])->name('getStatusUserTTE');
    Route::post('/sign-invisible', [App\Http\Controllers\APITTEController::class, 'signInvisible'])->name('signInvisibleTTE');

    Route::post('/download', [\App\Http\Controllers\TTEController::class, 'download'])->name('downloadRM');
});