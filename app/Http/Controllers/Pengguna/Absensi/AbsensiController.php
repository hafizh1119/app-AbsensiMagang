<?php

namespace App\Http\Controllers\Pengguna\Absensi;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\JamKerja;
use App\Models\LokasiAbsensi;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AbsensiController extends Controller
{
    /**
     * Disk penyimpanan foto absensi.
     * Di Vercel filesystem bersifat read-only, jadi ganti ke disk eksternal (mis. 's3').
     */
    private const FOTO_DISK = 'public';

    private const NAMA_HARI = [
        0 => 'Minggu',
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
    ];

    /**
     * Halaman utama absensi (status hari ini).
     */
    public function index(): View
    {
        $absensiHariIni = Absensi::where('user_id', Auth::id())
            ->whereDate('tanggal', now()->toDateString())
            ->first();

        return view('pengguna.absensi.index', compact('absensiHariIni'));
    }

    /**
     * Halaman kamera deteksi wajah (masuk/pulang otomatis).
     */
    public function create(): View
    {
        return view('pengguna.absensi.face-recognition');
    }

    /**
     * Simpan hasil absen dari capture wajah.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'photo'     => ['required', 'string'],
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $user  = $request->user();
        $now   = now();
        $today = $now->toDateString();

        $lokasi = $this->cariLokasiSesuai((float) $validated['latitude'], (float) $validated['longitude']);
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
            $jenisIzin = $absensi->pengajuanIzin()->with('jenisIzin')->first()?->jenisIzin;

            if ($jenisIzin && ! $jenisIzin->wajib_absen) {
                return back()->with('error', 'Anda tercatat ' . $jenisIzin->nama_izin . ' pada tanggal ini, tidak perlu melakukan absen.');
            }
        }

        if ($absensi && $absensi->jam_masuk && $absensi->jam_pulang) {
            return back()->with('error', 'Anda sudah melakukan absen masuk dan pulang hari ini.');
        }

        $tipe     = $absensi && $absensi->jam_masuk ? 'pulang' : 'masuk';
        $fotoPath = $this->simpanFoto($validated['photo'], $user->id, $tipe);

        if (! $fotoPath) {
            return back()->with('error', 'Foto absensi gagal disimpan. Silakan coba lagi.');
        }

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

    /**
     * Ambil data jam_kerja yang aktif untuk hari yang sesuai tanggal $now.
     * Kolom `status` bertipe boolean di PostgreSQL, jadi dibandingkan lewat SQL mentah
     * (binding true menjadi integer 1 dan ditolak PostgreSQL).
     */
    private function getJamKerjaHariIni(Carbon $now): ?JamKerja
    {
        return JamKerja::where('hari', self::NAMA_HARI[$now->dayOfWeek])
            ->whereRaw('status = true')
            ->first();
    }

    /**
     * Tentukan status kehadiran (hadir / terlambat) berdasarkan jam_masuk
     * dan toleransi_terlambat (dalam menit) dari jam_kerja.
     */
    private function tentukanStatusMasuk(Carbon $now, JamKerja $jamKerja): string
    {
        if (! $jamKerja->jam_masuk) {
            return 'hadir';
        }

        $batasMasuk = $now->copy()
            ->setTimeFromTimeString(Carbon::parse($jamKerja->jam_masuk)->format('H:i:s'))
            ->addMinutes((int) $jamKerja->toleransi_terlambat);

        return $now->greaterThan($batasMasuk) ? 'terlambat' : 'hadir';
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

        $lat1Rad  = deg2rad($lat1);
        $lat2Rad  = deg2rad($lat2);
        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLng = deg2rad($lng2 - $lng1);

        $a = sin($deltaLat / 2) ** 2
            + cos($lat1Rad) * cos($lat2Rad) * sin($deltaLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $radiusBumi * $c;
    }

    /**
     * Simpan foto base64 ke disk. Mengembalikan path file, atau null kalau gagal.
     */
    private function simpanFoto(string $base64, int $userId, string $tipe): ?string
    {
        $data    = str_contains($base64, ',') ? explode(',', $base64, 2)[1] : $base64;
        $decoded = base64_decode($data, true);

        if ($decoded === false || $decoded === '') {
            return null;
        }

        $filename = "absensi/{$userId}_" . now()->format('Ymd_His') . "_{$tipe}.jpg";

        try {
            $berhasil = Storage::disk(self::FOTO_DISK)->put($filename, $decoded);
        } catch (\Throwable $e) {
            report($e);

            return null;
        }

        return $berhasil ? $filename : null;
    }
}