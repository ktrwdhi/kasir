<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class authController extends Controller
{
    public function login(Request $request)
    {
        $validate = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($validate)) {
            $user = user::where('username', $request->username)->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ]);
        }
        else{
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }


    }
}