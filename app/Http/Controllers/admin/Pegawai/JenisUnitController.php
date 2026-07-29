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
        $jenisUnit = JenisUnit::orderBy('nama_unit')->get();

        $stats = [
            [
                'label'  => 'Total Jenis Unit',
                'value'  => $jenisUnit->count(),
                'suffix' => 'Jenis',
                'icon'   => 'building',
                'color'  => 'emerald',
            ],
        ];

        $unit = $jenisUnit->values()->map(function (JenisUnit $item, int $index) {
            return [
                'id'        => $item->id,
                'no'        => $index + 1,
                'nama'      => $item->nama_unit,
                'deskripsi' => $item->deskripsi,
                'status'    => $item->status,
            ];
        });

        return view('admin.data-master.unit.index', compact('unit', 'stats'));
    }

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
        $unit->delete();

        return redirect()
            ->route('admin.data-master.unit')
            ->with('success', 'Jenis unit berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nama_unit' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'status'    => ['required', 'in:Aktif,Nonaktif'],
        ]);
    }
}