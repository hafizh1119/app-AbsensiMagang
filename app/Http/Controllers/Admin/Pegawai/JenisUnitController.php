<?php

namespace App\Http\Controllers\Admin\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\JenisUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Models\User;

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

        $unit = $jenisUnit->values()->map(fn (JenisUnit $item, int $index) => [
            'id'   => $item->id,
            'no'   => $index + 1,
            'nama' => $item->nama_unit,
        ]);

        return view('admin.data-master.unit.index', compact('unit', 'stats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_unit' => ['required', 'string', 'max:255', 'unique:jenis_unit,nama_unit'],
        ]);

        JenisUnit::create($validated);

        return redirect()
            ->route('admin.data-master.unit')
            ->with('success', 'Jenis unit berhasil ditambahkan.');
    }

    public function update(Request $request, JenisUnit $unit): RedirectResponse
    {
        $validated = $request->validate([
            'nama_unit' => [
                'required', 'string', 'max:255',
                Rule::unique('jenis_unit', 'nama_unit')->ignore($unit->id),
            ],
        ]);

        $unit->update($validated);

        return redirect()
            ->route('admin.data-master.unit')
            ->with('success', 'Jenis unit berhasil diperbarui.');
    }

    public function destroy(JenisUnit $unit): RedirectResponse
    {
        if (User::where('unit_id', $unit->id)->exists()) {
            return redirect()
                ->route('admin.data-master.unit')
                ->with('error', 'Unit tidak bisa dihapus karena masih dipakai peserta.');
        }

        $unit->delete();

        return redirect()
            ->route('admin.data-master.unit')
            ->with('success', 'Jenis unit berhasil dihapus.');
    }
}