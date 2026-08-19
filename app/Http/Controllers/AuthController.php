<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showAdminLogin()
    {
        return view('admin.login');
    }

    public function processAdminLogin(Request $request)
    {
        $credentials = [
            'email' => $request->username,
            'password' => $request->password,
            'role' => 'admin'
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/admin/dashboard');
        }

        return back()->with('error', 'Login gagal. Periksa kembali email dan password Anda.');
    }

    public function showPimpinanLogin()
    {
        return view('pimpinan.login');
    }

    public function processPimpinanLogin(Request $request)
    {
        $credentials = [
            'email' => $request->username,
            'password' => $request->password,
            'role' => 'pimpinan'
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/pimpinan/dashboard');
        }

        return back()->with('error', 'Login gagal. Periksa kembali email dan password Anda.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
