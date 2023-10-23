<?php

use App\Http\Controllers\API\ArtikelController;
use App\Http\Controllers\API\ContohEncryptController;
use App\Http\Controllers\API\BeritaController;
use App\Http\Controllers\API\ParkirController;
use App\Http\Controllers\API\JajaranDireksiController;
use App\Http\Controllers\API\PagesController;
use App\Http\Controllers\API\PoliklinikController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\PengaduanController;
use App\Http\Controllers\API\SpesialisController;
use App\Http\Controllers\API\JenisLaporanPengaduanController;
use App\Http\Controllers\API\PermintaanPPIDController;
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

Route::group(['prefix' => 'profil'], function () {
    Route::apiResource('pages', PagesController::class);
    Route::apiResource('jajarandireksi', JajaranDireksiController::class);
});

Route::get('berita/popular', [BeritaController::class, 'indexPopular']);
Route::apiResource('berita', BeritaController::class);
Route::get('artikel/popular', [ArtikelController::class, 'indexPopular']);
Route::apiResource('artikel', ArtikelController::class);
Route::apiResource('parkir', ParkirController::class);
Route::apiResource('poliklinik', PoliklinikController::class);

Route::post('encrypt-file', [ContohEncryptController::class, 'store']);
Route::get('decrypt-file', [ContohEncryptController::class, 'decrypt']);
Route::apiResource('review', ReviewController::class);
Route::apiResource('pengaduan', PengaduanController::class);
Route::apiResource('jenis-laporan-pengaduan', JenisLaporanPengaduanController::class);
Route::apiResource('spesialis', SpesialisController::class);
Route::apiResource('permintaan-ppid', PermintaanPPIDController::class);
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });