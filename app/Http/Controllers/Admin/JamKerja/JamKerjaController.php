<?php

namespace App\Http\Controllers\Admin\JamKerja;

use App\Http\Controllers\Controller;
use App\Models\JamKerja;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JamKerjaController extends Controller
{
    /**
     * Urutan hari Senin - Minggu, dipakai untuk sorting & opsi form.
     */
    private const URUTAN_HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    public function index(): View
    {
        $semua = JamKerja::all()
            ->sortBy(fn (JamKerja $item) => array_search($item->hari, self::URUTAN_HARI))
            ->values();

        $hariAktif = $semua->where('status', true)->pluck('hari');
        $hariLibur = $semua->where('status', false)->pluck('hari');

        $toleransi = optional($semua->firstWhere('status', true))->toleransi_terlambat ?? 0;

        $stats = [
            [
                'label'  => 'Total Hari Kerja',
                'value'  => $hariAktif->count(),
                'suffix' => $this->rangeHari($hariAktif),
                'icon'   => 'calendar',
                'color'  => 'emerald',
            ],
            [
                'label'  => 'Hari Libur',
                'value'  => $hariLibur->count(),
                'suffix' => $this->rangeHari($hariLibur),
                'icon'   => 'calendar',
                'color'  => 'rose',
            ],
            [
                'label'  => 'Toleransi Keterlambatan',
                'value'  => $toleransi,
                'suffix' => 'Menit',
                'icon'   => 'clock',
                'color'  => 'amber',
            ],
        ];

        $jamKerja = $semua->map(fn (JamKerja $item, int $index) => [
            'id'         => $item->id,
            'no'         => $index + 1,
            'hari'       => $item->hari,
            'jam_masuk'  => $item->status ? optional($item->jam_masuk)->format('H:i') : null,
            'jam_pulang' => $item->status ? optional($item->jam_pulang)->format('H:i') : null,
            'toleransi'  => $item->status ? $item->toleransi_terlambat . ' Menit' : null,
            'status'     => $item->status ? 'Aktif' : 'Libur',

            // Data mentah untuk mengisi modal edit
            'status_val'     => $item->status ? 'aktif' : 'libur',
            'jam_masuk_val'  => optional($item->jam_masuk)->format('H:i'),
            'jam_pulang_val' => optional($item->jam_pulang)->format('H:i'),
            'toleransi_val'  => $item->toleransi_terlambat,
        ]);

        // Hari yang belum punya jadwal (untuk pilihan di modal tambah)
        $semuaHari    = self::URUTAN_HARI;
        $hariTersedia = array_values(array_diff(self::URUTAN_HARI, $semua->pluck('hari')->all()));

        return view('admin.data-master.jam-kerja.index', compact(
            'jamKerja',
            'stats',
            'semuaHari',
            'hariTersedia'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        JamKerja::create($this->validateData($request));

        return redirect()
            ->route('admin.data-master.jam-kerja')
            ->with('success', 'Jam kerja berhasil ditambahkan.');
    }

    public function update(Request $request, JamKerja $jamKerja): RedirectResponse
    {
        $jamKerja->update($this->validateData($request, $jamKerja->id));

        return redirect()
            ->route('admin.data-master.jam-kerja')
            ->with('success', 'Jam kerja berhasil diperbarui.');
    }

    public function destroy(JamKerja $jamKerja): RedirectResponse
    {
        if ($jamKerja->absensi()->exists()) {
            return redirect()
                ->route('admin.data-master.jam-kerja')
                ->with('error', 'Jam kerja tidak bisa dihapus karena masih digunakan oleh data absensi.');
        }

        $jamKerja->delete();

        return redirect()
            ->route('admin.data-master.jam-kerja')
            ->with('success', 'Jam kerja berhasil dihapus.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'hari' => [
                'required',
                'string',
                Rule::in(self::URUTAN_HARI),
                Rule::unique('jam_kerja', 'hari')->ignore($ignoreId),
            ],
            'status'              => ['required', 'in:aktif,libur'],
            'jam_masuk'           => ['required_if:status,aktif', 'nullable', 'date_format:H:i'],
            'jam_pulang'          => ['required_if:status,aktif', 'nullable', 'date_format:H:i', 'after:jam_masuk'],
            'toleransi_terlambat' => ['required_if:status,aktif', 'nullable', 'integer', 'min:0'],
        ]);

        $isAktif = $validated['status'] === 'aktif';

        return [
            'hari'                => $validated['hari'],
            // Dikirim sebagai literal SQL agar PostgreSQL menerimanya sebagai boolean
            'status'              => DB::raw($isAktif ? 'true' : 'false'),
            'jam_masuk'           => $isAktif ? $validated['jam_masuk'] : null,
            'jam_pulang'          => $isAktif ? $validated['jam_pulang'] : null,
            'toleransi_terlambat' => $isAktif ? $validated['toleransi_terlambat'] : null,
        ];
    }

    private function rangeHari($koleksiHari): string
    {
        if ($koleksiHari->isEmpty()) {
            return '-';
        }

        return $koleksiHari->count() > 1
            ? $koleksiHari->first() . ' - ' . $koleksiHari->last()
            : $koleksiHari->first();
    }
}