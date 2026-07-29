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
     * Role yang termasuk kategori "Admin" pada modul ini.
     * Halaman ini KHUSUS mengelola akun dengan role 'admin' di database
     * (ditampilkan ke user sebagai "Super Admin").
     * Role 'admin_unit' TIDAK ditampilkan/dikelola di halaman ini —
     * itu dikelola lewat modul Manajemen Pengguna/Unit yang terpisah.
     */
    private const AVAILABLE_ROLES = ['admin'];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = User::whereIn('role', self::AVAILABLE_ROLES)
            ->orderBy('nama');

        if ($search = $request->input('q')) {
            $query->where(function ($sub) use ($search) {
                $sub->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $admins = $query->paginate(10)->withQueryString();

        $totalAdmin    = User::whereIn('role', self::AVAILABLE_ROLES)->count();
        $totalAktif    = User::whereIn('role', self::AVAILABLE_ROLES)->where('status', 'aktif')->count();
        $totalNonaktif = User::whereIn('role', self::AVAILABLE_ROLES)->where('status', 'nonaktif')->count();

        return view('admin.manajemen.admin.index', compact(
            'admins', 'totalAdmin', 'totalAktif', 'totalNonaktif'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.manajemen.admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);

        User::create([
            'nama'     => $validated['nama'],
            'nip'      => $validated['nip'],
            'jabatan'  => $validated['jabatan'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'admin',
            'unit_id'  => null,
            'status'   => $validated['status'],
        ]);

        return redirect()
            ->route('admin.manajemen.admin')
            ->with('success', 'Admin berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * Tidak dipakai — detail admin cukup dilihat lewat halaman edit.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.manajemen.admin.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $admin = User::whereIn('role', self::AVAILABLE_ROLES)->findOrFail($id);

        return view('admin.manajemen.admin.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $admin = User::whereIn('role', self::AVAILABLE_ROLES)->findOrFail($id);

        $validated = $this->validateData($request, $admin->id);

        $payload = [
            'nama'    => $validated['nama'],
            'nip'     => $validated['nip'],
            'jabatan' => $validated['jabatan'],
            'email'   => $validated['email'],
            'role'    => 'admin',
            'unit_id' => null,
            'status'  => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $admin->update($payload);

        return redirect()
            ->route('admin.manajemen.admin')
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
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
     * Validasi input form tambah/edit admin.
     * Role pada halaman ini selalu 'admin' (Super Admin) dan di-hardcode
     * di store()/update(), sehingga form tidak perlu kirim field role/unit_id.
     */
    private function validateData(Request $request, ?int $ignoreUserId = null): array
    {
        $isCreate = is_null($ignoreUserId);

        $validated = $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'nip'      => ['required', 'string', 'max:50', 'unique:users,nip' . ($ignoreUserId ? ",{$ignoreUserId}" : '')],
            'jabatan'  => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email' . ($ignoreUserId ? ",{$ignoreUserId}" : '')],
            'password' => [$isCreate ? 'required' : 'nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Checkbox "status" hanya terkirim kalau dicentang.
        $validated['status'] = $request->boolean('status') ? 'aktif' : 'nonaktif';

        return $validated;
    }
}