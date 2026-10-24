# Upgrade WebTTE: Laravel 8 ke Laravel 13

Dokumen ini menjelaskan apa saja yang diubah saat WebTTE dinaikkan dari Laravel 8.75 ke Laravel 13, dan langkah memasangnya di server. Alur sistem (route, nama route, tabel database, format respons JSON, alur upload dan tanda tangan) **tidak diubah**.

## 1. Kebutuhan server

- PHP **8.3 sampai 8.5**
- Composer 2.2 atau lebih baru
- Ekstensi PHP yang wajib aktif di `php.ini`:
  `openssl`, `curl`, `mbstring`, `fileinfo`, `pdo_mysql`, `zip`, `gd`, `intl`, dan **`bcmath` atau `gmp`**
  (`bcmath`/`gmp` dipakai Hashids untuk halaman QR `/naskah-tte/{id}`; tanpa ini halaman tersebut error 500)
- `curl.cainfo` dan `openssl.cafile` di `php.ini` diarahkan ke `cacert.pem` (lihat catatan SSL sebelumnya)

## 2. Langkah pemasangan

1. Backup folder project lama dan database.
2. Pakai folder hasil upgrade ini sebagai folder project baru. **Jangan ditimpa di atas folder lama**, karena file lama yang sudah dihapus (misalnya `resources/views/vendor/sweetalert/alert.blade.php` dan `app/Http/Kernel.php`) akan tertinggal dan mengganggu.
3. Salin dari project lama ke folder baru:
   - file `.env`
   - folder `storage/` (berisi file rekam medis, QR code, dan log)
   - folder `public/storage/` (file yang dikirim lewat WhatsApp)
4. Pastikan folder baru tidak berisi `vendor/`, `composer.lock`, maupun file `*.php` di `bootstrap/cache/`.
5. Tambahkan baris ini ke `.env` (nilainya diambil dari baris `Authorization: Bearer ...` di `TTEController.php` versi lama):
   ```env
   OCA_API_TOKEN=isi_token_oca_dari_kode_lama
   ```
6. Jalankan:
   ```bash
   composer update
   php artisan migrate
   php artisan optimize:clear
   ```
   `php artisan migrate` aman dijalankan pada database SIMRS yang sudah ada: migrasi hanya menambah kolom `expires_at` ke tabel `personal_access_tokens` (dibutuhkan Sanctum 4 untuk login API `/api/auth`). Tabel lain tidak disentuh.
7. Uji: login, dashboard, upload RM, pembubuhan TTE, list dokumen, download, kirim WA, dan halaman QR `/naskah-tte/{id}`.

## 3. Perubahan struktur (gaya Laravel 13)

| Laravel 8 (lama) | Laravel 13 (baru) |
|---|---|
| `app/Http/Kernel.php` | Dihapus. Middleware diatur di `bootstrap/app.php` (`withMiddleware`) |
| `app/Http/Middleware/*` (8 file) | Dihapus. Semuanya sudah bawaan Laravel 13 dengan perilaku sama |
| `app/Console/Kernel.php` | Dihapus. Command otomatis dimuat dari `routes/console.php` |
| `app/Exceptions/Handler.php` | Dihapus. Diganti `withExceptions` di `bootstrap/app.php` |
| `AuthServiceProvider` (Gate admin, ppa, petugas, perawat, admin-it) | Dipindah ke `AppServiceProvider::configureGates()` |
| `RouteServiceProvider` (rate limit API 60/menit, HOME = `/`) | Dipindah ke `AppServiceProvider` dan `bootstrap/app.php` |
| `EventServiceProvider`, `BroadcastServiceProvider` | Dihapus (isinya bawaan, broadcast memang tidak aktif) |
| `config/app.php` daftar `providers` dan `aliases` | Provider di `bootstrap/providers.php`; paket terdaftar otomatis |
| `$this->middleware('auth')` di constructor controller | `implements HasMiddleware` + `public static function middleware()` |
| `public/index.php`, `artisan` | Versi Laravel 13 |
| `server.php` | Dihapus (tidak dipakai lagi) |

Pemetaan middleware lama ke baru:

