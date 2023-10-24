<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\Pegawai; 
use Alert;
use Illuminate\Http\Request;


class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        // Authenticate the user
        if (auth()->attempt($credentials)) {
            $user_data = Pegawai::where('nik', $credentials['username'])->first(); // Assuming 'username' is the NIK
            $ses_data = [
                'id' => $user_data->nik, // Assuming 'nik' is the user ID
                'username' => $user_data->nama, // Assuming 'nama' is the username
            ];
            Session::put($ses_data);
            Alert::success('Login Berhasil!', 'Selamat Datang');
            return redirect()->route('dashboard');
        } else {
            Alert::error('Oops! Login Gagal.', 'Terdapat Kesalahan!');
            return redirect()->back();
        }             
    }
    
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}