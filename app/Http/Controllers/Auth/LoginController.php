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
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    public function login(Request $request){
        $input = $request->all();
        $this->validate($request,[
            'username' => 'required',
            'password' => 'required',
        ]);

        if(auth()->attempt(array('username'=>$input['username'], 'password'=>$input['password']))){
            return redirect()->route('dashboard');
            Alert::success('Selamat Datang', 'Login Berhasil');
        }else{
            return redirect()->route('login');
            Alert::error('Oops...', 'Login Gagal');
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}