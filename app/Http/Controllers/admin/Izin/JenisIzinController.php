<?php

namespace App\Http\Controllers\Admin\Izin;

use App\Http\Controllers\Controller;
use App\Models\JenisIzin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JenisIzinController extends Controller
{
    public function index()
    {
        $jenisIzin = JenisIzin::orderBy('nama_izin')->get();

        return view('admin.data-master.jenis-izin.index', compact('jenisIzin'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_izin' => ['required', 'string', 'max:100', 'unique:jenis_izin,nama_izin'],
        ]);

        $validated['status'] = true;

        JenisIzin::create($validated);

        return redirect()
            ->route('admin.data-master.jenis-izin')
            ->with('success', 'Jenis izin berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $jenisIzin = JenisIzin::findOrFail($id);

        $validated = $request->validate([
            'nama_izin' => [
                'required', 'string', 'max:100',
                Rule::unique('jenis_izin', 'nama_izin')->ignore($jenisIzin->id),
            ],
        ]);

        $jenisIzin->update($validated);

        return redirect()
            ->route('admin.data-master.jenis-izin')
            ->with('success', 'Jenis izin berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $jenisIzin = JenisIzin::findOrFail($id);

        if ($jenisIzin->pengajuanIzin()->exists()) {
            return redirect()
                ->route('admin.data-master.jenis-izin')
                ->with('error', 'Jenis izin tidak bisa dihapus karena masih dipakai pada data pengajuan.');
        }

        $jenisIzin->delete();

        return redirect()
            ->route('admin.data-master.jenis-izin')
            ->with('success', 'Jenis izin berhasil dihapus.');
    }
}