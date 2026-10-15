<?php

namespace App\Http\Controllers\Admin\Absensi;

use App\Exports\AbsensiExport;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AbsensiController extends Controller
{
    /**
     * Status yang boleh disimpan (harus sama dengan <option> di edit.blade.php).
     */
    private const STATUS_VALID = [
        'hadir',
        'terlambat',
        'izin',
        'sakit',
        'cuti',
        'izin_terlambat',
        'tidak_hadir',
        'alpha',
    ];

    /**
     * Pilihan filter periode di halaman "Semua Absensi".
     */
    private const FILTER_VALID = ['semua', 'hari_ini', 'minggu_ini', 'bulan_ini', 'custom'];

    public function index(Request $request): View
    {
        $search  = $request->input('search');
        $periode = $this->resolvePeriode($request);

        $filter       = $periode['filter'];
        $periodeLabel = $periode['label'];

        $absensis = $this->queryFilter($request, $periode)
            ->paginate(10)
            ->withQueryString();

        return view('admin.absensi.semua', compact('absensis', 'search', 'filter', 'periodeLabel'));
    }

    /**
     * Export Excel, mengikuti filter periode & search yang sedang diterapkan.
     */
    public function export(Request $request): BinaryFileResponse
    {
        $periode  = $this->resolvePeriode($request);
        $absensis = $this->queryFilter($request, $periode)->get();

        $namaFile = 'rekap-absensi-' . Str::slug($periode['label']) . '.xlsx';

        return Excel::download(
            new AbsensiExport($absensis, $periode['label'], $periode['tahun']),
            $namaFile
        );
    }

    public function hariIni(Request $request): View
    {
        $tanggalValue = $request->input('tanggal', now()->toDateString());
        $cari         = $request->input('cari');

        try {
            $tanggalCarbon = Carbon::parse($tanggalValue);
        } catch (\Exception $e) {
            $tanggalCarbon = now();
        }
        $tanggalValue = $tanggalCarbon->toDateString();

        $rolesAbsensi   = ['pegawai', 'admin_unit'];
        $semuaPeserta   = User::whereIn('role', $rolesAbsensi)->where('status', 'aktif')->pluck('id');
        $totalPeserta   = $semuaPeserta->count();
        $absensiHariIni = Absensi::whereDate('tanggal', $tanggalValue)
            ->whereIn('user_id', $semuaPeserta)
            ->get();

        $statusCounts = $absensiHariIni->groupBy(fn ($a) => strtolower($a->status ?? ''))->map->count();

        $terlambat = $statusCounts->get('terlambat', 0);

        // Hadir = hadir + terlambat (peserta yang terlambat tetap datang).
        $hadir = $statusCounts->get('hadir', 0) + $terlambat;

        $izin = $statusCounts->get('izin', 0)
            + $statusCounts->get('sakit', 0)
            + $statusCounts->get('cuti', 0)
            + $statusCounts->get('izin_terlambat', 0);

        // Tidak hadir = sisanya: status tidak_hadir/alpha + peserta yang belum punya data absen.
        $tidakHadir = max($totalPeserta - $hadir - $izin, 0);

        $ringkasan = [
            'total'       => $totalPeserta,
            'hadir'       => $hadir,
            'terlambat'   => $terlambat,
            'izin'        => $izin,
            'tidak_hadir' => $tidakHadir,
        ];

        // Daftar peserta untuk tabel, dengan filter cari nama.
        $absensiHariIniByUser = $absensiHariIni->keyBy('user_id');

        $$users = User::whereIn('role', $rolesAbsensi)
            ->where('status', 'aktif')
            ->when($cari, fn ($q) => $q->where('nama', 'ilike', "%{$cari}%"))
            ->orderBy('nama')
            ->get();

        $absensis = $users->values()->map(function (User $user, int $index) use ($absensiHariIniByUser) {
            $absensi = $absensiHariIniByUser->get($user->id);

            $jamMasuk  = $absensi?->jam_masuk ? Carbon::parse($absensi->jam_masuk)->format('H:i') : '';
            $jamPulang = $absensi?->jam_pulang ? Carbon::parse($absensi->jam_pulang)->format('H:i') : '';

            return [
                'no'         => $index + 1,
                'nip'        => $user->nip,
                'nama'       => $user->nama,
                'masuk'      => $jamMasuk ?: '-',
                'pulang'     => $jamPulang ?: '-',
                'status'     => $this->labelStatus($absensi?->status),
                'keterangan' => $absensi?->keterangan ?: '-',

                // Data untuk modal edit; null kalau peserta belum punya data absensi.
                'edit'       => $absensi ? [
                    'id'         => $absensi->id,
                    'tanggal'    => Carbon::parse($absensi->tanggal)->format('Y-m-d'),
                    'nama'       => $user->nama,
                    'nip'        => $user->nip,
                    'jam_masuk'  => $jamMasuk,
                    'jam_pulang' => $jamPulang,
                    'status'     => $absensi->status,
                    'keterangan' => $absensi->keterangan ?? '',
                ] : null,
            ];
        });

        $tanggal = $tanggalCarbon->translatedFormat('d F Y');

        return view('admin.absensi.hari-ini', compact(
            'tanggal',
            'tanggalValue',
            'ringkasan',
            'absensis'
        ));
    }

    public function rekap(Request $request): View
    {
        $periodeInput = $request->input('periode', now()->format('Y-m'));

        try {
            $awalBulan = Carbon::parse($periodeInput . '-01')->startOfMonth();
        } catch (\Exception $e) {
            $awalBulan = now()->startOfMonth();
        }
        $akhirBulan = $awalBulan->copy()->endOfMonth();

        $periode = $awalBulan->translatedFormat('F Y');

        $userIds = User::where('role', 'pegawai')->where('status', 'aktif')->pluck('id');

        $totalPegawai = $userIds->count();

        $absensiBulan = Absensi::whereIn('user_id', $userIds)
            ->whereBetween('tanggal', [$awalBulan->toDateString(), $akhirBulan->toDateString()])
            ->get();

        $statusCounts = $absensiBulan->groupBy(fn ($a) => strtolower($a->status ?? ''))->map->count();

        $rekap = [
            'total'     => $totalPegawai,
            'hadir'     => $statusCounts->get('hadir', 0),
            'terlambat' => $statusCounts->get('terlambat', 0),
            'izin'      => $statusCounts->get('izin', 0)
                + $statusCounts->get('cuti', 0)
                + $statusCounts->get('izin_terlambat', 0),
            'sakit'     => $statusCounts->get('sakit', 0),
            'alpha'     => $statusCounts->get('alpha', 0) + $statusCounts->get('tidak_hadir', 0),
        ];

        // Grafik kehadiran harian (persen hadir per tanggal) sepanjang bulan terpilih.
        $totalUserGrafik = $totalPegawai;

        $absensiPerHari = Absensi::whereIn('user_id', $userIds)
            ->whereBetween('tanggal', [$awalBulan->toDateString(), $akhirBulan->toDateString()])
            ->where('status', 'hadir')
            ->selectRaw('tanggal, count(*) as jumlah')
            ->groupBy('tanggal')
            ->pluck('jumlah', 'tanggal');

        $grafikHarian = [];
        $sumbuX       = [];
        $jumlahHari   = $awalBulan->daysInMonth;

        for ($i = 0; $i < $jumlahHari; $i++) {
            $tanggalHari    = $awalBulan->copy()->addDays($i);
            $hadirHariIni   = $absensiPerHari->get($tanggalHari->toDateString(), 0);
            $grafikHarian[] = $totalUserGrafik > 0 ? round($hadirHariIni / $totalUserGrafik * 100, 2) : 0;

            if ($i % 5 === 0 || $i === $jumlahHari - 1) {
                $sumbuX[] = $tanggalHari->translatedFormat('d M');
            }
        }

        return view('admin.absensi.rekap', compact(
            'periode',
            'periodeInput',
            'rekap',
            'grafikHarian',
            'sumbuX'
        ));
    }

    /**
     * Simpan perubahan dari modal edit (halaman "Semua Absensi" dan "Absensi Hari Ini").
     * Kalau validasi gagal, Laravel redirect back + old input (termasuk _form=edit),
     * sehingga modal otomatis terbuka lagi dengan pesan error.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $absensi = Absensi::findOrFail($id);

        $rulesJamPulang = ['nullable', 'date_format:H:i'];
        if ($request->filled('jam_masuk')) {
            $rulesJamPulang[] = 'after_or_equal:jam_masuk';
        }

        $validated = $request->validate(
            [
                'tanggal'    => ['required', 'date'],
                'jam_masuk'  => ['nullable', 'date_format:H:i'],
                'jam_pulang' => $rulesJamPulang,
                'status'     => ['required', 'in:' . implode(',', self::STATUS_VALID)],
                'keterangan' => ['nullable', 'string', 'max:500'],
            ],
            [],
            [
                'tanggal'    => 'tanggal',
                'jam_masuk'  => 'jam masuk',
                'jam_pulang' => 'jam pulang',
                'status'     => 'status',
                'keterangan' => 'keterangan',
            ]
        );

        // Cegah dua data absensi untuk peserta yang sama di tanggal yang sama.
        $duplikat = Absensi::where('user_id', $absensi->user_id)
            ->whereDate('tanggal', $validated['tanggal'])
            ->where('id', '!=', $absensi->id)
            ->exists();

        if ($duplikat) {
            throw ValidationException::withMessages([
                'tanggal' => 'Peserta ini sudah memiliki data absensi pada tanggal tersebut.',
            ]);
        }

        $absensi->update([
            'tanggal'    => $validated['tanggal'],
            'jam_masuk'  => $validated['jam_masuk'] ?? null,
            'jam_pulang' => $validated['jam_pulang'] ?? null,
            'status'     => $validated['status'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Data absensi berhasil diperbarui.');
    }

    /**
     * Hapus data absensi, lalu kembali ke halaman asal
     * (Semua Absensi atau Absensi Hari Ini, lengkap dengan filternya).
     */
    public function destroy(string $id): RedirectResponse
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();

        return redirect()
            ->back()
            ->with('success', 'Data absensi berhasil dihapus.');
    }

    /**
     * Query dasar + filter periode & search, dipakai bersama oleh index() dan export().
     *
     * @param array{awal: ?Carbon, akhir: ?Carbon, label: string, tahun: string, filter: string} $periode
     */
    private function queryFilter(Request $request, array $periode): Builder
    {
        $search = $request->input('search');

        return Absensi::query()
            ->with('user')
            ->when($periode['awal'], fn ($q, $awal) => $q->whereDate('tanggal', '>=', $awal->toDateString()))
            ->when($periode['akhir'], fn ($q, $akhir) => $q->whereDate('tanggal', '<=', $akhir->toDateString()))
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user', function ($sub) use ($search) {
                    $sub->where('nama', 'ilike', "%{$search}%")
                         ->orWhere('nip', 'ilike', "%{$search}%");
                         
                });
            })
            ->orderByDesc('tanggal')
            ->orderBy('jam_masuk');
    }

    /**
     * Terjemahkan parameter ?filter= menjadi batas awal & akhir periode.
     *
     *  - semua       : tanpa batas tanggal
     *  - hari_ini    : hari ini
     *  - minggu_ini  : Senin - Minggu pada minggu berjalan
     *  - bulan_ini   : tanggal 1 - akhir bulan berjalan
     *  - custom      : ?dari=YYYY-MM-DD&sampai=YYYY-MM-DD
     *
     * @return array{awal: ?Carbon, akhir: ?Carbon, label: string, tahun: string, filter: string}
     */
    private function resolvePeriode(Request $request): array
    {
        $filter = $request->input('filter', 'semua');
        if (!in_array($filter, self::FILTER_VALID, true)) {
            $filter = 'semua';
        }

        $semua = [
            'awal'   => null,
            'akhir'  => null,
            'label'  => 'Semua Tanggal',
            'tahun'  => (string) now()->year,
            'filter' => $filter,
        ];

        switch ($filter) {
            case 'hari_ini':
                $tgl = now();

                return [
                    'awal'   => $tgl->copy()->startOfDay(),
                    'akhir'  => $tgl->copy()->endOfDay(),
                    'label'  => 'Tanggal ' . $tgl->translatedFormat('d F Y'),
                    'tahun'  => (string) $tgl->year,
                    'filter' => $filter,
                ];

            case 'minggu_ini':
                $awal  = now()->startOfWeek(Carbon::MONDAY);
                $akhir = now()->endOfWeek(Carbon::SUNDAY);

                return [
                    'awal'   => $awal,
                    'akhir'  => $akhir,
                    'label'  => 'Minggu ' . $awal->translatedFormat('d M Y') . ' - ' . $akhir->translatedFormat('d M Y'),
                    'tahun'  => $this->labelTahun($awal, $akhir),
                    'filter' => $filter,
                ];

            case 'bulan_ini':
                $awal  = now()->startOfMonth();
                $akhir = now()->endOfMonth();

                return [
                    'awal'   => $awal,
                    'akhir'  => $akhir,
                    'label'  => 'Bulan ' . $awal->translatedFormat('F Y'),
                    'tahun'  => (string) $awal->year,
                    'filter' => $filter,
                ];

            case 'custom':
                try {
                    $dari   = $request->filled('dari') ? Carbon::parse($request->input('dari'))->startOfDay() : null;
                    $sampai = $request->filled('sampai') ? Carbon::parse($request->input('sampai'))->endOfDay() : null;
                } catch (\Exception $e) {
                    return $semua;
                }

                if (!$dari && !$sampai) {
                    return $semua;
                }

                // Kalau terbalik, tukar otomatis.
                if ($dari && $sampai && $dari->gt($sampai)) {
                    [$dari, $sampai] = [$sampai->copy()->startOfDay(), $dari->copy()->endOfDay()];
                }

                if ($dari && $sampai && $dari->isSameDay($sampai)) {
                    $label = 'Tanggal ' . $dari->translatedFormat('d F Y');
                } elseif ($dari && $sampai) {
                    $label = $dari->translatedFormat('d M Y') . ' - ' . $sampai->translatedFormat('d M Y');
                } elseif ($dari) {
                    $label = 'Mulai ' . $dari->translatedFormat('d M Y');
                } else {
                    $label = 'Sampai ' . $sampai->translatedFormat('d M Y');
                }

                return [
                    'awal'   => $dari,
                    'akhir'  => $sampai,
                    'label'  => $label,
                    'tahun'  => $this->labelTahun($dari, $sampai),
                    'filter' => $filter,
                ];
        }

        return $semua;
    }

    private function labelTahun(?Carbon $awal, ?Carbon $akhir): string
    {
        $a = $awal?->year ?? $akhir?->year ?? now()->year;
        $b = $akhir?->year ?? $awal?->year ?? now()->year;

        return $a === $b ? (string) $a : "{$a}/{$b}";
    }

    private function labelStatus(?string $status): string
    {
        if (!$status) {
            return 'Belum Absen';
        }

        return match (strtolower($status)) {
            'hadir'          => 'Hadir',
            'terlambat'      => 'Terlambat',
            'izin'           => 'Izin',
            'sakit'          => 'Sakit',
            'cuti'           => 'Cuti',
            'izin_terlambat' => 'Izin Terlambat',
            'tidak_hadir'    => 'Tidak Hadir',
            'alpha'          => 'Alpha',
            default          => ucwords(str_replace('_', ' ', $status)),
        };
    }
}