- `Authenticate::redirectTo()` ke halaman login: `redirectGuestsTo(route('login'))`
- `RedirectIfAuthenticated` ke `/`: `redirectUsersTo('/')`
- Grup `api` dengan `throttle:api`: `throttleApi()` + rate limiter `api` 60 request/menit
- `TrustProxies`, `HandleCors` (membaca `config/cors.php`), `TrimStrings` (password tidak di-trim), `PreventRequestsDuringMaintenance`, `VerifyCsrfToken`: semuanya bawaan Laravel 13
- `TrustHosts` tetap tidak aktif, sama seperti sebelumnya

## 4. Perubahan paket

| Paket | Lama | Baru | Keterangan |
|---|---|---|---|
| laravel/framework | ^8.75 | ^13.0 | |
| laravel/sanctum | ^2.15 | ^4.0 | Perlu kolom `expires_at` (sudah ada migrasinya) |
| laravel/tinker | ^2.5 | ^3.0 | |
| laravel/ui | ^3.4 | ^4.6 | `Auth::routes()` tetap sama |
| barryvdh/laravel-dompdf | ^2.0 | ^3.1 | |
| realrashid/sweet-alert | ^7.1 | ^8.0 | `Alert::success()` / `Alert::error()` tetap sama |
| yajra/laravel-datatables-oracle | ^9.21 | ^13.0 | `DataTables::of()` tetap sama |
| yajra/laravel-datatables | ^1.5 | dihapus | Bundle lama; hanya `DataTables::of()` yang dipakai |
| vinkla/hashids | ^9.1 | dihapus | Diganti `hashids/hashids ^5.0` langsung. Hasil enkode **identik** (sudah diuji), QR lama tetap valid |
| soarecostin/file-vault | ^1.2 | dihapus | Tidak mendukung Laravel 9 ke atas. Hanya dipakai `app/Jobs/EncryptFile.php` yang tidak pernah dipanggil di mana pun, sehingga job itu ikut dihapus |
| fruitcake/laravel-cors | ^2.0 | dihapus | Sudah bawaan Laravel |
| facade/ignition | ^2.5 | dihapus | |
| phpunit/phpunit | ^9.5 | ^12.0 | |
| nunomaduro/collision | ^5.10 | ^8.6 | |

## 5. Perbaikan yang ikut dilakukan

Perbaikan ini diperlukan agar jalan di Laravel 13 / PHP 8.5, atau menutup celah keamanan, tanpa mengubah alur:

- **`App\Models\Pegawai`**: tambah `getAuthPasswordName()` (wajib sejak Laravel 11).
- **`config/database.php`**: konstanta `PDO::MYSQL_ATTR_SSL_CA` diganti `Pdo\Mysql::ATTR_SSL_CA` di PHP 8.5 (konstanta lama sudah deprecated).
- **`config/sanctum.php`**: format Sanctum 4 (versi lama merujuk middleware yang sudah dihapus).
- **Dashboard**: nama bulan grafik dibuat aman untuk Carbon 3 (dulu bisa meleset pada tanggal 29 sampai 31) dan sekarang berbahasa Indonesia (Oktober, bukan October).
- **Route register**: dulu tertulis `'reigster' => false` (salah ketik), sehingga **POST `/register` terbuka dan siapa pun bisa membuat akun**. Sekarang `'register' => false`. Halaman register memang sudah disembunyikan (menampilkan 404), jadi tidak ada fitur yang hilang.
- **Token WhatsApp OCA**: dulu ditulis langsung di `TTEController::kirimWAOCA`. Sekarang dibaca dari `.env` (`OCA_API_TOKEN`). Karena token itu sudah tersimpan di riwayat git, **sebaiknya minta token baru ke penyedia OCA**.
- **Route `/naskah-tte/{id}` dobel**: versi di dalam grup `auth` selalu tertimpa versi publik di bawahnya (method-nya pun sudah tidak ada). Yang dihapus hanya versi yang tidak pernah terpakai; perilaku tetap sama.
- `env('URL_IMAGE')` dan `env('TOKEN')` di view diganti `config('app.url_image')` / `config('app.token')` agar tetap jalan setelah `php artisan config:cache`.
- Halaman login: memperbaiki error JavaScript (`login-button` tidak ditemukan) saat pesan "terlalu banyak percobaan" muncul.

