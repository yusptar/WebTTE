<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APITTEController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
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
});  