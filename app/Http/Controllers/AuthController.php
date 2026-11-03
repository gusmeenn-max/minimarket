<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view("auth.login");
    }


    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            "email" => ["required", "email"],
            "password" => ["required"],
        ], ["email.required" => "Email wajib diisi.", "email.email" => "Format email tidak valid.", "password.required" => "Password wajib diisi"]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route("dashboard"))->with("success", "Login berhasil.");
        }

        return back()->withInput($request->only("email"))->with("error", "Email atau password salah.");
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route("login")->with("success", "Logout berhasil");
    }
}
