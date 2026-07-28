<?php

namespace App\Http\Controllers\Pengguna\Absensi;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RiwayatAbsensiController extends Controller
{
    /**
     * Tampilkan riwayat absensi milik user yang sedang login,
     * difilter berdasarkan bulan & tahun (default: bulan & tahun sekarang).
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        $absensi = Absensi::where('user_id', $user->id)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderByDesc('tanggal')
            ->get();

        $riwayat = $absensi->map(function (Absensi $item) {
            $tanggal = Carbon::parse($item->tanggal)->locale('id');

            // Tentukan status badge yang ditampilkan di view.
            if (is_null($item->jam_pulang)) {
                $status = 'belum_pulang';
            } elseif (strtolower((string) $item->status) === 'terlambat') {
                $status = 'terlambat';
            } else {
                $status = 'hadir';
            }

            return [
                'id'      => $item->id,
                'hari'    => $tanggal->translatedFormat('l'),
                'tanggal' => $tanggal->translatedFormat('d F Y'),
                'masuk'   => $item->jam_masuk ? Carbon::parse($item->jam_masuk)->format('H:i') : null,
                'pulang'  => $item->jam_pulang ? Carbon::parse($item->jam_pulang)->format('H:i') : null,
                'status'  => $status,
            ];
        });

        return view('pengguna.absensi.riwayat-absensi', [
            'riwayat' => $riwayat,
            'bulan'   => $bulan,
            'tahun'   => $tahun,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}