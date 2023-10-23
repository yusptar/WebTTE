<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Alert;
use Illuminate\Http\Request;


class LoginController extends Controller
{
    use AuthenticatesUsers;
    

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Check the login credentials using your custom logic
        $username = $request->input('username');
        $password = $request->input('password');

        $authenticated = $this->customLoginLogic($username, $password);

        if ($authenticated) {
            Alert::success('Selamat Datang', 'Login Berhasil');
            return redirect()->route('home');
        } else {
            Alert::error('Maaf', 'Login Gagal');
            return redirect()->route('login');
        }
    }

    // Your custom authentication logic
    protected function customLoginLogic($username, $password)
    {
        $model = new User();
        $data = $model->checkLogin($username, $password);

        if ($data) {
            // Customize this part based on your user data
            $ses_data = [
                'user_id'       => $data['nik'], // Assuming this is the user's ID
                'user_fullname' => $data['nama'], // Assuming this is the user's name
                'logged_in'     => true
            ];

            $this->guard()->login($user);

            return true;
        }

        return false;
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}