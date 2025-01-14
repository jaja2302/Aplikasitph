<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengguna;

class AuthPenggunaController extends Controller
{
    //


    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        // Cari pengguna berdasarkan email dan password langsung (tanpa hash)
        $pengguna = Pengguna::where('email', $request->email)
            ->where('password', $request->password)
            ->first();

        if ($pengguna) {
            // Login manual
            Auth::guard('pengguna')->loginUsingId($pengguna->user_id);

            // Untuk debugging
            if (Auth::guard('pengguna')->check()) {
                return redirect()->intended('/dashboard');
            } else {
                return back()->withErrors(['email' => 'Gagal melakukan autentikasi']);
            }
        }

        return back()->withErrors(['email' => 'Email atau Password Salah']);
    }

    public function logout()
    {
        Auth::guard('pengguna')->logout();
        return redirect()->route('login');
    }
}
