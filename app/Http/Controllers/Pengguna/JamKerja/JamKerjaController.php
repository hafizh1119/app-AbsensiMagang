<?php

namespace App\Http\Controllers\Pengguna\JamKerja;

use App\Http\Controllers\Controller;
use App\Models\JamKerja;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JamKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $bulan = (int) $request->input('bulan', now('Asia/Jakarta')->month);
        $tahun = (int) $request->input('tahun', now('Asia/Jakarta')->year);

        $tanggalAwal = Carbon::createFromDate($tahun, $bulan, 1);

        $namaBulan   = $tanggalAwal->locale('id')->translatedFormat('F Y');
        $jumlahHari  = $tanggalAwal->daysInMonth;
        $hariPertama = $tanggalAwal->dayOfWeekIso; // 1 = Senin ... 7 = Minggu
        $hariIni     = now('Asia/Jakarta')->format('Y-m-d');

        // Ambil jam kerja hari Senin sebagai acuan (Senin-Jumat dianggap sama)
        $jamKerja = JamKerja::where('hari', 'Senin')
            ->where('status', true)
            ->first();

        $shiftMasuk  = $jamKerja?->jam_masuk?->format('H:i') ?? '-';
        $shiftPulang = $jamKerja?->jam_pulang?->format('H:i') ?? '-';

        $liburNasional = $this->getLiburNasional($tahun, $bulan);

        return view('pengguna.jadwal.index', compact(
            'bulan',
            'tahun',
            'namaBulan',
            'jumlahHari',
            'hariPertama',
            'hariIni',
            'shiftMasuk',
            'shiftPulang',
            'liburNasional'
        ));
    }

    /**
     * Ambil daftar hari libur nasional untuk bulan & tahun tertentu.
     * Menggunakan API publik "hari libur Indonesia".
     * Format hasil: ['Y-m-d' => 'Nama Libur']
     */
    private function getLiburNasional(int $tahun, int $bulan): array
    {
        try {
            $response = Http::timeout(3)->get("https://api-harilibur.vercel.app/api", [
                'year'  => $tahun,
                'month' => $bulan,
            ]);

            if (! $response->successful()) {
                return [];
            }

            return collect($response->json())
                ->filter(fn ($item) => ($item['is_national_holiday'] ?? false) === true)
                ->mapWithKeys(fn ($item) => [$item['holiday_date'] => $item['holiday_name']])
                ->toArray();

        } catch (\Throwable $e) {
            // Kalau API gagal/timeout, jangan sampai halaman error, cukup kosongkan libur
            return [];
        }
    }

    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}