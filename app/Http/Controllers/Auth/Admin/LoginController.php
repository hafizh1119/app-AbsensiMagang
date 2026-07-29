<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Tampilkan form login admin.
     */
    public function showLoginForm(): View
    {
        return view('auth.admin.login');
    }

    /**
     * Proses login admin.
     * Hanya user dengan role 'admin' (Super Admin) yang boleh masuk lewat sini.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password salah.');
        }

        // Login berhasil secara kredensial, tapi cek dulu apakah role-nya admin.
        if (Auth::user()->role !== 'admin') {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Akun ini tidak memiliki akses sebagai Admin.');
        }

        $request->session()->regenerate();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Selamat datang kembali, ' . Auth::user()->nama . '!');
    }

    /**
     * Logout admin.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('success', 'Anda berhasil keluar.');
    }
}