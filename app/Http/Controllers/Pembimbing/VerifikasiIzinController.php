<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\PengajuanIzin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VerifikasiIzinController extends Controller
{
    /**
     * Query dasar: pengajuan izin dari peserta (role pegawai)
     * di ruangan pembimbing yang login.
     */
    private function queryPengajuan()
    {
        $unitId = Auth::user()->unit_id;

        return PengajuanIzin::whereHas('user', function ($q) use ($unitId) {
            $q->where('role', 'pegawai')
              ->where('unit_id', $unitId);
        });
    }

    /**
     * Daftar pengajuan izin (tab Semua / Menunggu / Disetujui / Ditolak).
     */
    public function index(Request $request)
    {
        $activeTab = in_array($request->status, ['menunggu', 'disetujui', 'ditolak'])
            ? $request->status
            : 'semua';

        $query = $this->queryPengajuan()
            ->with(['user', 'jenisIzin'])
            ->orderByRaw("CASE WHEN status = 'menunggu' THEN 0 ELSE 1 END")
            ->orderByDesc('tanggal_pengajuan');

        if ($activeTab !== 'semua') {
            $query->where('status', $activeTab);
        }

        $pengajuanList = $query->paginate(10);

        return view('pembimbing.verifikasi_izin.index', compact('pengajuanList', 'activeTab'));
    }

    /**
     * Detail satu pengajuan izin.
     */
    public function show(string $id)
    {
        // Pengajuan peserta ruangan lain => 404
        $pengajuan = $this->queryPengajuan()
            ->with(['user.unit', 'jenisIzin', 'penyetuju'])
            ->findOrFail($id);

        return view('pembimbing.verifikasi_izin.detail', compact('pengajuan'));
    }

    /**
     * Setujui pengajuan izin.
     */
    public function approve(Request $request, string $id)
    {
        $request->validate([
            'tanggal_persetujuan' => 'required|date',
        ]);

        $pengajuan = $this->queryPengajuan()
            ->with('jenisIzin')
            ->findOrFail($id);

        if ($pengajuan->status !== 'menunggu') {
            return redirect()
                ->route('pembimbing.verifikasi-izin.detail', $pengajuan->id)
                ->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($pengajuan, $request) {
            $pengajuan->update([
                'status'              => 'disetujui',
                'disetujui_oleh'      => Auth::id(),
                'tanggal_persetujuan' => $request->tanggal_persetujuan,
            ]);

            $this->terapkanKeAbsensi($pengajuan);
        });

        return redirect()
            ->route('pembimbing.verifikasi-izin.index')
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
        ], [
            'catatan.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $pengajuan = $this->queryPengajuan()->findOrFail($id);

        if ($pengajuan->status !== 'menunggu') {
            return redirect()
                ->route('pembimbing.verifikasi-izin.detail', $pengajuan->id)
                ->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $pengajuan->update([
            'status'              => 'ditolak',
            'disetujui_oleh'      => Auth::id(),
            'tanggal_persetujuan' => $request->tanggal_persetujuan,
            'catatan'             => $request->catatan,
        ]);

        return redirect()
            ->route('pembimbing.verifikasi-izin.index')
            ->with('success', 'Pengajuan izin berhasil ditolak.');
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

            $absensi->status            = $jenisIzin->kategori ?? 'izin'; // 'izin_terlambat', 'cuti', 'sakit', 'izin'
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