<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt([
            'identifier' => $request->identifier,
            'password'   => $request->password
        ])) {
            $request->session()->regenerate();

            $user = Auth::user();

            return match ($user->role) {
                'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
                'BAK'       => redirect()->route('bak.dashboard'),
                'DEKAN'     => redirect()->route('dekan.dashboard'),
                'admin'     => redirect()->route('admin.dashboard'),
                default     => redirect('/login'),
            };
        }

        return back()->withInput()->with('failed', 'Username atau password salah!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
