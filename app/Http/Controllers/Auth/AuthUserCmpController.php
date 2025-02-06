<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SysUser;
use Illuminate\Support\Facades\Auth;

class AuthUserCmpController extends Controller
{
    private $salt = '!@C#$m%^&P*';

    public function showLoginForm()
    {
        // If user is already logged in, redirect to dashboard
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        // dd('puki');
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        // Generate hashed password sesuai format: md5(salt+username+password)
        $hashedPassword = md5($this->salt . $request->email . $request->password);

        // Cari user berdasarkan username/email dan password yang sudah di-hash
        $user = SysUser::where('username', $request->email)
            ->where('password', $hashedPassword)
            ->where('status', 1)
            ->first();

        if ($user) {
            // Login manual menggunakan guard 'web'
            Auth::guard('web')->loginUsingId($user->id);

            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Username atau Password Salah'
        ]);
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('login');
    }
}
