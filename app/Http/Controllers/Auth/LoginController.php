<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\Pegawai; 
use Alert;
use Illuminate\Http\Request;
use Session;


class LoginController extends Controller
{
    use AuthenticatesUsers;


    // public function login(Request $request){
    //     $credentials = $request->only('username', 'password');

    //     if(auth()->attempt($credentials)){
    //         Alert::success('Login Berhasil!', 'Selamat Datang');
    //         return redirect()->route('dashboard');
    //     }else{
    //         Alert::error('Oops! Login Gagal.', 'Terdapat Kesalahan!');
    //         return redirect()->back();
    //     }
    // }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        // Authenticate the user
        if (auth()->attempt($credentials)) {
            if(auth()->user()->role == 'petugas'){
                $user_data = Pegawai::where('nik', $credentials['username'])->first(); // Assuming 'username' is the NIK
                $ses_data = [
                    'id' => $user_data->nik, // Assuming 'nik' is the user ID
                    'username' => $user_data->nama, // Assuming 'nama' is the username
                ];
                Session::put($ses_data);
                Alert::success('Login Berhasil!', 'Selamat Datang! Petugas BPJS');
                return redirect()->route('dashboard');
            }else if(auth()->user()->role == 'ppa'){
                $user_data = Pegawai::where('nik', $credentials['username'])->first(); // Assuming 'username' is the NIK
                $ses_data = [
                    'id' => $user_data->nik, // Assuming 'nik' is the user ID
                    'username' => $user_data->nama, // Assuming 'nama' is the username
                ];
                Session::put($ses_data);
                Alert::success('Login Berhasil!', 'Selamat Datang! Petugas PPA ');
                return redirect()->route('dashboard');
            }else if(auth()->user()->role == 'perawat'){
                $user_data = Pegawai::where('nik', $credentials['username'])->first(); // Assuming 'username' is the NIK
                $ses_data = [
                    'id' => $user_data->nik, // Assuming 'nik' is the user ID
                    'username' => $user_data->nama, // Assuming 'nama' is the username
                ];
                Session::put($ses_data);
                Alert::success('Login Berhasil!', 'Selamat Datang! Perawat ');
                return redirect()->route('dashboard');
            }else{
                $user_data = Pegawai::where('nik', $credentials['username'])->first(); // Assuming 'username' is the NIK
                $ses_data = [
                    'id' => $user_data->nik, // Assuming 'nik' is the user ID
                    'username' => $user_data->nama, // Assuming 'nama' is the username
                ];
                Session::put($ses_data);
                Alert::success('Login Berhasil!', 'Selamat Datang! Admin ');
                return redirect()->route('dashboard');
            }
        } else {
            Alert::error('Oops! Login Gagal.', 'Terdapat kesalahan pada username atau password! ');
            return redirect()->back();
        }             
    }
    
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}