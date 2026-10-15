<?php

namespace App\Http\Controllers\Admin\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\JenisUnit;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PegawaiController extends Controller
{
    /**
     * Folder tujuan penyimpanan foto peserta, relatif terhadap public_path().
     */
    private const FOTO_FOLDER = 'foto-pegawai';

    public function index(): View
    {
        $users = User::with('unit')
            ->whereIn('role', ['pegawai', 'admin_unit'])
            ->orderBy('nama')
            ->get();

        $pegawai = $users->values()->map(function (User $user, int $index) {
            return [
                'id'     => $user->id,
                'no'     => $index + 1,
                'nama'   => $user->nama,
                'nip'    => $user->nip,
                'unit'   => $user->unit->nama_unit ?? '-',
                'role'   => $user->role,
                'status' => $user->status,
            ];
        });

        return view('admin.data-master.pegawai.index', compact('pegawai'));
    }

    public function create(): View
    {
        $units = JenisUnit::orderBy('nama_unit')->get();

        return view('admin.data-master.pegawai.create', compact('units'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);

        $fotoName = null;
        if ($request->hasFile('foto')) {
            $fotoName = $this->simpanFoto($request);
        }

        User::create([
            'nama'                   => $validated['nama'],
            'nip'                    => $validated['nip'],
            'unit_id'                => $validated['unit_id'],
            'email'                  => $validated['email'],
            'password'               => $validated['password'],
            'role'                   => 'pegawai',
            'tempat_lahir'           => $validated['tempat_lahir'],
            'tanggal_lahir'          => $validated['tanggal_lahir'],
            'kewarganegaraan'        => $validated['kewarganegaraan'],
            'status_kewarganegaraan' => $validated['status_kewarganegaraan'],
            'no_hp'                  => $validated['no_hp'] ?? null,
            'alamat'                 => $validated['alamat'] ?? null,
            'foto'                   => $fotoName,
            'status'                 => 'aktif',
        ]);

        return redirect()
            ->route('admin.data-master.pegawai')
            ->with('success', 'Peserta berhasil ditambahkan.');
    }

    public function show(User $pegawai): View
    {
        $pegawai->load('unit');

        return view('admin.data-master.pegawai.show', compact('pegawai'));
    }

    public function edit(User $pegawai): View
    {
        $units = JenisUnit::orderBy('nama_unit')->get();

        return view('admin.data-master.pegawai.edit', compact('pegawai', 'units'));
    }

    public function update(Request $request, User $pegawai): RedirectResponse
    {
        $validated = $this->validateData($request, $pegawai->id);

        if ($request->hasFile('foto')) {
            // Hapus foto lama sebelum menyimpan yang baru.
            $this->hapusFoto($pegawai->foto);
            $validated['foto'] = $this->simpanFoto($request);
        } else {
            unset($validated['foto']);
        }

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $pegawai->update($validated);

        return redirect()
            ->route('admin.data-master.pegawai')
            ->with('success', 'Data peserta berhasil diperbarui.');
    }

    public function destroy(User $pegawai): RedirectResponse
    {
        $this->hapusFoto($pegawai->foto);

        $pegawai->delete();

        return redirect()
            ->route('admin.data-master.pegawai')
            ->with('success', 'Peserta berhasil dihapus.');
    }

    private function simpanFoto(Request $request): string
    {
        $file = $request->file('foto');

        $folder = public_path(self::FOTO_FOLDER);
        if (!File::isDirectory($folder)) {
            File::makeDirectory($folder, 0755, true);
        }

        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $file->move($folder, $fileName);

        return $fileName;
    }

    private function hapusFoto(?string $fileName): void
    {
        if (!$fileName) {
            return;
        }

        $path = public_path(self::FOTO_FOLDER . '/' . $fileName);

        if (File::exists($path)) {
            File::delete($path);
        }
    }

    /**
     * Validasi input form tambah/edit peserta.
     */
    private function validateData(Request $request, ?int $ignoreUserId = null): array
    {
        $isCreate = is_null($ignoreUserId);

        $validated = $request->validate([
            'nama'                   => ['required', 'string', 'max:255'],
            'nip'                    => ['required', 'string', 'max:50', 'unique:users,nip' . ($ignoreUserId ? ",{$ignoreUserId}" : '')],
            'unit_id'                => ['required', 'exists:jenis_unit,id'],
            'email'                  => ['required', 'email', 'max:255', 'unique:users,email' . ($ignoreUserId ? ",{$ignoreUserId}" : '')],
            'password'               => [$isCreate ? 'required' : 'nullable', 'string', 'min:8'],
            'tempat_lahir'           => ['required', 'string', 'max:255'],
            'tanggal_lahir'          => ['required', 'date'],
            'kewarganegaraan'        => ['required', 'string', 'max:255'],
            'status_kewarganegaraan' => ['required', 'in:WNI,WNA'],
            'no_hp'                  => ['nullable', 'string', 'max:20'],
            'alamat'                 => ['nullable', 'string'],
            'foto'                   => ['nullable', 'image', 'max:2048'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        return $validated;
    }
}