<?php

namespace App\Http\Controllers\Admin\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\JenisUnit;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
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
                'id'             => $user->id,
                'no'             => $index + 1,
                'nama'           => $user->nama,
                'status_peserta' => $user->status_peserta ? ucfirst($user->status_peserta) : '-',
                'asal_instansi'  => $user->asal_instansi ?? '-',
                'unit'           => $user->unit->nama_unit ?? '-',
                'role'           => $user->role,
                'status'         => $user->status,
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
            // A. Identitas diri
            'nama'           => $validated['nama'],
            'status_peserta' => $validated['status_peserta'],
            'jenis_kelamin'  => $validated['jenis_kelamin'],
            'alamat'         => $validated['alamat'],
            'no_hp'          => $validated['no_hp'],
            'email'          => $validated['email'],
            'password'       => $validated['password'],

            // B. Asal pendidikan
            'asal_instansi'  => $validated['asal_instansi'],
            'jurusan'        => $validated['jurusan'],
            'kelas_semester' => $validated['kelas_semester'],

            // C. Penempatan magang
            'unit_id'                => $validated['unit_id'],
            'tanggal_mulai_magang'   => $validated['tanggal_mulai_magang'],
            'tanggal_selesai_magang' => $validated['tanggal_selesai_magang'],

            // Lainnya
            'role'   => 'pegawai',
            'foto'   => $fotoName,
            'status' => 'aktif',
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

        // Password kosong = tidak diganti.
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
        if (Absensi::where('user_id', $pegawai->id)->exists()) {
            return redirect()
                ->route('admin.data-master.pegawai')
                ->with('error', 'Peserta tidak bisa dihapus karena sudah memiliki data absensi.');
        }

        $pegawai->delete();
        $this->hapusFoto($pegawai->foto);

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
            // A. Identitas diri
            'nama'           => ['required', 'string', 'max:255'],
            'status_peserta' => ['required', Rule::in(['mahasiswa', 'siswa'])],
            'jenis_kelamin'  => ['required', Rule::in(['L', 'P'])],
            'alamat'         => ['required', 'string'],
            'no_hp'          => ['required', 'string', 'max:20'],
            'email'          => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($ignoreUserId),
            ],
            'password'       => [$isCreate ? 'required' : 'nullable', 'string', 'min:8'],

            // B. Asal pendidikan
            'asal_instansi'  => ['required', 'string', 'max:255'],
            'jurusan'        => ['required', 'string', 'max:255'],
            'kelas_semester' => ['required', 'string', 'max:20'],

            // C. Penempatan magang
            'unit_id'                => ['required', 'exists:jenis_unit,id'],
            'tanggal_mulai_magang'   => ['required', 'date'],
            'tanggal_selesai_magang' => ['required', 'date', 'after_or_equal:tanggal_mulai_magang'],

            'foto'           => ['nullable', 'image', 'max:2048'],
        ], [
            'tanggal_selesai_magang.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        return $validated;
    }
}