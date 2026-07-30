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
        $jenisIzin = JenisIzin::withCount('pengajuanIzin')
            ->orderBy('nama_izin')
            ->get();

        $stats = [
            'total_jenis'     => $jenisIzin->count(),
            'total_pengajuan' => $jenisIzin->sum('pengajuan_izin_count'),
        ];

        return view('admin.data-master.jenis-izin.index', compact('jenisIzin', 'stats'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_izin'   => ['required', 'string', 'max:100', 'unique:jenis_izin,nama_izin'],
            'deskripsi'   => ['nullable', 'string', 'max:500'],
            'kuota'       => ['required', 'integer', 'min:0', 'max:365'],
            'status'      => ['required', 'boolean'],
            'kategori'    => ['required', Rule::in(['izin', 'sakit', 'cuti', 'izin_terlambat'])],
            'wajib_absen' => ['required', 'boolean'],
        ]);

        JenisIzin::create($validated);

        return redirect()
            ->route('admin.data-master.jenis-izin')
            ->with('success', 'Jenis izin berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        return redirect()->route('admin.data-master.jenis-izin');
    }

    public function edit(string $id)
    {
        $jenisIzin = JenisIzin::findOrFail($id);

        return response()->json($jenisIzin);
    }

    public function update(Request $request, string $id)
    {
        $jenisIzin = JenisIzin::findOrFail($id);

        $validated = $request->validate([
            'nama_izin' => [
                'required', 'string', 'max:100',
                Rule::unique('jenis_izin', 'nama_izin')->ignore($jenisIzin->id),
            ],
            'deskripsi'   => ['nullable', 'string', 'max:500'],
            'kuota'       => ['required', 'integer', 'min:0', 'max:365'],
            'status'      => ['required', 'boolean'],
            'kategori'    => ['required', Rule::in(['izin', 'sakit', 'cuti', 'izin_terlambat'])],
            'wajib_absen' => ['required', 'boolean'],
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