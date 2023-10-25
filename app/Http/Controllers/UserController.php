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

    public function update(Request $request)
    {
        $user_id = $request->user_id;

        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try{
            $user = User::find($user_id);
            $user->password = Hash::make($request->password);
            $user->update();      
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json(['success' => 'Berhasil update data'], 200);
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $query = User::find($id);
        $query->delete();

        if (!$query) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'User has been deleted']);
        }
    }

    public function __construct()
    {
        $this->middleware('auth');
    }
}