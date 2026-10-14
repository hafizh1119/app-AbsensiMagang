<?php

namespace App\Http\Controllers\Admin\Absensi;

use App\Http\Controllers\Controller;
use App\Models\LokasiAbsensi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LokasiKantorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $lokasi = LokasiAbsensi::withCount('absensi')
            ->orderBy('nama_lokasi')
            ->get()
            ->map(function (LokasiAbsensi $item, int $index) {
                return [
                    'id'             => $item->id,
                    'no'             => $index + 1,
                    'nama'           => $item->nama_lokasi,
                    // Kolom 'utama' belum ada di tabel lokasi_absensi.
                    // Sementara di-set false, tambahkan migrasi kalau butuh fitur "lokasi utama".
                    'utama'          => false,
                    'alamat'         => $item->alamat,
                    'latitude'       => $item->latitude,
                    'longitude'      => $item->longitude,
                    'radius'         => $item->radius . ' Meter',
                    'jumlah_pegawai' => $item->absensi_count,
                    // Kolom 'status' juga belum ada di tabel, default 'Aktif' dulu.
                    'status'         => 'Aktif',
                ];
            });

        return view('admin.lokasi-kantor.index', compact('lokasi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Titik default peta (contoh: pusat Kota Semarang)
        $default = [
            'lat'    => '-6.986931',
            'lng'    => '110.420279',
            'radius' => 100,
        ];

        return view('admin.lokasi-kantor.create', compact('default'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);

        LokasiAbsensi::create([
            'nama_lokasi' => $validated['nama'],
            'alamat'      => $validated['alamat'],
            'latitude'    => $validated['latitude'],
            'longitude'   => $validated['longitude'],
            'radius'      => $validated['radius'],
        ]);

        return redirect()
            ->route('admin.lokasi-kantor')
            ->with('success', 'Lokasi kantor berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LokasiAbsensi $lokasiKantor): View
    {
        $lokasi = $this->toViewObject($lokasiKantor);

        return view('admin.lokasi-kantor.show', compact('lokasi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LokasiAbsensi $lokasiKantor): View
    {
        $lokasi = $this->toViewObject($lokasiKantor);

        return view('admin.lokasi-kantor.edit', compact('lokasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LokasiAbsensi $lokasiKantor): RedirectResponse
    {
        $validated = $this->validateData($request);

        $lokasiKantor->update([
            'nama_lokasi' => $validated['nama'],
            'alamat'      => $validated['alamat'],
            'latitude'    => $validated['latitude'],
            'longitude'   => $validated['longitude'],
            'radius'      => $validated['radius'],
        ]);

        return redirect()
            ->route('admin.lokasi-kantor')
            ->with('success', 'Lokasi kantor berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LokasiAbsensi $lokasiKantor): RedirectResponse
    {
        $lokasiKantor->delete();

        return redirect()
            ->route('admin.lokasi-kantor')
            ->with('success', 'Lokasi kantor berhasil dihapus.');
    }

    /**
     * Validasi input dari form create/edit lokasi.
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'nama'      => ['required', 'string', 'max:255'],
            'alamat'    => ['nullable', 'string'],
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius'    => ['required', 'integer', 'min:20', 'max:1000'],
        ]);
    }

    /**
     * Ubah model LokasiAbsensi jadi object dengan nama properti
     * yang dipakai di view show/edit (nama, alamat, lat, lng, radius).
     */
    private function toViewObject(LokasiAbsensi $item): object
    {
        return (object) [
            'id'     => $item->id,
            'nama'   => $item->nama_lokasi,
            // Belum ada kolom 'utama' di tabel, default false dulu.
            'utama'  => false,
            'alamat' => $item->alamat,
            'lat'    => (string) $item->latitude,
            'lng'    => (string) $item->longitude,
            'radius' => $item->radius,
        ];
    }
}