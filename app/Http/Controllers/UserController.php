<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();
        $pegawai = Pegawai::all();
        return view('settings.users', compact('user'));
    }
    
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function store(Request $request)
    {    
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        
        try{
            $user = User::create([
                'username' => $request->username, 
                'password' => Hash::make($request->password),
            ]);        
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json(['success' => 'Berhasil menambahkan data', 'data' => $user], 200);
    }

    public function edit($id)
    {
        $users = User::find($id);
        return response()->json($users);
    }

    public function destroy($id)
    {
        User::find($id)->delete();   
        return response()->json(['success'=>'User deleted successfully.']);
    }


    public function __construct()
    {
        $this->middleware('auth');
    }
}