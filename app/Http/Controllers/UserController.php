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
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        return view('settings.users');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function user_detail(Request $request)
    {
        $id = $request->id;
        $user_details = User::find($id);
        return response()->json(['details' => $user_details]);
    }


    public function user_list()
    {
        $users = User::all();
        return DataTables::of($users)
            ->addColumn('actions', function ($row) {
                return
                    '<div class="btn-group" role="group">
                <button id="edit_user_btn" type="button" class="btn btn-outline-primary" data-id="' . $row['id'] . '"><i class="fas fa-edit"></i></button>
                <button id="delete_user_btn"  type="button" class="btn btn-outline-danger" data-id="' . $row['id'] . '"><i class="fas fa-trash"></i></button>
              </div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
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
        $id = $request->id;

        $request->validate([
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        $user = User::find($id);
        $user->password = Hash::make($request->password);

        if ($user->save()) {
            return response()->json(['code' => 1]);
        } else {
            return response()->json(['code' => 0]);
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $query = User::find($id);
        $query->delete();

        if (!$query) {
            return response()->json(['code' => 0]);
        } else {
            return response()->json(['code' => 1]);
        }
    }


    public function __construct()
    {
        $this->middleware('auth');
    }

}