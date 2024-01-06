<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');


        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('API Token')->accessToken;
            return response()->json(['user' => auth()->user(), 'token' => $token], 200);
        }

        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json('Logged out successfully', 200);
    }
}
