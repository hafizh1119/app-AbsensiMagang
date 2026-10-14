<?php

namespace App\Http\Controllers\Pengguna\Jadwal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JadwalController extends Controller
{
    /**
     * Jam kerja tetap: Senin-Jumat.
     */
    private const SHIFT_MASUK  = '07.30';
    private const SHIFT_PULANG = '15.30';

    public function index(Request $request)
    {
        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        $awalBulan   = Carbon::createFromDate($tahun, $bulan, 1);
        $jumlahHari  = $awalBulan->daysInMonth;
        $hariPertama = $awalBulan->dayOfWeekIso; // 1 = Senin ... 7 = Minggu
        $hariIni     = now()->format('Y-m-d');
        $namaBulan   = $awalBulan->translatedFormat('F Y');

        $liburNasional = $this->ambilHariLibur($tahun);

        return view('pengguna.jadwal.index', [
            'bulan'         => $bulan,
            'tahun'         => $tahun,
            'namaBulan'     => $namaBulan,
            'jumlahHari'    => $jumlahHari,
            'hariPertama'   => $hariPertama,
            'hariIni'       => $hariIni,
            'liburNasional' => $liburNasional,
            'shiftMasuk'    => self::SHIFT_MASUK,
            'shiftPulang'   => self::SHIFT_PULANG,
        ]);
    }

    /**
     * Ambil daftar hari libur nasional Indonesia untuk 1 tahun dari Nager.Date
     * (https://date.nager.at) — API publik, gratis, tanpa rate limit, dan lebih
     * stabil dibanding sumber sebelumnya.
     *
     * Hasil di-cache 1 hari per tahun supaya tidak nembak API tiap request,
     * dan fallback ke array kosong kalau API sedang bermasalah / offline.
     *
     * Return format: ['Y-m-d' => 'Nama libur', ...]
     */
    private function ambilHariLibur(int $tahun): array
    {
        return Cache::remember("libur-nasional-{$tahun}", now()->addDay(), function () use ($tahun) {
            try {
                $response = Http::timeout(5)->get("https://date.nager.at/api/v3/PublicHolidays/{$tahun}/ID");

                if (! $response->successful()) {
                    return [];
                }

                return collect($response->json())
                    ->mapWithKeys(fn ($item) => [$item['date'] => $item['localName']])
                    ->toArray();
            } catch (\Throwable $e) {
                Log::warning('Gagal mengambil data hari libur nasional: ' . $e->getMessage());

                return [];
            }
        });
    }
}