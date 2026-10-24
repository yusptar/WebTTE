<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | Route reset password dinonaktifkan di routes/web.php ('reset' => false).
    | Controller ini dipertahankan agar struktur auth laravel/ui tetap utuh.
    |
    */

    use SendsPasswordResetEmails;
}
