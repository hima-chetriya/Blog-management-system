<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $user->tokens()->delete();

        // new token
        $token = $user->createToken('API Token')->plainTextToken;
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
        ]);
    }

}
