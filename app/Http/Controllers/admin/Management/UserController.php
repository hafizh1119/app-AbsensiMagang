<?php

namespace App\Http\Controllers\Admin\Management;

use App\Http\Controllers\Controller;
use App\Models\JenisUnit;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    /**
     * Daftar role yang tersedia untuk dipilih saat mengubah user.
     * Sesuaikan dengan role yang benar-benar dipakai di aplikasi kamu.
     */
    private const AVAILABLE_ROLES = ['admin_unit', 'pegawai'];

    /**
     * Display a listing of the resource.
     *
     * Menampilkan seluruh user, bisa dicari (nama/NIP/email) dan
     * difilter berdasarkan unit. Tidak ada fitur tambah user di sini.
     */
    public function index(Request $request): View
    {
        $query = User::with('unit')->orderBy('nama');

        if ($search = $request->input('q')) {
            $query->where(function ($sub) use ($search) {
                $sub->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($unitId = $request->input('unit_id')) {
            $query->where('unit_id', $unitId);
        }

        $users = $query->paginate(10)->withQueryString();

        $totalUser = User::count();
        $totalUnit = JenisUnit::count();
        $units     = JenisUnit::orderBy('nama_unit')->get();

        return view('admin.manajemen.user.index', compact('users', 'totalUser', 'totalUnit', 'units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * Dinonaktifkan — user baru dibuat lewat modul Data Master Pegawai,
     * bukan dari halaman Manajemen User.
     */
    public function create(): void
    {
        throw new NotFoundHttpException();
    }

    /**
     * Store a newly created resource in storage.
     *
     * Dinonaktifkan — lihat catatan pada method create().
     */
    public function store(Request $request): void
    {
        throw new NotFoundHttpException();
    }

    /**
     * Display the specified resource.
     *
     * Dinonaktifkan — detail user cukup dilihat lewat modul Data Master Pegawai.
     */
    public function show(string $id): void
    {
        throw new NotFoundHttpException();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * Hanya menampilkan form untuk mengubah role. Data lain
     * (nama, NIP, email, unit, dst) ditampilkan sebagai info read-only.
     */
    public function edit(string $id): View
    {
        $user  = User::with('unit')->findOrFail($id);
        $roles = self::AVAILABLE_ROLES;

        return view('admin.manajemen.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * Hanya field role yang diproses & disimpan.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'role' => ['required', 'string', 'in:' . implode(',', self::AVAILABLE_ROLES)],
        ], [
            'role.required' => 'Role wajib dipilih.',
            'role.in'       => 'Role yang dipilih tidak valid.',
        ]);

        $user->update([
            'role' => $validated['role'],
        ]);

        return redirect()
            ->route('admin.manajemen.user')
            ->with('success', 'Role user berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * Dinonaktifkan — user tidak dihapus dari halaman ini.
     */
    public function destroy(string $id): void
    {
        throw new NotFoundHttpException();
    }
}