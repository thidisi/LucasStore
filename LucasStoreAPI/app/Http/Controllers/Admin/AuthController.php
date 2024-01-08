<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AuthController extends Controller
{
    public function handleLogin(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->only('remember') ? true : false;
        if (auth()->attempt($credentials, $remember)) {
            $user = auth()->user();
            $token = $user->createToken('API Token')->accessToken;
            return response()->json(['user' => auth()->user(), 'token' => $token], 200);
        }

        return response()->json(['error' => 'Login error'], 401);
    }


    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->token()->revoke();
            return response()->json(['message' => 'Logout successful'], 200);
        } else {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
    }
}
