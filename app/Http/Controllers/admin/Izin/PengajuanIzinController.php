<?php

namespace App\Http\Controllers\Admin\Izin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanIzin;
use App\Models\JenisIzin;
use App\Models\JenisUnit;
use Illuminate\Http\Request;

class PengajuanIzinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Halaman "Semua Pengajuan" untuk admin, lengkap dengan filter & paginasi.
     */
    public function semua(Request $request)
    {
        $query = PengajuanIzin::with(['user.unit', 'jenisIzin']);

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_pengajuan', $request->tanggal);
        }

        if ($request->filled('unit') && $request->unit !== 'Semua Unit') {
            $query->whereHas('user.unit', function ($q) use ($request) {
                $q->where('nama_unit', $request->unit);
            });
        }

        if ($request->filled('jenis') && $request->jenis !== 'Semua Jenis') {
            $query->whereHas('jenisIzin', function ($q) use ($request) {
                $q->where('nama_izin', $request->jenis);
            });
        }

        if ($request->filled('status') && $request->status !== 'Semua Status') {
            $query->where('status', $request->status);
        }

        $pengajuan = $query->latest('tanggal_pengajuan')
            ->paginate(10)
            ->withQueryString();

        $jenisIzinList = JenisIzin::pluck('nama_izin');
        $unitList = JenisUnit::pluck('nama_unit');

        return view('admin.izin.semua', compact('pengajuan', 'jenisIzinList', 'unitList'));
    }

    /**
     * Halaman "Menunggu Persetujuan".
     */
    public function menunggu(Request $request)
    {
        $base = PengajuanIzin::where('status', 'Menunggu');

        $totalMenunggu = (clone $base)->count();

        $totalCuti = (clone $base)->whereHas('jenisIzin', function ($q) {
            $q->where('nama_izin', 'Cuti Tahunan');
        })->count();

        $totalSakit = (clone $base)->whereHas('jenisIzin', function ($q) {
            $q->where('nama_izin', 'Sakit');
        })->count();

        $totalPribadi = (clone $base)->whereHas('jenisIzin', function ($q) {
            $q->where('nama_izin', 'Izin Pribadi');
        })->count();

        $pengajuan = PengajuanIzin::with(['user.unit', 'jenisIzin'])
            ->where('status', 'Menunggu')
            ->latest('tanggal_pengajuan')
            ->paginate(10)
            ->withQueryString();

        return view('admin.izin.menunggu', compact(
            'pengajuan',
            'totalMenunggu',
            'totalCuti',
            'totalSakit',
            'totalPribadi'
        ));
    }

    /**
     * Setujui pengajuan izin.
     */
    public function approve(Request $request, string $id)
    {
        $pengajuan = PengajuanIzin::findOrFail($id);

        $tanggalPersetujuan = $request->filled('tanggal_persetujuan')
            ? \Carbon\Carbon::parse($request->input('tanggal_persetujuan'))
            : now();

        $pengajuan->update([
            'status'              => 'disetujui',
            'disetujui_oleh'      => auth()->id(),
            'tanggal_persetujuan' => $tanggalPersetujuan,
        ]);

        return back()->with('success', 'Pengajuan izin berhasil disetujui.');
    }

    /**
     * Tolak pengajuan izin.
     */
    public function reject(Request $request, string $id)
    {
        $pengajuan = PengajuanIzin::findOrFail($id);

        $request->validate([
            'catatan' => ['required', 'string'],
        ]);

        $tanggalPersetujuan = $request->filled('tanggal_persetujuan')
            ? \Carbon\Carbon::parse($request->input('tanggal_persetujuan'))
            : now();

        $pengajuan->update([
            'status'              => 'ditolak',
            'disetujui_oleh'      => auth()->id(),
            'tanggal_persetujuan' => $tanggalPersetujuan,
            'catatan'             => $request->input('catatan'),
        ]);

        return back()->with('success', 'Pengajuan izin ditolak.');
    }

    /**
     * Show the form for creating a new resource.
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pengajuan = PengajuanIzin::with(['user.unit', 'jenisIzin'])
            ->findOrFail($id);

        return view('admin.izin.show', compact('pengajuan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}