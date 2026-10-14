<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\JamKerja;
use App\Models\PengajuanIzin;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * NOTE / ASUMSI:
     * Kolom `absensi.status` diasumsikan berisi salah satu dari:
     *   'hadir', 'terlambat', 'izin', 'sakit', 'alpha'
     * Sesuaikan nilai-nilai di bawah ini (STATUS_*) kalau enum di database
     * kamu berbeda, tidak perlu ubah logic lainnya.
     */
    private const STATUS_HADIR     = 'hadir';
    private const STATUS_TERLAMBAT = 'terlambat';
    private const STATUS_IZIN      = 'izin';
    private const STATUS_SAKIT     = 'sakit';
    private const STATUS_ALPHA     = 'alpha';

    public function index()
    {
        Carbon::setLocale('id');

        $today      = Carbon::today();
        $startWeek  = $today->copy()->subDays(6); // total 7 kolom termasuk besok
        $tahunAktif = $today->year;

        // ================= TOTAL PEGAWAI =================
        $totalPegawai = User::whereIn('role', ['pegawai', 'admin_unit'])
            ->where('status', 'aktif')
            ->count();

        $pegawaiBaruBulanIni = User::whereIn('role', ['pegawai', 'admin_unit'])
            ->where('status', 'aktif')
            ->whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->count();

        // ================= REKAP ABSENSI HARI INI =================
        $absensiToday = Absensi::whereDate('tanggal', $today)->get();

        $hadirHariIni     = $absensiToday->where('status', self::STATUS_HADIR)->count();
        $terlambatHariIni = $absensiToday->where('status', self::STATUS_TERLAMBAT)->count();
        $izinHariIni      = $absensiToday->where('status', self::STATUS_IZIN)->count();
        $sakitHariIni     = $absensiToday->where('status', self::STATUS_SAKIT)->count();
        $alphaHariIni     = $absensiToday->where('status', self::STATUS_ALPHA)->count();

        // "Hadir" pada card & donut menggabungkan hadir tepat waktu + terlambat
        $hadirTotal       = $hadirHariIni + $terlambatHariIni;
        $izinSakitHariIni = $izinHariIni + $sakitHariIni;

        $persen = fn (int $jumlah) => $totalPegawai > 0
            ? round(($jumlah / $totalPegawai) * 100, 1)
            : 0;

        $persenHadir     = $persen($hadirTotal);
        $persenTerlambat = $persen($terlambatHariIni);
        $persenIzinSakit = $persen($izinSakitHariIni);
        $persenIzin      = $persen($izinHariIni);
        $persenSakit     = $persen($sakitHariIni);
        $persenAlpha     = $persen($alphaHariIni);

        // ================= GRAFIK: KEHADIRAN 7 HARI TERAKHIR =================
        $rekapMingguan = Absensi::selectRaw('tanggal, COUNT(*) as total')
            ->whereBetween('tanggal', [$startWeek, $today])
            ->whereIn('status', [self::STATUS_HADIR, self::STATUS_TERLAMBAT])
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $kehadiranLabels = [];
        $kehadiranData   = [];
        for ($i = 0; $i < 7; $i++) {
            $tanggal = $startWeek->copy()->addDays($i);
            $isBesok = $tanggal->isAfter($today);

            $kehadiranLabels[] = ($isBesok ? 'Besok ' : ($tanggal->isToday() ? 'Hari ini' : $tanggal->translatedFormat('D'))) . ' ' . $tanggal->format('d/m');
            $kehadiranData[]   = $isBesok ? null : (int) ($rekapMingguan[$tanggal->toDateString()] ?? 0);
        }

        // ================= TABEL: ABSENSI HARI INI =================
        $absensiHariIni = Absensi::with(['user.unit'])
            ->whereDate('tanggal', $today)
            ->orderByDesc('jam_masuk')
            ->limit(5)
            ->get();

        // ================= JAM KERJA HARI INI =================
        $namaHariIni = $today->translatedFormat('l'); // Senin, Selasa, dst
        $jamKerja = JamKerja::where('hari', $namaHariIni)
        ->whereRaw('status = true')
        ->first();

        // ================= PENGAJUAN IZIN MENUNGGU PERSETUJUAN =================
        $pengajuanPending = PengajuanIzin::where('status', 'pending')->count();

        // ================= TOP 5 PEGAWAI TERAJIN BULAN INI =================
        // Diukur dari jumlah hadir tepat waktu (bukan terlambat) terbanyak bulan ini
        $topRajin = Absensi::with(['user.unit'])
            ->selectRaw('user_id, COUNT(*) as total')
            ->where('status', self::STATUS_HADIR)
            ->whereMonth('tanggal', $today->month)
            ->whereYear('tanggal', $today->year)
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // ================= TOP 5 PEGAWAI TERLAMBAT BULAN INI =================
        $topTerlambat = Absensi::with(['user.unit'])
            ->selectRaw('user_id, COUNT(*) as total')
            ->where('status', self::STATUS_TERLAMBAT)
            ->whereMonth('tanggal', $today->month)
            ->whereYear('tanggal', $today->year)
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalPegawai',
            'pegawaiBaruBulanIni',
            'hadirHariIni',
            'terlambatHariIni',
            'izinHariIni',
            'sakitHariIni',
            'alphaHariIni',
            'izinSakitHariIni',
            'persenHadir',
            'persenTerlambat',
            'persenIzinSakit',
            'persenIzin',
            'persenSakit',
            'persenAlpha',
            'kehadiranLabels',
            'kehadiranData',
            'absensiHariIni',
            'jamKerja',
            'pengajuanPending',
            'tahunAktif',
            'topRajin',
            'topTerlambat'
        ));
    }
}