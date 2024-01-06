<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AuthController extends Controller
{
    public function handleLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->only('remember') ? true : false;
        if (auth()->attempt($credentials, $remember)) {
            $user = auth()->user();
            $token = $user->createToken('API Token')->accessToken;
            return response()->json(['user' => auth()->user(), 'token' => $token], 200);
        }

        return response()->json(['error' => 'Unauthenticated'], 401);
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            // dd($user);
            return redirect()->route('dashboard');
        }

        return redirect()->back()->with('error', "Invalid");
    }

    public function logout()
    {
    }
}
