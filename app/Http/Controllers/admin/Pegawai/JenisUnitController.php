<?php

namespace App\Http\Controllers\Admin\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\JenisUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JenisUnitController extends Controller
{
    public function index(): View
    {
        $jenisUnit = JenisUnit::withCount('penempatanUnit')->orderBy('nama_unit')->get();

        $stats = [
            [
                'label'  => 'Total Jenis Unit',
                'value'  => $jenisUnit->count(),
                'suffix' => 'Jenis',
                'icon'   => 'building',
                'color'  => 'emerald',
            ],
            [
                'label'  => 'Total Unit Terdaftar',
                'value'  => $jenisUnit->sum('penempatan_unit_count'),
                'suffix' => 'Unit',
                'icon'   => 'users',
                'color'  => 'blue',
            ],
        ];

        $unit = $jenisUnit->values()->map(function (JenisUnit $item, int $index) {
            return [
                'id'        => $item->id,
                'no'        => $index + 1,
                'nama'      => $item->nama_unit,
                'deskripsi' => $item->deskripsi,
                'status'    => $item->status,
                'jumlah'    => $item->penempatan_unit_count,
            ];
        });

        return view('admin.data-master.unit.index', compact('unit', 'stats'));
    }

    /**
     * Store a newly created resource in storage.
     * Form "Tambah Unit" tidak punya field status, jadi status baru
     * otomatis di-set "Aktif" tanpa perlu dipilih user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_unit' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $validated['status'] = 'Aktif';

        JenisUnit::create($validated);

        return redirect()
            ->route('admin.data-master.unit')
            ->with('success', 'Jenis unit berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     * Form "Edit Unit" punya field status, jadi tetap divalidasi wajib diisi.
     */
    public function update(Request $request, JenisUnit $unit): RedirectResponse
    {
        $validated = $this->validateData($request);

        $unit->update($validated);

        return redirect()
            ->route('admin.data-master.unit')
            ->with('success', 'Jenis unit berhasil diperbarui.');
    }

    public function destroy(JenisUnit $unit): RedirectResponse
    {
        if ($unit->penempatanUnit()->exists()) {
            return redirect()
                ->route('admin.data-master.unit')
                ->with('error', 'Jenis unit tidak bisa dihapus karena masih digunakan oleh data penempatan unit lain.');
        }

        $unit->delete();

        return redirect()
            ->route('admin.data-master.unit')
            ->with('success', 'Jenis unit berhasil dihapus.');
    }

    /**
     * Validasi input dari form edit jenis unit (dikirim lewat modal).
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'nama_unit' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'status'    => ['required', 'in:Aktif,Nonaktif'],
        ]);
    }
}