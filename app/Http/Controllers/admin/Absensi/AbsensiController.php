<?php

namespace App\Http\Controllers\Admin\Absensi;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\JenisUnit;
use App\Models\PenempatanUnit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tanggal = $request->input('tanggal');
        $unitId  = $request->input('unit_id');
        $search  = $request->input('search');

        $absensis = Absensi::query()
            ->with(['user.penempatanUnit.jenisUnit'])
            ->when($tanggal, fn ($q) => $q->whereDate('tanggal', $tanggal))
            ->when($unitId, function ($q) use ($unitId) {
                $q->whereHas('user.penempatanUnit', function ($sub) use ($unitId) {
                    $sub->where('jenis_unit_id', $unitId);
                });
            })
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user', function ($sub) use ($search) {
                    $sub->where('nama', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('tanggal')
            ->orderBy('jam_masuk')
            ->paginate(10)
            ->withQueryString();

        $units = JenisUnit::orderBy('nama_unit')->get();

        return view('admin.absensi.semua', compact('absensis', 'units', 'tanggal', 'unitId', 'search'));
    }

    /**
     * Display attendance summary and list for a given day (default: today).
     */
    public function hariIni(Request $request): View
    {
        $tanggalValue = $request->input('tanggal', now()->toDateString());
        $cari         = $request->input('cari');
        $unitNama     = $request->input('unit');

        // Ringkasan dihitung dari SELURUH pegawai aktif, tidak terpengaruh filter tabel.
        $semuaPegawai   = User::where('role', 'pegawai')->where('status', 'aktif')->pluck('id');
        $totalPegawai   = $semuaPegawai->count();
        $absensiHariIni = Absensi::whereDate('tanggal', $tanggalValue)
            ->whereIn('user_id', $semuaPegawai)
            ->get();

        $statusCounts = $absensiHariIni->groupBy(fn ($a) => strtolower($a->status ?? ''))->map->count();

        $hadir      = $statusCounts->get('hadir', 0);
        $terlambat  = $statusCounts->get('terlambat', 0);
        $izin       = $statusCounts->get('izin', 0) + $statusCounts->get('sakit', 0);
        $belumAbsen = max($totalPegawai - $absensiHariIni->count(), 0);

        $ringkasan = [
            'total'       => $totalPegawai,
            'hadir'       => $hadir,
            'terlambat'   => $terlambat,
            'belum_absen' => $belumAbsen,
            'izin'        => $izin,
        ];

        // Daftar pegawai untuk tabel, dengan filter cari nama & unit.
        $absensiHariIniByUser = $absensiHariIni->keyBy('user_id');

        $users = User::with('penempatanUnit.jenisUnit')
            ->where('role', 'pegawai')
            ->where('status', 'aktif')
            ->when($cari, fn ($q) => $q->where('nama', 'like', "%{$cari}%"))
            ->when($unitNama, function ($q) use ($unitNama) {
                $q->whereHas('penempatanUnit.jenisUnit', function ($sub) use ($unitNama) {
                    $sub->where('nama_unit', $unitNama);
                });
            })
            ->orderBy('nama')
            ->get();

        $absensis = $users->values()->map(function (User $user, int $index) use ($absensiHariIniByUser) {
            $absensi = $absensiHariIniByUser->get($user->id);
            $unit    = $user->penempatanUnit->first()?->jenisUnit;

            return [
                'no'         => $index + 1,
                'nip'        => $user->nip,
                'nama'       => $user->nama,
                'unit'       => $unit->nama_unit ?? '-',
                'masuk'      => $absensi?->jam_masuk ? Carbon::parse($absensi->jam_masuk)->format('H:i') : '-',
                'pulang'     => $absensi?->jam_pulang ? Carbon::parse($absensi->jam_pulang)->format('H:i') : '-',
                'status'     => $this->labelStatus($absensi?->status),
                'keterangan' => $absensi?->keterangan ?? '-',
            ];
        });

        $unitOptions = JenisUnit::orderBy('nama_unit')->pluck('nama_unit');

        $tanggalValue = $tanggalValue;
        $tanggal      = Carbon::parse($tanggalValue)->translatedFormat('d F Y');

        return view('admin.absensi.hari-ini', compact(
            'tanggal',
            'tanggalValue',
            'ringkasan',
            'absensis',
            'unitOptions'
        ));
    }

    /**
     * Terjemahkan status mentah dari database ke label yang dipakai view.
     */
    private function labelStatus(?string $status): string
    {
        if (!$status) {
            return 'Belum Absen';
        }

        return match (strtolower($status)) {
            'hadir'     => 'Hadir',
            'terlambat' => 'Terlambat',
            'izin'      => 'Izin',
            'sakit'     => 'Sakit',
            default     => ucwords(str_replace('_', ' ', $status)),
        };
    }

    /**
     * Rekapitulasi absensi per unit untuk satu bulan, plus grafik kehadiran harian.
     */
    public function rekap(Request $request): View
    {
        $periodeInput = $request->input('periode', now()->format('Y-m'));
        $unitFilter   = $request->input('unit');

        try {
            $awalBulan = Carbon::parse($periodeInput . '-01')->startOfMonth();
        } catch (\Exception $e) {
            $awalBulan = now()->startOfMonth();
        }
        $akhirBulan = $awalBulan->copy()->endOfMonth();

        $periode = $awalBulan->translatedFormat('F Y');

        $unitList = JenisUnit::orderBy('nama_unit')
            ->when($unitFilter, fn ($q) => $q->where('nama_unit', $unitFilter))
            ->get();

        $rekap = $unitList->map(function (JenisUnit $unit) use ($awalBulan, $akhirBulan) {
            $userIds = PenempatanUnit::where('jenis_unit_id', $unit->id)->pluck('user_id');

            $totalPegawai = User::whereIn('id', $userIds)
                ->where('role', 'pegawai')
                ->where('status', 'aktif')
                ->count();

            $absensiBulan = Absensi::whereIn('user_id', $userIds)
                ->whereBetween('tanggal', [$awalBulan->toDateString(), $akhirBulan->toDateString()])
                ->get();

            $statusCounts = $absensiBulan->groupBy(fn ($a) => strtolower($a->status ?? ''))->map->count();

            return [
                'unit'      => $unit->nama_unit,
                'total'     => $totalPegawai,
                'hadir'     => $statusCounts->get('hadir', 0),
                'terlambat' => $statusCounts->get('terlambat', 0),
                'izin'      => $statusCounts->get('izin', 0),
                'sakit'     => $statusCounts->get('sakit', 0),
                'alpha'     => $statusCounts->get('alpha', 0) + $statusCounts->get('tidak_hadir', 0),
            ];
        });

        // Grafik kehadiran harian (persen hadir per tanggal) sepanjang bulan terpilih.
        $userIdsGrafik = $unitFilter
            ? PenempatanUnit::whereHas('jenisUnit', fn ($q) => $q->where('nama_unit', $unitFilter))->pluck('user_id')
            : User::where('role', 'pegawai')->pluck('id');

        $totalUserGrafik = User::whereIn('id', $userIdsGrafik)
            ->where('status', 'aktif')
            ->count();

        $absensiPerHari = Absensi::whereIn('user_id', $userIdsGrafik)
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

        $units = JenisUnit::orderBy('nama_unit')->pluck('nama_unit');

        return view('admin.absensi.rekap', compact(
            'periode',
            'periodeInput',
            'rekap',
            'grafikHarian',
            'sumbuX',
            'units',
            'unitFilter'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function destroy(string $id): RedirectResponse
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();

        return redirect()
            ->route('admin.absensi.semua')
            ->with('success', 'Data absensi berhasil dihapus.');
    }
}