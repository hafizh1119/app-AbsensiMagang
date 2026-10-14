<?php

namespace App\Http\Controllers\Admin\Management;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Halaman ini KHUSUS mengelola akun dengan role 'admin' di database
     * (ditampilkan ke user sebagai "Super Admin").
     */
    private const AVAILABLE_ROLES = ['admin'];

    public function index(Request $request): View
    {
        $query = User::whereIn('role', self::AVAILABLE_ROLES)
            ->orderBy('nama');

        if ($search = $request->input('q')) {
            $query->where(function ($sub) use ($search) {
                $sub->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $admins = $query->paginate(10)->withQueryString();

        return view('admin.manajemen.admin.index', compact('admins'));
    }

    /**
     * Form tambah/edit sekarang berupa modal di halaman index,
     * jadi halaman terpisah dialihkan ke index.
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('admin.manajemen.admin');
    }

    public function show(string $id): RedirectResponse
    {
        return redirect()->route('admin.manajemen.admin');
    }

    public function edit(string $id): RedirectResponse
    {
        return redirect()->route('admin.manajemen.admin');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);

        User::create([
            'nama'     => $validated['nama'],
            'nip'      => null,
            'jabatan'  => 'Super Admin',
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'admin',
            'unit_id'  => null,
            'status'   => 'aktif',
        ]);

        return redirect()
            ->route('admin.manajemen.admin')
            ->with('success', 'Admin berhasil ditambahkan.');
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $admin = User::whereIn('role', self::AVAILABLE_ROLES)->findOrFail($id);

        $validated = $this->validateData($request, $admin->id);

        $payload = [
            'nama'  => $validated['nama'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $admin->update($payload);

        return redirect()
            ->route('admin.manajemen.admin')
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $admin = User::whereIn('role', self::AVAILABLE_ROLES)->findOrFail($id);

        if ($admin->id === auth()->id()) {
            return redirect()
                ->route('admin.manajemen.admin')
                ->with('error', 'Tidak bisa menghapus akun yang sedang digunakan.');
        }

        $admin->delete();

        return redirect()
            ->route('admin.manajemen.admin')
            ->with('success', 'Admin berhasil dihapus.');
    }

    /**
     * Validasi form tambah/edit admin. Hanya nama, email, dan password.
     */
    private function validateData(Request $request, ?int $ignoreUserId = null): array
    {
        $isCreate = is_null($ignoreUserId);

        return $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email' . ($ignoreUserId ? ",{$ignoreUserId}" : '')],
            'password' => [$isCreate ? 'required' : 'nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'nama.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah digunakan.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);
    }
}