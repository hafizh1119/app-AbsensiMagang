<?php

namespace App\Http\Controllers\Admin\Management;

use App\Http\Controllers\Controller;
use App\Models\JenisUnit;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    /**
     * Daftar role yang tersedia untuk dipilih saat mengubah user.
     * Nilai ini yang tersimpan di database (jangan diubah).
     */
    private const AVAILABLE_ROLES = ['admin_unit', 'pegawai'];

    /**
     * Nama tampilan role. Hanya untuk tampilan, database tetap memakai
     * admin_unit dan pegawai.
     */
    private const ROLE_LABELS = [
        'admin'      => 'Admin',
        'admin_unit' => 'Admin Ruangan',
        'pegawai'    => 'Peserta',
    ];

    /**
     * Menampilkan seluruh user, bisa dicari (nama/NIP/email) dan
     * difilter berdasarkan unit. Tidak ada fitur tambah user di sini.
     */
    /**
     * Halaman "Peserta": hanya user dengan role pegawai, bisa dicari
     * (nama/NIP/email) dan difilter berdasarkan unit.
     */
    public function index(Request $request): View
    {
        $query = User::with('unit')
            ->where('role', 'pegawai')
            ->orderBy('nama');

        $query = $this->applyFilters($query, $request);

        $users = $query->paginate(10)->withQueryString();

        $units      = JenisUnit::orderBy('nama_unit')->get();
        $roles      = self::AVAILABLE_ROLES;
        $roleLabels = self::ROLE_LABELS;

        return view('admin.manajemen.user.index', compact('users', 'units', 'roles', 'roleLabels'));
    }

    /**
     * Halaman "Admin Ruangan": hanya user dengan role admin_unit.
     * Admin ruangan adalah akun yang memverifikasi pengajuan izin peserta.
     */
    public function adminRuangan(Request $request): View
    {
        $query = User::with('unit')
            ->where('role', 'admin_unit')
            ->orderBy('nama');

        $query = $this->applyFilters($query, $request);

        $users = $query->paginate(10)->withQueryString();

        $units      = JenisUnit::orderBy('nama_unit')->get();
        $roles      = self::AVAILABLE_ROLES;
        $roleLabels = self::ROLE_LABELS;

        return view('admin.manajemen.admin_ruangan.index', compact('users', 'units', 'roles', 'roleLabels'));
    }

    /**
     * Dinonaktifkan — user baru dibuat lewat modul Data Master Pegawai,
     * bukan dari halaman Manajemen User.
     */
    public function create(): void
    {
        throw new NotFoundHttpException();
    }

    /**
     * Dinonaktifkan — lihat catatan pada method create().
     */
    public function store(Request $request): void
    {
        throw new NotFoundHttpException();
    }

    /**
     * Dinonaktifkan — detail user cukup dilihat lewat modul Data Master Pegawai.
     */
    public function show(string $id): void
    {
        throw new NotFoundHttpException();
    }

    /**
     * Hanya menampilkan form untuk mengubah role. Data lain
     * (nama, NIP, email, unit, dst) ditampilkan sebagai info read-only.
     */
    public function edit(string $id): View
    {
        $user       = User::with('unit')->findOrFail($id);
        $roles      = self::AVAILABLE_ROLES;
        $roleLabels = self::ROLE_LABELS;

        return view('admin.manajemen.user.edit', compact('user', 'roles', 'roleLabels'));
    }

    /**
     * Hanya field role yang diproses & disimpan.
     *
     * Kalau dikirim dari modal (_form=edit), kembali ke halaman asal
     * dengan filter yang sama; kalau dari halaman edit, ke daftar user.
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

        $redirect = $request->input('_form') === 'edit'
            ? redirect()->back()
            : redirect()->route('admin.manajemen.user');

        return $redirect->with('success', 'Role user berhasil diperbarui.');
    }

    /**
     * Dinonaktifkan — user tidak dihapus dari halaman ini.
     */
    public function destroy(string $id): void
    {
        throw new NotFoundHttpException();
    }

    /**
     * Filter pencarian (nama/NIP/email) dan unit, dipakai bersama
     * oleh index() dan adminRuangan().
     */
    private function applyFilters(Builder $query, Request $request): Builder
    {
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

        return $query;
    }
}