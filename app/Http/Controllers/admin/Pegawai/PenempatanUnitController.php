<?php

namespace App\Http\Controllers\Admin\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\JenisUnit;
use App\Models\PenempatanUnit;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenempatanUnitController extends Controller
{
    /**
     * Daftar semua unit beserta jumlah pegawai di masing-masing unit.
     */
    public function index(): View
    {
        $jenisUnit = JenisUnit::withCount('penempatanUnit')->orderBy('nama_unit')->get();

        $stats = [
            [
                'label'  => 'Total Unit',
                'value'  => $jenisUnit->count(),
                'suffix' => 'Unit',
                'icon'   => 'building',
                'color'  => 'emerald',
            ],
            [
                'label'  => 'Total Pegawai',
                'value'  => $jenisUnit->sum('penempatan_unit_count'),
                'suffix' => 'Pegawai',
                'icon'   => 'users',
                'color'  => 'blue',
            ],
        ];

        $unit = $jenisUnit->values()->map(function (JenisUnit $item, int $index) {
            return [
                'id'     => $item->id,
                'no'     => $index + 1,
                'nama'   => $item->nama_unit,
                'jenis'  => $item->deskripsi,
                'jumlah' => $item->penempatan_unit_count,
            ];
        });

        return view('admin.unit.index', compact('unit', 'stats'));
    }

    /**
     * Detail satu unit: daftar pegawai yang ditempatkan di unit ini.
     *
     * NOTE: sesuaikan nama kolom (nip, jabatan, status) berikut
     * dengan skema tabel `users` yang sebenarnya di project kamu.
     */
    public function show(JenisUnit $unit): View
    {
        $penempatan = PenempatanUnit::with('user')
            ->where('jenis_unit_id', $unit->id)
            ->get();

        $pegawai = $penempatan->values()->map(function (PenempatanUnit $item, int $index) {
            return [
                'penempatan_id'     => $item->id,
                'no'                => $index + 1,
                'nama'              => $item->user->nama ?? '-',
                'nip'               => $item->user->nip ?? '-',
                'jabatan'           => $item->user->jabatan ?? '-',
                'status'            => $item->user->status ?? 'Aktif',
                'tanggal_bergabung' => optional($item->created_at)->translatedFormat('d F Y') ?? '-',
            ];
        });

        // Pegawai yang belum ditempatkan di unit ini, untuk modal "Tambah Anggota".
        $pegawaiTersedia = User::whereDoesntHave('penempatanUnit', function ($query) use ($unit) {
            $query->where('jenis_unit_id', $unit->id);
        })->get();

        return view('admin.unit.show', [
            'unit'            => ['id' => $unit->id, 'nama' => $unit->nama_unit],
            'pegawai'         => $pegawai,
            'pegawaiTersedia' => $pegawaiTersedia,
        ]);
    }

    /**
     * Tambahkan satu atau beberapa pegawai ke unit ini.
     */
    public function store(Request $request, JenisUnit $unit): RedirectResponse
    {
        $validated = $request->validate([
            'user_id'   => ['required', 'array', 'min:1'],
            'user_id.*' => ['exists:users,id'],
        ]);

        foreach ($validated['user_id'] as $userId) {
            PenempatanUnit::firstOrCreate([
                'user_id'       => $userId,
                'jenis_unit_id' => $unit->id,
            ]);
        }

        return redirect()
            ->route('admin.unit.show', $unit->id)
            ->with('success', 'Pegawai berhasil ditambahkan ke unit.');
    }

    /**
     * Keluarkan satu pegawai dari unit (hapus baris penempatan, bukan hapus user).
     */
    public function destroy(PenempatanUnit $penempatan): RedirectResponse
    {
        $unitId = $penempatan->jenis_unit_id;
        $penempatan->delete();

        return redirect()
            ->route('admin.unit.show', $unitId)
            ->with('success', 'Pegawai berhasil dikeluarkan dari unit.');
    }
}