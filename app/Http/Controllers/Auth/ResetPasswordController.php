<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | Route reset password dinonaktifkan di routes/web.php ('reset' => false).
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     * (Dulu RouteServiceProvider::HOME)
     *
     * @var string
     */
    protected $redirectTo = '/';
}
