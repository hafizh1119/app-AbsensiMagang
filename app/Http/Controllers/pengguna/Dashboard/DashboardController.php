<?php

namespace App\Http\Controllers\Pengguna\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\PengajuanIzin;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman home/dashboard pengguna.
     */
    public function index()
    {
        $user  = Auth::user();
        $today = now()->toDateString();

        // Absensi hari ini
        $absensiHariIni = Absensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        $sudahAbsenMasuk = (bool) ($absensiHariIni?->jam_masuk);
        $terlambat       = $absensiHariIni?->status === 'terlambat';

        // Durasi kerja (kalau sudah absen masuk & pulang)
        $durasi = null;
        if ($absensiHariIni?->jam_masuk && $absensiHariIni?->jam_pulang) {
            $masuk  = \Carbon\Carbon::parse($absensiHariIni->jam_masuk);
            $pulang = \Carbon\Carbon::parse($absensiHariIni->jam_pulang);
            $durasi = $masuk->diff($pulang)->format('%H:%I');
        }

        // Rekap bulan ini
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        $rekapBulanIni = Absensi::where('user_id', $user->id)
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->selectRaw("
                SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = 'terlambat' THEN 1 ELSE 0 END) as terlambat,
                SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = 'sakit' THEN 1 ELSE 0 END) as sakit
            ")
            ->first();

        // ================= BADGE STATUS "HARI INI" =================
        // Cuma untuk menentukan label badge di card "Hari ini". Tidak mengubah
        // logic/variabel absensi yang sudah ada di atas.
        //
        // Prioritas: absen tercatat (hadir/terlambat) > izin/cuti/sakit yang
        // SUDAH disetujui admin > belum ada aktivitas sama sekali. Pengajuan
        // yang masih 'pending' SENGAJA tidak mengubah badge ini.
        //
        // NOTE / ASUMSI: sesuaikan nama kolom tanggal (tanggal_mulai/tanggal_selesai
        // atau cukup 'tanggal') dan nilai status ('disetujui'/'diverifikasi') dengan
        // skema tabel pengajuan_izin kamu yang sebenarnya.
        $izinDisetujuiHariIni = PengajuanIzin::with('jenisIzin')
            ->where('user_id', $user->id)
            ->whereIn('status', ['disetujui', 'diverifikasi'])
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->latest()
            ->first();

        if ($sudahAbsenMasuk) {
            $statusBadge = $terlambat ? 'terlambat' : 'hadir';
            $labelBadge  = $terlambat ? 'Datang Terlambat' : 'Datang Tepat Waktu';
        } elseif ($izinDisetujuiHariIni) {
            $namaJenisIzin = $izinDisetujuiHariIni->jenisIzin->nama
                ?? $izinDisetujuiHariIni->jenis
                ?? 'Izin';

            // Samakan pola pengelompokan status dengan AbsensiController::labelStatus():
            // cuti dan sakit punya badge sendiri, selain itu masuk ke 'izin'.
            $statusBadge = match (strtolower($namaJenisIzin)) {
                'cuti'  => 'cuti',
                'sakit' => 'sakit',
                default => 'izin',
            };
            $labelBadge  = $namaJenisIzin;
        } else {
            $statusBadge = 'belum_absen';
            $labelBadge  = 'Belum Absen';
        }

        return view('pengguna.home.index', [
            'user'            => $user,
            'sudahAbsenMasuk' => $sudahAbsenMasuk,
            'terlambat'       => $terlambat,
            'absensiHariIni'  => $absensiHariIni,
            'durasi'          => $durasi,
            'rekapBulanIni'   => $rekapBulanIni,
            'statusBadge'     => $statusBadge,
            'labelBadge'      => $labelBadge,
        ]);
    }
}