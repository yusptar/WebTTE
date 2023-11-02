<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::group(['prefix' => 'profil'], function () {
//     Route::apiResource('pages', PagesController::class);
//     Route::apiResource('jajarandireksi', JajaranDireksiController::class);
// });

// Route::get('berita/popular', [BeritaController::class, 'indexPopular']);
// Route::apiResource('berita', BeritaController::class);
// Route::get('artikel/popular', [ArtikelController::class, 'indexPopular']);
// Route::apiResource('artikel', ArtikelController::class);
// Route::apiResource('parkir', ParkirController::class);
// Route::apiResource('poliklinik', PoliklinikController::class);

// Route::post('encrypt-file', [ContohEncryptController::class, 'store']);
// Route::get('decrypt-file', [ContohEncryptController::class, 'decrypt']);
// Route::apiResource('review', ReviewController::class);
// Route::apiResource('pengaduan', PengaduanController::class);
// Route::apiResource('jenis-laporan-pengaduan', JenisLaporanPengaduanController::class);
// Route::apiResource('spesialis', SpesialisController::class);
// Route::apiResource('permintaan-ppid', PermintaanPPIDController::class);
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// add login middleware
// Route::group(['middleware' => ['auth']], function () {
    Route::get('/status-user-tte', [App\Http\Controllers\APITTEController::class, 'getStatusUser'])->name('getStatusUserTTE');
    Route::get('/sign-invisible', [App\Http\Controllers\APITTEController::class, 'signInvisible'])->name('signInvisibleTTE');
// });