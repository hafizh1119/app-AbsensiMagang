<?php

namespace App\Http\Controllers\Admin\Izin;

use App\Http\Controllers\Controller;
use App\Models\JenisIzin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JenisIzinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

    /**
     * Show the form for creating a new resource.
     *
     * Form pembuatan dibuka lewat modal di halaman index,
     * jadi method ini tidak perlu mengembalikan view terpisah.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_izin' => ['required', 'string', 'max:100', 'unique:jenis_izin,nama_izin'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
            'kuota'     => ['required', 'integer', 'min:0', 'max:365'],
            'status'    => ['required', 'boolean'],
        ]);

        JenisIzin::create($validated);

        return redirect()
            ->route('admin.data-master.jenis-izin')
            ->with('success', 'Jenis izin berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.data-master.jenis-izin');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * Form edit ditampilkan lewat modal yang datanya sudah
     * dirender langsung dari index (lihat blade-nya), jadi
     * endpoint ini opsional saja untuk kebutuhan lain (mis. API/AJAX).
     */
    public function edit(string $id)
    {
        $jenisIzin = JenisIzin::findOrFail($id);

        return response()->json($jenisIzin);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $jenisIzin = JenisIzin::findOrFail($id);

        $validated = $request->validate([
            'nama_izin' => [
                'required', 'string', 'max:100',
                Rule::unique('jenis_izin', 'nama_izin')->ignore($jenisIzin->id),
            ],
            'deskripsi' => ['nullable', 'string', 'max:500'],
            'kuota'     => ['required', 'integer', 'min:0', 'max:365'],
            'status'    => ['required', 'boolean'],
        ]);

        $jenisIzin->update($validated);

        return redirect()
            ->route('admin.data-master.jenis-izin')
            ->with('success', 'Jenis izin berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jenisIzin = JenisIzin::findOrFail($id);

        // Cegah hapus jenis izin yang masih dipakai di pengajuan,
        // supaya data pengajuan lama tidak kehilangan relasinya.
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