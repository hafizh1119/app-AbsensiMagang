<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\PengajuanIzin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard pembimbing.
     */
    public function index()
    {
        $user  = Auth::user();
        $today = now()->toDateString();

        // Peserta = pegawai aktif yang ditempatkan di ruangan pembimbing ini
        $pesertaIds = User::where('role', 'pegawai')
            ->where('unit_id', $user->unit_id)
            ->where('status', 'aktif')
            ->pluck('id');

        // Rekap kehadiran peserta hari ini
        $rekap = Absensi::whereIn('user_id', $pesertaIds)
            ->where('tanggal', $today)
            ->selectRaw("
                COALESCE(SUM(CASE WHEN status IN ('hadir', 'terlambat') THEN 1 ELSE 0 END), 0) as hadir,
                COALESCE(SUM(CASE WHEN status IN ('izin', 'cuti') THEN 1 ELSE 0 END), 0) as izin,
                COALESCE(SUM(CASE WHEN status = 'sakit' THEN 1 ELSE 0 END), 0) as sakit
            ")
            ->first();

        $ringkasan = [
            'total' => $pesertaIds->count(),
            'hadir' => (int) $rekap->hadir,
            'izin'  => (int) $rekap->izin,
            'sakit' => (int) $rekap->sakit,
        ];

        // Jumlah pengajuan yang menunggu verifikasi (untuk badge merah)
        $jumlahMenunggu = PengajuanIzin::whereIn('user_id', $pesertaIds)
            ->where('status', 'menunggu')
            ->count();

        // Pengajuan izin terbaru
        $pengajuanTerbaru = PengajuanIzin::with(['user', 'jenisIzin'])
            ->whereIn('user_id', $pesertaIds)
            ->latest('tanggal_pengajuan')
            ->take(3)
            ->get();

        return view('pembimbing.dashboard', [
            'user'             => $user,
            'ringkasan'        => $ringkasan,
            'jumlahMenunggu'   => $jumlahMenunggu,
            'pengajuanTerbaru' => $pengajuanTerbaru,
        ]);
    }
}