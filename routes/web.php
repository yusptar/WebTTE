<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Hashids\Hashids;

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
    'reigster' => false,
]);

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

// add login middleware
Route::group(['middleware' => ['auth']], function () {
    // Pra Integrasi TTE

    // UPLOAD PDF
    Route::get('upload-rm', [App\Http\Controllers\TTEController::class, 'index_rm'])->name('upload-rm');
    Route::get('upload-surat', [App\Http\Controllers\TTEController::class, 'index_surat'])->name('upload-surat');
    // TTE PDF (RM)
    Route::get('pembubuhan-tte-rm', [App\Http\Controllers\TTEController::class, 'view_pembubuhan_rm'])->name('view-pemb-rm');
    Route::get('pembubuhan-tte', [App\Http\Controllers\TTEController::class, 'index_pembubuhan_rm'])->name('pembubuhan-tte');
    // TTE SURAT
    Route::get('pembubuhan-tte-sur', [App\Http\Controllers\TTEController::class, 'view_pembubuhan_surat'])->name('view-pemb-sur');
    Route::get('pembubuhan-tte-surat', [App\Http\Controllers\TTEController::class, 'pembubuhan_surat_list'])->name('pembubuhan-tte-surat');
    // LIST DOKUMEN RALAN
    Route::get('view-dokumen-rj', [App\Http\Controllers\TTEController::class, 'view_dokumen_rj'])->name('view-dok-rj');
    Route::get('list-dokumen-rj', [App\Http\Controllers\TTEController::class, 'index_list_dokumen_rj'])->name('list-dokumen-rj');
    // LIST DOKUMEN RANAP
    Route::get('view-dokumen-ri', [App\Http\Controllers\TTEController::class, 'view_dokumen_ri'])->name('view-dok-ri');
    Route::get('list-dokumen-ri', [App\Http\Controllers\TTEController::class, 'index_list_dokumen_ri'])->name('list-dokumen-ri');
    // LIST DOKUMEN SURAT
    Route::get('view-dokumen-surat', [App\Http\Controllers\TTEController::class, 'view_dokumen_surat'])->name('view-dok-surat');
    Route::get('list-dokumen-surat', [App\Http\Controllers\TTEController::class, 'index_list_dokumen_sur'])->name('list-dokumen-surat');
    // SEND TTE
    Route::post('store-surat', [App\Http\Controllers\TTEController::class, 'store_surat'])->name('store-surat');
    Route::post('store-rm', [App\Http\Controllers\TTEController::class, 'store_rm'])->name('store-rm');
    Route::post('update-tte', [App\Http\Controllers\TTEController::class, 'update'])->name('update-tte');
    
    // LIST DOKUMEN BY PASIEN
    Route::get('list-dokumen-rm-rj', [App\Http\Controllers\TTEController::class, 'index_list_dokumen_rm_rj'])->name('list-dokumen-rm-rj');
    // LIST DOKUMEN RANAP
    Route::get('list-dokumen-rm-ri', [App\Http\Controllers\TTEController::class, 'index_list_dokumen_rm_ri'])->name('list-dokumen-rm-ri');
    Route::post('rm-detail', [App\Http\Controllers\TTEController::class, 'rm_detail'])->name('rm.detail');

    // NASKAH KET TTE
    Route::get('/naskah-tte/{id}', [\App\Http\Controllers\TTEController::class, 'index_ket_tte'])->name('naskah-tte');


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
    Route::post('/downloadberkas', [\App\Http\Controllers\TTEController::class, 'downloadberkas'])->name('downloadberkas');
    Route::post('/sign-koordinat', [App\Http\Controllers\APITTEController::class, 'signCoordinate'])->name('signKoordinatTTE');

    // Route::post('/berkas', [App\Http\Controllers\APITTEController::class, 'manageBerkas'])->name('berkas');

});

Route::get('/naskah-tte/{id}', [\App\Http\Controllers\NaskahTTEController::class, 'index_ket_tte'])->name('naskah-tte');