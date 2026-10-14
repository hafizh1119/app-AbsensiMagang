<?php

namespace App\Http\Controllers\Pengguna\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil pengguna yang sedang login.
     */
    public function index()
    {
        $user = Auth::user();

        return view('pengguna.profil.index', compact('user'));
    }

    /**
     * Update password pengguna.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => ['required', 'string'],
            'password'               => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password baru minimal 8 karakter.',
            'password.confirmed'        => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password lama yang Anda masukkan salah.',
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}