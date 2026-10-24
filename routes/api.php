<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APITTEController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Route API aplikasi TTE. File ini dimuat dari bootstrap/app.php
| (withRouting) dengan prefix /api dan middleware group "api".
|
*/

Route::post('/auth', [App\Http\Controllers\API\AuthController::class, 'login']);

// add login middleware

// Route::group(['middleware' => ['auth']], function () {
    // Route::get('/status-user-tte', [APITTEController::class, 'getStatusUser'])->name('getStatusUserTTE');
    // Route::post('/sign-invisible', [APITTEController::class, 'signInvisible'])->name('signInvisibleTTE');
// });

Route::group(['middleware' => ['auth:sanctum']], function () {
    // Route::get('/view-rm', [App\Http\Controllers\API\UploadRMController::class, 'index']);
    Route::post('/api-store-pdf', [App\Http\Controllers\API\UploadRMController::class, 'store']);
    Route::post('/api-store-qr', [App\Http\Controllers\API\UploadRMController::class, 'storeQR']);
});  