## 6. Perubahan tampilan

Tampilan tetap memakai AdminLTE 3, sehingga semua ID, class, dan JavaScript lama tetap berfungsi. Yang berubah:

- File tema baru `public/css/tte-theme.css`: warna biru tinta (struktur) dan hijau stempel (aksi/SUDAH), status BELUM/SUDAH tampil seperti cap stempel, tabel, form, modal, dan pagination yang lebih rapi.
- Font Public Sans, Chart.js 4, dan SweetAlert2 11 disimpan lokal di `public/vendor/` (tidak bergantung CDN; tetap jalan di jaringan intranet).
- **Lebih ringan**: script demo AdminLTE yang tidak dipakai dihapus dari layout (jQuery UI, JQVMap + peta USA, Knob, Sparkline, Summernote, Tempusdominus, `demo.js`, `pages/dashboard.js`, Chart.js 2 lokal, DataTables Buttons + JSZip + pdfmake, dan SweetAlert v1 yang dimuat 2 kali). Sebelumnya beberapa script itu memunculkan error di console di setiap halaman.
- Halaman login baru (dua panel, tombol lihat password, cegah klik ganda).
- Navbar dengan nama + role pengguna, footer rapi, dashboard dengan kartu ringkasan dan grafik yang responsif di HP.
- Teks DataTables berbahasa Indonesia, bar loading tipis di atas layar saat AJAX berjalan.
- Kolom passphrase TTE sekarang disamarkan (type password).

## 7. Temuan yang sengaja TIDAK diubah

Hal-hal berikut sudah ada sejak versi lama. Tidak diubah agar alur tetap sama, tapi sebaiknya ditinjau:

1. **Detail RM Rawat Jalan selalu kosong** (`TTEController::rm_detail`). `getJenisPelayanan()` mengembalikan objek, lalu dibandingkan dengan string `"Ralan"`, sehingga hasilnya selalu `"1"` (Ranap). Perbaikannya: `->status_lanjut == "Ralan"`.
2. Route `update-tte` (`TTEController@update`) dan `sign-koordinat` (`APITTEController@signCoordinate`) mengarah ke method yang sudah dikomentari. Route `view-dokumen-rj` dan `view-dokumen-ri` mengarah ke method yang tidak ada. Jika dipanggil akan error 500 (sama seperti dulu).
3. `APITTEController` memakai `dd()` di blok error (akan menampilkan dump ke pengguna) dan username/password API TTE (`esign`/`qwerty`) ditulis langsung di kode.
4. `kirimWAOCA` mematikan verifikasi SSL (`CURLOPT_SSL_VERIFYPEER => false`) dan saat sukses mengembalikan seluruh isi request sebagai pesan.
5. `getStatusUser` mengirim request dua kali dan memakai NIK tetap.

## 8. Yang sudah diuji

Diuji dengan Laravel 13.38.0 pada PHP 8.3 dan MariaDB berisi salinan struktur tabel SIMRS yang dipakai aplikasi:

- `composer install`, `package:discover`, `route:list` (49 route), `config:cache`, `route:cache`, `view:cache`
- Migrasi pada tabel `personal_access_tokens` gaya lama
- Login web (berhasil, gagal, rate limit), logout, redirect tamu
- Semua halaman (admin, PPA, petugas) dan semua endpoint DataTables
- Simpan user, upload RM (file tersimpan di `storage/app/rekam-medis/...`), download, hapus berkas
- Login API `/api/auth` (token Sanctum), upload API `/api/api-store-pdf`, penolakan tanpa token (401)
- Halaman QR publik `/naskah-tte/{id}` dengan Hashids
- Tampilan desktop dan HP di Chromium, tanpa error JavaScript di console

Yang **belum** bisa diuji di sini karena butuh server asli: proses tanda tangan ke API BSrE (`/sign-invisible`), pengiriman WhatsApp OCA, dan PHP 8.5 (pengujian memakai 8.3; Laravel 13 dan semua paket di atas mendukung 8.5).
