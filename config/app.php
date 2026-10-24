<?php

use Illuminate\Support\Facades\Facade;

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    */

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Sengaja ditulis langsung (bukan dari .env) seperti versi lama, agar
    | jam tanggal_upload / tgl_signed tetap memakai WIB.
    |
    */

    'timezone' => 'Asia/Jakarta',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    */

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | Alias bawaan Laravel (DB, Auth, Storage, Config, dst.) tetap tersedia.
    | Service provider sekarang didaftarkan di bootstrap/providers.php, dan
    | paket pihak ketiga (DomPDF, SweetAlert, DataTables) terdaftar otomatis.
    |
    */

    'aliases' => Facade::defaultAliases()->merge([
        'Alert' => RealRashid\SweetAlert\Facades\Alert::class,
    ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi khusus aplikasi TTE
    |--------------------------------------------------------------------------
    */

    'baseurl_api' => env('BASEURL_API', ''),
    'url_api_wa' => env('URL_API_WA', ''),
    'url_api_wa_oca' => env('URL_API_WA_OCA', ''),

    // Dulu dibaca langsung lewat env('URL_IMAGE') dan env('TOKEN') di view.
    // Lewat config agar tetap jalan setelah `php artisan config:cache`.
    'url_image' => env('URL_IMAGE', ''),
    'token' => env('TOKEN', ''),

];
