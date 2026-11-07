<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesertaController extends Controller
{
    /**
     * Query dasar: peserta (role pegawai) di ruangan pembimbing yang login.
     */
    private function queryPeserta()
    {
        return User::where('role', 'pegawai')
            ->where('unit_id', Auth::user()->unit_id);
    }

    /**
     * Peserta aktif = belum lewat tanggal selesai magang.
     */
    private function scopeAktif($query, string $today)
    {
        return $query->where(function ($q) use ($today) {
            $q->whereNull('tanggal_selesai_magang')
              ->orWhereDate('tanggal_selesai_magang', '>=', $today);
        });
    }

    /**
     * Daftar peserta (tab Aktif / Selesai + pencarian nama).
     */
    public function index(Request $request)
    {
        $today     = now()->toDateString();
        $activeTab = $request->query('status', 'aktif') === 'selesai' ? 'selesai' : 'aktif';
        $keyword   = trim((string) $request->query('q', ''));

        // Jumlah di badge tab (tidak terpengaruh pencarian)
        $jumlahAktif   = $this->scopeAktif($this->queryPeserta(), $today)->count();
        $jumlahSelesai = $this->queryPeserta()
            ->whereDate('tanggal_selesai_magang', '<', $today)
            ->count();

        // Daftar sesuai tab
        $query = $this->queryPeserta();

        if ($activeTab === 'aktif') {
            $this->scopeAktif($query, $today);
        } else {
            $query->whereDate('tanggal_selesai_magang', '<', $today);
        }

        if ($keyword !== '') {
            $query->where('nama', 'ilike', "%{$keyword}%");
        }

        $pesertaList = $query->orderBy('nama')->get();

        return view('pembimbing.peserta.index', compact(
            'pesertaList',
            'activeTab',
            'keyword',
            'jumlahAktif',
            'jumlahSelesai'
        ));
    }

    /**
     * Detail peserta: ringkasan + riwayat presensi per bulan.
     */
    public function show(Request $request, $id)
    {
        Carbon::setLocale('id');

        // findOrFail di query yang sudah dibatasi ruangan => peserta ruangan lain = 404
        $peserta = $this->queryPeserta()->with('unit')->findOrFail($id);

        $today = now()->toDateString();

        $sudahSelesai = $peserta->tanggal_selesai_magang
            && Carbon::parse($peserta->tanggal_selesai_magang)->lt(Carbon::parse($today));

        // Ringkasan seluruh masa magang
        $rekap = Absensi::where('user_id', $peserta->id)
            ->selectRaw("
                COALESCE(SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END), 0) as hadir,
                COALESCE(SUM(CASE WHEN status = 'terlambat' THEN 1 ELSE 0 END), 0) as terlambat,
                COALESCE(SUM(CASE WHEN status IN ('izin', 'cuti') THEN 1 ELSE 0 END), 0) as izin,
                COALESCE(SUM(CASE WHEN status = 'sakit' THEN 1 ELSE 0 END), 0) as sakit
            ")
            ->first();

        $ringkasan = [
            'hadir'     => (int) $rekap->hadir,
            'terlambat' => (int) $rekap->terlambat,
            'izin'      => (int) $rekap->izin,
            'sakit'     => (int) $rekap->sakit,
        ];

        // Pilihan bulan: dari bulan mulai magang sampai bulan ini (terbaru di atas)
        $awal = $peserta->tanggal_mulai_magang
            ? Carbon::parse($peserta->tanggal_mulai_magang)->startOfMonth()
            : now()->startOfMonth();

        if ($awal->gt(now())) {
            $awal = now()->startOfMonth();
        }

        $daftarBulan = [];
        foreach (CarbonPeriod::create($awal, '1 month', now()->startOfMonth()) as $bulan) {
            $daftarBulan[$bulan->format('Y-m')] = $bulan->translatedFormat('F Y');
        }
        $daftarBulan = array_reverse($daftarBulan, true);

        $bulanDipilih = $request->query('bulan', now()->format('Y-m'));
        if (! array_key_exists($bulanDipilih, $daftarBulan)) {
            $bulanDipilih = now()->format('Y-m');
        }

        [$tahun, $bulan] = explode('-', $bulanDipilih);

        $riwayat = Absensi::where('user_id', $peserta->id)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal')
            ->get();

        $tabAktif = $request->query('tab', 'ringkasan') === 'riwayat' ? 'riwayat' : 'ringkasan';

        return view('pembimbing.peserta.detail', compact(
            'peserta',
            'sudahSelesai',
            'ringkasan',
            'riwayat',
            'daftarBulan',
            'bulanDipilih',
            'tabAktif'
        ));
    }
}