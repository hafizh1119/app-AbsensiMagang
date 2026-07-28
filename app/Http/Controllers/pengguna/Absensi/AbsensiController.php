<?php

namespace App\Http\Controllers\Pengguna\Absensi;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\LokasiAbsensi;
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

        // NOTE: sebelumnya 'absen.index' -> tidak sesuai konvensi folder
        // 'pengguna.*' yang dipakai di route lain. Sesuaikan kembali kalau
        // nama file view kamu memang berbeda.
        return view('pengguna.absensi.index', compact('absensiHariIni'));
    }

    /**
     * Halaman kamera deteksi wajah (masuk/pulang otomatis).
     */
    public function create()
    {
        // NOTE: sebelumnya 'absen.face-recognition' -> disesuaikan agar
        // konsisten dengan konvensi 'pengguna.*'.
        return view('pengguna.absensi.face-recognition');
    }

    /**
     * Simpan hasil absen dari capture wajah.
     *
     * Otomatis menentukan ini absen masuk atau pulang berdasarkan
     * data absensi hari ini milik user yang sedang login.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'photo'     => ['required', 'string'],
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $user  = $request->user();
        $today = now()->toDateString();

        // 1. Pastikan titik user ada di dalam radius salah satu lokasi absensi.
        $lokasi = $this->cariLokasiSesuai($validated['latitude'], $validated['longitude']);

        if (! $lokasi) {
            return back()->with('error', 'Anda berada di luar radius lokasi absensi. Pastikan berada di area kerja saat absen.');
        }

        // 2. Cek apakah user sudah punya record absensi hari ini.
        $absensi = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        // Sudah absen masuk & pulang -> tidak bisa absen lagi hari ini.
        if ($absensi && $absensi->jam_masuk && $absensi->jam_pulang) {
            return back()->with('error', 'Anda sudah melakukan absen masuk dan pulang hari ini.');
        }

        $tipe     = $absensi && $absensi->jam_masuk ? 'pulang' : 'masuk';
        $fotoPath = $this->simpanFoto($validated['photo'], $user->id, $tipe);

        if ($tipe === 'masuk') {
            Absensi::create([
                'user_id'           => $user->id,
                'lokasi_absensi_id' => $lokasi->id,
                'tanggal'           => $today,
                'jam_masuk'         => now()->toTimeString(),
                'latitude'          => $validated['latitude'],
                'longitude'         => $validated['longitude'],
                'foto_masuk'        => $fotoPath,
                'status'            => 'Hadir',
            ]);

            return redirect()
                ->route('pengguna.home')
                ->with('success', 'Absen masuk berhasil dicatat pukul ' . now()->format('H:i'));
        }

        $absensi->update([
            'jam_pulang'  => now()->toTimeString(),
            'foto_pulang' => $fotoPath,
        ]);

        return redirect()
            ->route('pengguna.home')
            ->with('success', 'Absen pulang berhasil dicatat pukul ' . now()->format('H:i'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Cari lokasi absensi yang mencakup titik koordinat user (dalam radius meter-nya).
     *
     * Asumsi: tabel lokasi_absensi punya kolom `latitude`, `longitude`, `radius` (meter).
     * Sesuaikan nama kolomnya kalau di project kamu berbeda.
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

    /**
     * Hitung jarak dua koordinat pakai formula Haversine, hasil dalam meter.
     */
    private function hitungJarakMeter(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $radiusBumi = 6371000; // meter

        $lat1Rad   = deg2rad($lat1);
        $lat2Rad   = deg2rad($lat2);
        $deltaLat  = deg2rad($lat2 - $lat1);
        $deltaLng  = deg2rad($lng2 - $lng1);

        $a = sin($deltaLat / 2) ** 2
            + cos($lat1Rad) * cos($lat2Rad) * sin($deltaLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $radiusBumi * $c;
    }

    /**
     * Decode foto base64 (hasil canvas.toDataURL) lalu simpan ke storage.
     */
    private function simpanFoto(string $base64, int $userId, string $tipe): string
    {
        if (! str_contains($base64, ',')) {
            // Jaga-jaga kalau string base64 tidak mengandung header data URI
            // (misal "data:image/jpeg;base64,xxxx"), supaya tidak error saat explode.
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