<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\Pegawai; 
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LoginController extends Controller implements HasMiddleware
{
    use AuthenticatesUsers;

    public function login(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('username', 'password');

        // Rate limiting
        $key = Str::lower($request->input('username')).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return redirect()->back()->with('error', 'Terlalu banyak melakukan kesalahan login, coba lagi setelah ' . $seconds . ' detik');
        }

        // Authenticate the user
        if (auth()->attempt($credentials)) {
            RateLimiter::clear($key); // Clear the rate limit on successful login

            // Cek role pengguna
            $user = auth()->user();
            $user_data = Pegawai::where('nik', $credentials['username'])->first();

            if ($user) {
                $ses_data = [
                    'id' => $user_data->nik,
                    'username' => $user_data->nama,
                ];
                Session::put($ses_data);

                switch ($user->role) {
                    case 'petugas':
                        Alert::success('Login Berhasil!', 'Selamat Datang! ' .  $user_data->nama);
                        return redirect()->route('dashboard');
                    case 'ppa':
                        Alert::success('Login Berhasil!', 'Selamat Datang! ' .  $user_data->nama);
                        return redirect()->route('dashboard');
                    case 'perawat':
                        Alert::success('Login Berhasil!', 'Selamat Datang! ' .  $user_data->nama);
                        return redirect()->route('dashboard');
                    default:
                        Alert::success('Login Berhasil!', 'Selamat Datang! Admin');
                        return redirect()->route('dashboard');
                }
            } else {
                auth()->logout();
                Alert::error('Oops! Login Gagal.', 'User data tidak ditemukan.');
                return redirect()->back();
            }
        } else {
            RateLimiter::hit($key); // Hit the rate limit
            Alert::error('Oops! Login Gagal.', 'Terdapat kesalahan pada username atau password! ');
            return redirect()->back();
        }
    }

    /**
     * Get the middleware that should be assigned to the controller.
     * (Dulu $this->middleware('guest')->except('logout') di constructor)
     */
    public static function middleware(): array
    {
        return [
            new Middleware('guest', except: ['logout']),
        ];
    }
}