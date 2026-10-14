<?php

namespace App\Http\Controllers\Pengguna\Absensi;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\JamKerja;
use App\Models\LokasiAbsensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AbsensiController extends Controller
{
    /**
     * Halaman utama absensi (riwayat / status hari ini).
     */
    public function index()
    {
        $absensiHariIni = Absensi::where('user_id', Auth::id())
            ->whereDate('tanggal', now()->toDateString())
            ->first();

        return view('pengguna.absensi.index', compact('absensiHariIni'));
    }

    /**
     * Halaman kamera deteksi wajah (masuk/pulang otomatis).
     */
    public function create()
    {
        return view('pengguna.absensi.face-recognition');
    }

    /**
     * Simpan hasil absen dari capture wajah.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'photo'     => ['required', 'string'],
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $user  = $request->user();
        $now   = now();
        $today = $now->toDateString();

        $lokasi = $this->cariLokasiSesuai($validated['latitude'], $validated['longitude']);
        if (! $lokasi) {
            return back()->with('error', 'Anda berada di luar radius lokasi absensi. Pastikan berada di area kerja saat absen.');
        }

        $jamKerja = $this->getJamKerjaHariIni($now);
        if (! $jamKerja) {
            return back()->with('error', 'Hari ini bukan hari kerja, absensi tidak dapat dilakukan.');
        }

        $absensi = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        // Kalau hari ini sudah ditandai izin/cuti/sakit yang tidak wajib absen, tolak.
        if ($absensi && $absensi->pengajuan_izin_id) {
            $pengajuanIzin = $absensi->pengajuanIzin()->with('jenisIzin')->first();

            if ($pengajuanIzin && ! $pengajuanIzin->jenisIzin->wajib_absen) {
                return back()->with('error', 'Anda tercatat ' . $pengajuanIzin->jenisIzin->nama_izin . ' pada tanggal ini, tidak perlu melakukan absen.');
            }
        }

        if ($absensi && $absensi->jam_masuk && $absensi->jam_pulang) {
            return back()->with('error', 'Anda sudah melakukan absen masuk dan pulang hari ini.');
        }

        $tipe     = $absensi && $absensi->jam_masuk ? 'pulang' : 'masuk';
        $fotoPath = $this->simpanFoto($validated['photo'], $user->id, $tipe);

        if ($tipe === 'masuk') {
            $status = $this->tentukanStatusMasuk($now, $jamKerja);

            // Kalau ada izin terlambat yang sudah disetujui untuk hari ini, pertahankan statusnya
            // (tidak dihitung "terlambat" biasa walau jam masuk lewat batas toleransi).
            if ($absensi && $absensi->pengajuan_izin_id) {
                $status = $absensi->status;
            }

            $data = [
                'lokasi_absensi_id' => $lokasi->id,
                'jam_masuk'         => $now->toTimeString(),
                'latitude'          => $validated['latitude'],
                'longitude'         => $validated['longitude'],
                'foto_masuk'        => $fotoPath,
                'status'            => $status,
            ];

            if ($absensi) {
                $absensi->update($data);
            } else {
                Absensi::create(array_merge($data, [
                    'user_id' => $user->id,
                    'tanggal' => $today,
                ]));
            }

            $pesan = $status === 'terlambat'
                ? 'Absen masuk berhasil dicatat pukul ' . $now->format('H:i') . ', namun Anda tercatat terlambat.'
                : 'Absen masuk berhasil dicatat pukul ' . $now->format('H:i');

            return redirect()->route('pengguna.home')->with('success', $pesan);
        }

        $absensi->update([
            'jam_pulang'  => $now->toTimeString(),
            'foto_pulang' => $fotoPath,
        ]);

        return redirect()->route('pengguna.home')->with('success', 'Absen pulang berhasil dicatat pukul ' . $now->format('H:i'));
    }

    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}

    /**
     * Ambil data jam_kerja yang aktif untuk hari yang sesuai tanggal $now.
     * Asumsi kolom `hari` berisi nama hari dalam Bahasa Indonesia: Senin, Selasa, ... Minggu.
     */
    private function getJamKerjaHariIni(Carbon $now): ?JamKerja
    {
        $namaHariList = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        $namaHari = $namaHariList[$now->dayOfWeek];

        return JamKerja::where('hari', $namaHari)
            ->where('status', true)
            ->first();
    }

    /**
     * Tentukan status kehadiran (Hadir / Terlambat) berdasarkan jam_masuk
     * dan toleransi_terlambat (dalam menit) dari jam_kerja.
     */
    private function tentukanStatusMasuk(Carbon $now, JamKerja $jamKerja): string
    {
        $batasMasuk = Carbon::createFromTimeString($jamKerja->jam_masuk->format('H:i:s'), $now->timezone)
            ->setDateFrom($now)
            ->addMinutes((int) $jamKerja->toleransi_terlambat);

        return $now->greaterThan($batasMasuk) ? 'Terlambat' : 'Hadir';
    }

    /**
     * Cari lokasi absensi yang mencakup titik koordinat user (dalam radius meter-nya).
     */
    private function cariLokasiSesuai(float $lat, float $lng): ?LokasiAbsensi
    {
        foreach (LokasiAbsensi::all() as $lokasi) {
            $jarak = $this->hitungJarakMeter($lat, $lng, (float) $lokasi->latitude, (float) $lokasi->longitude);

            if ($jarak <= (float) $lokasi->radius) {
                return $lokasi;
            }
        }

        return null;
    }

    private function hitungJarakMeter(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $radiusBumi = 6371000;

        $lat1Rad   = deg2rad($lat1);
        $lat2Rad   = deg2rad($lat2);
        $deltaLat  = deg2rad($lat2 - $lat1);
        $deltaLng  = deg2rad($lng2 - $lng1);

        $a = sin($deltaLat / 2) ** 2
            + cos($lat1Rad) * cos($lat2Rad) * sin($deltaLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $radiusBumi * $c;
    }

    private function simpanFoto(string $base64, int $userId, string $tipe): string
    {
        if (! str_contains($base64, ',')) {
            $decoded = base64_decode($base64);
        } else {
            [, $data] = explode(',', $base64, 2);
            $decoded  = base64_decode($data);
        }

        $filename = "absensi/{$userId}_" . now()->format('Ymd_His') . "_{$tipe}.jpg";

        Storage::disk('public')->put($filename, $decoded);

        return $filename;
    }
}