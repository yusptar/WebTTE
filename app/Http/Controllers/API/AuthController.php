<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        
        $credentials = $request->only('username', 'password');
        // Authenticate the user
        if (!auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => [
                        'Invalid credentials'
                    ]
                ]
            ],401);
        }

        $user_data = User::where('username', $credentials['username'])->first();
        $authToken = $user_data->createToken('auth-token')->plainTextToken;

        return response()->json([
            'access_token' => $authToken,
        ]);
    }

}