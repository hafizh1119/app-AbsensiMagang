<?php

namespace App\Http\Controllers\Auth\Pengguna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.pengguna.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'identifier' => ['required', 'string'],
            'password'   => ['required', 'string'],
        ], [
            'identifier.required' => 'NIP atau Email wajib diisi.',
            'password.required'   => 'Password wajib diisi.',
        ]);

        $loginField = filter_var($credentials['identifier'], FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'nip';

        $remember = $request->boolean('remember');

        if (! Auth::attempt(
            [$loginField => $credentials['identifier'], 'password' => $credentials['password']],
            $remember
        )) {
            throw ValidationException::withMessages([
                'identifier' => 'NIP/Email atau password yang Anda masukkan salah.',
            ]);
        }

        $user = Auth::user();

        // Role yang diizinkan masuk lewat halaman login ini
        $allowedRoles = ['pegawai', 'admin_unit'];

        if (! in_array($user->role, $allowedRoles)) {
            Auth::logout();

            throw ValidationException::withMessages([
                'identifier' => 'Akun ini tidak memiliki akses sebagai pengguna.',
            ]);
        }

        if (isset($user->status) && $user->status !== 'aktif') {
            Auth::logout();

            throw ValidationException::withMessages([
                'identifier' => 'Akun Anda tidak aktif. Silakan hubungi admin.',
            ]);
        }

        $user->forceFill(['last_login' => now()])->save();

        $request->session()->regenerate();

        return redirect()->intended(route('pengguna.home'));
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}