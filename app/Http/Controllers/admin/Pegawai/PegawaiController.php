<?php

namespace App\Http\Controllers\Admin\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\JenisUnit;
use App\Models\PenempatanUnit;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PegawaiController extends Controller
{
    /**
     * Folder tujuan penyimpanan foto pegawai, relatif terhadap public_path().
     */
    private const FOTO_FOLDER = 'foto-pegawai';

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = User::with(['penempatanUnit.jenisUnit'])
            ->where('role', 'pegawai')
            ->orderBy('nama')
            ->get();

        $pegawai = $users->values()->map(function (User $user, int $index) {
            $unit = $user->penempatanUnit->first()?->jenisUnit;

            return [
                'id'      => $user->id,
                'no'      => $index + 1,
                'nama'    => $user->nama,
                'nip'     => $user->nip,
                'unit'    => $unit->nama_unit ?? '-',
                'jabatan' => $user->jabatan,
                'status'  => $user->status,
            ];
        });

        $units = JenisUnit::orderBy('nama_unit')->get();

        return view('admin.data-master.pegawai.index', compact('pegawai', 'units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $units = JenisUnit::orderBy('nama_unit')->get();

        return view('admin.data-master.pegawai.create', compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);

        $fotoName = null;
        if ($request->hasFile('foto')) {
            $fotoName = $this->simpanFoto($request);
        }

        $user = User::create([
            'nama'                   => $validated['nama'],
            'nip'                    => $validated['nip'],
            'email'                  => $validated['email'],
            'password'               => $validated['password'],
            'role'                   => 'pegawai',
            'jabatan'                => $validated['jabatan'],
            'tempat_lahir'           => $validated['tempat_lahir'],
            'tanggal_lahir'          => $validated['tanggal_lahir'],
            'kewarganegaraan'        => $validated['kewarganegaraan'],
            'status_kewarganegaraan' => $validated['status_kewarganegaraan'],
            'no_hp'                  => $validated['no_hp'] ?? null,
            'alamat'                 => $validated['alamat'] ?? null,
            'foto'                   => $fotoName,
            'status'                 => 'aktif',
        ]);

        if (!empty($validated['unit_id'])) {
            PenempatanUnit::create([
                'user_id'       => $user->id,
                'jenis_unit_id' => $validated['unit_id'],
            ]);
        }

        return redirect()
            ->route('admin.data-master.pegawai')
            ->with('success', 'Pegawai berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $pegawai): View
    {
        $pegawai->load('penempatanUnit.jenisUnit');

        return view('admin.data-master.pegawai.show', compact('pegawai'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $pegawai): View
    {
        $units = JenisUnit::orderBy('nama_unit')->get();
        $pegawai->load('penempatanUnit');

        return view('admin.data-master.pegawai.edit', compact('pegawai', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $pegawai): RedirectResponse
    {
        $validated = $this->validateData($request, $pegawai->id);

        if ($request->hasFile('foto')) {
            // Hapus foto lama sebelum menyimpan yang baru.
            $this->hapusFoto($pegawai->foto);
            $validated['foto'] = $this->simpanFoto($request);
        }

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $unitId = $validated['unit_id'] ?? null;
        unset($validated['unit_id']);

        $pegawai->update($validated);

        // Perbarui penempatan unit: hapus penempatan lama, buat yang baru jika ada.
        $pegawai->penempatanUnit()->delete();
        if ($unitId) {
            PenempatanUnit::create([
                'user_id'       => $pegawai->id,
                'jenis_unit_id' => $unitId,
            ]);
        }

        return redirect()
            ->route('admin.data-master.pegawai')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $pegawai): RedirectResponse
    {
        $this->hapusFoto($pegawai->foto);

        $pegawai->penempatanUnit()->delete();
        $pegawai->delete();

        return redirect()
            ->route('admin.data-master.pegawai')
            ->with('success', 'Pegawai berhasil dihapus.');
    }

    /**
     * Simpan file foto yang diunggah langsung ke folder public/foto-pegawai
     * dan kembalikan nama file yang tersimpan.
     */
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

    /**
     * Hapus file foto lama dari folder public/foto-pegawai jika ada.
     */
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
     * Validasi input form tambah/edit pegawai.
     */
    private function validateData(Request $request, ?int $ignoreUserId = null): array
    {
        $isCreate = is_null($ignoreUserId);

        $validated = $request->validate([
            'nama'                    => ['required', 'string', 'max:255'],
            'nip'                     => ['required', 'string', 'max:50', 'unique:users,nip' . ($ignoreUserId ? ",{$ignoreUserId}" : '')],
            'unit_id'                 => ['nullable', 'exists:jenis_unit,id'],
            'jabatan'                 => ['required', 'string', 'max:255'],
            'email'                   => ['required', 'email', 'max:255', 'unique:users,email' . ($ignoreUserId ? ",{$ignoreUserId}" : '')],
            'password'                => [$isCreate ? 'required' : 'nullable', 'string', 'min:8'],
            'tempat_lahir'            => ['required', 'string', 'max:255'],
            'tanggal_lahir'           => ['required', 'date'],
            'kewarganegaraan'         => ['required', 'string', 'max:255'],
            'status_kewarganegaraan'  => ['required', 'in:WNI,WNA'],
            'no_hp'                   => ['nullable', 'string', 'max:20'],
            'alamat'                  => ['nullable', 'string'],
            'foto'                    => ['nullable', 'image', 'max:2048'],
        ]);

        if (!$isCreate && !empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } elseif ($isCreate) {
            $validated['password'] = Hash::make($validated['password']);
        }

        return $validated;
    }
}