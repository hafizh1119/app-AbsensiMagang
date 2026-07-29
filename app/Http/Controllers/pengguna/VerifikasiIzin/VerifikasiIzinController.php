<?php

namespace App\Http\Controllers\Pengguna\VerifikasiIzin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanIzin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifikasiIzinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activeTab = $request->status ?? 'semua';

        $query = PengajuanIzin::with(['user', 'jenisIzin'])
            ->orderByRaw("CASE WHEN status = 'menunggu' THEN 0 ELSE 1 END")
            ->orderByDesc('tanggal_pengajuan');

        if ($activeTab !== 'semua') {
            $query->where('status', $activeTab);
        }

        $pengajuanList = $query->paginate(10);

        return view('pengguna.verifikasi.index', compact('pengajuanList', 'activeTab'));
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
        $pengajuan = PengajuanIzin::with(['user.unit', 'jenisIzin', 'penyetuju'])
            ->findOrFail($id);

        return view('pengguna.verifikasi.show', compact('pengajuan'));
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

    /**
     * Setujui pengajuan izin.
     */
    public function approve(Request $request, string $id)
    {
        $request->validate([
            'tanggal_persetujuan' => 'required|date',
        ]);

        $pengajuan = PengajuanIzin::findOrFail($id);

        $pengajuan->update([
            'status'              => 'disetujui',
            'disetujui_oleh'      => Auth::id(),
            'tanggal_persetujuan' => $request->tanggal_persetujuan,
        ]);

        return redirect()
            ->route('verifikasi.index')
            ->with('success', 'Pengajuan izin berhasil disetujui.');
    }

    /**
     * Tolak pengajuan izin.
     */
    public function reject(Request $request, string $id)
    {
        $request->validate([
            'tanggal_persetujuan' => 'required|date',
            'catatan'             => 'required|string|max:500',
        ]);

        $pengajuan = PengajuanIzin::findOrFail($id);

        $pengajuan->update([
            'status'              => 'ditolak',
            'disetujui_oleh'      => Auth::id(),
            'tanggal_persetujuan' => $request->tanggal_persetujuan,
            'catatan'             => $request->catatan,
        ]);

        return redirect()
            ->route('verifikasi.index')
            ->with('success', 'Pengajuan izin berhasil ditolak.');
    }
}