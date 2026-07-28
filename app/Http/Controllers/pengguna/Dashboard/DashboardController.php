<?php

namespace App\Http\Controllers\Pengguna\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
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

        return view('pengguna.home.index', [
            'user'            => $user,
            'sudahAbsenMasuk' => $sudahAbsenMasuk,
            'terlambat'       => $terlambat,
            'absensiHariIni'  => $absensiHariIni,
            'durasi'          => $durasi,
            'rekapBulanIni'   => $rekapBulanIni,
        ]);
    }
}