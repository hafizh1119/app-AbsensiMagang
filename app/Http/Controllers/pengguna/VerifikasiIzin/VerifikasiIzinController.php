<?php

namespace App\Http\Controllers\Pengguna\VerifikasiIzin;

use App\Http\Controllers\Controller;
use App\Models\Absensi; 
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

        $pengajuan = PengajuanIzin::with('jenisIzin')->findOrFail($id);

        $pengajuan->update([
            'status'              => 'disetujui',
            'disetujui_oleh'      => Auth::id(),
            'tanggal_persetujuan' => $request->tanggal_persetujuan,
        ]);

        $this->terapkanKeAbsensi($pengajuan);

        return redirect()
            ->route('pengguna.verifikasi.index')
            ->with('success', 'Pengajuan izin berhasil disetujui.');
    }

    /**
     * Terapkan hasil persetujuan izin ke tabel absensi untuk setiap tanggal
     * dalam rentang tanggal_mulai - tanggal_selesai.
     */
    private function terapkanKeAbsensi(PengajuanIzin $pengajuan): void
    {
        $jenisIzin = $pengajuan->jenisIzin;

        $tanggal = $pengajuan->tanggal_mulai->copy();
        $akhir   = $pengajuan->tanggal_selesai->copy();

        while ($tanggal->lte($akhir)) {
            $absensi = Absensi::firstOrNew([
                'user_id' => $pengajuan->user_id,
                'tanggal' => $tanggal->toDateString(),
            ]);

            $absensi->status            = $jenisIzin->kategori; // 'izin_terlambat', 'cuti', 'sakit', 'izin'
            $absensi->pengajuan_izin_id = $pengajuan->id;
            $absensi->keterangan        = $jenisIzin->nama_izin . ($pengajuan->alasan ? ' - ' . $pengajuan->alasan : '');

            if (! $jenisIzin->wajib_absen) {
                // Cuti/sakit: tidak perlu absen fisik, kosongkan jam supaya tidak ketuker data lama
                $absensi->jam_masuk  = null;
                $absensi->jam_pulang = null;
            }

            if (! $absensi->exists) {
                $absensi->lokasi_absensi_id = null; // belum ada lokasi karena belum absen fisik
            }

            $absensi->save();

            $tanggal->addDay();
        }
    }
}