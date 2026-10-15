<?php

namespace App\Http\Controllers\Admin\Izin;

use App\Exports\IzinExport;
use App\Http\Controllers\Controller;
use App\Models\JenisIzin;
use App\Models\JenisUnit;
use App\Models\PengajuanIzin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PengajuanIzinController extends Controller
{
    /**
     * Status yang valid di database (check constraint pengajuan_izin_status_check).
     */
    private const STATUS_VALID = ['menunggu', 'disetujui', 'ditolak'];

    public function index()
    {
        //
    }

    /**
     * Halaman "Semua Pengajuan" untuk admin, lengkap dengan filter & paginasi.
     */
    public function semua(Request $request)
    {
        $query = PengajuanIzin::with(['user.unit', 'jenisIzin']);

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_pengajuan', $request->tanggal);
        }

        if ($request->filled('unit') && $request->unit !== 'Semua Unit') {
            $query->whereHas('user.unit', function ($q) use ($request) {
                $q->where('nama_unit', $request->unit);
            });
        }

        if ($request->filled('jenis') && $request->jenis !== 'Semua Jenis') {
            $query->whereHas('jenisIzin', function ($q) use ($request) {
                $q->where('nama_izin', $request->jenis);
            });
        }

        if ($request->filled('status')) {
            $status = strtolower((string) $request->status);

            if (in_array($status, self::STATUS_VALID, true)) {
                $query->where('status', $status);
            }
        }

        $pengajuan = $query->latest('tanggal_pengajuan')
            ->paginate(10)
            ->withQueryString();

        $jenisIzinList = JenisIzin::pluck('nama_izin');
        $unitList      = JenisUnit::pluck('nama_unit');

        return view('admin.izin.semua', compact('pengajuan', 'jenisIzinList', 'unitList'));
    }

    /**
     * Halaman "Menunggu Persetujuan".
     */
    public function menunggu(Request $request)
    {
        // Pengajuan yang masih menunggu persetujuan
        $totalMenunggu = PengajuanIzin::where('status', 'menunggu')->count();

        // Seluruh pengajuan izin (semua status)
        $totalIzin = PengajuanIzin::count();

        $pengajuan = PengajuanIzin::with(['user.unit', 'jenisIzin'])
            ->where('status', 'menunggu')
            ->latest('tanggal_pengajuan')
            ->paginate(10)
            ->withQueryString();

        return view('admin.izin.menunggu', compact(
            'pengajuan',
            'totalMenunggu',
            'totalIzin'
        ));
    }

    /**
     * Setujui pengajuan izin.
     */
    public function approve(Request $request, string $id)
    {
        $pengajuan = PengajuanIzin::findOrFail($id);

        if (strtolower((string) $pengajuan->status) !== 'menunggu') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $tanggalPersetujuan = $request->filled('tanggal_persetujuan')
            ? Carbon::parse($request->input('tanggal_persetujuan'))
            : now();

        $pengajuan->update([
            'status'              => 'disetujui',
            'disetujui_oleh'      => auth()->id(),
            'tanggal_persetujuan' => $tanggalPersetujuan,
        ]);

        return back()->with('success', 'Pengajuan izin berhasil disetujui.');
    }

    /**
     * Tolak pengajuan izin.
     */
    public function reject(Request $request, string $id)
    {
        $pengajuan = PengajuanIzin::findOrFail($id);

        if (strtolower((string) $pengajuan->status) !== 'menunggu') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'catatan' => ['required', 'string'],
        ]);

        $tanggalPersetujuan = $request->filled('tanggal_persetujuan')
            ? Carbon::parse($request->input('tanggal_persetujuan'))
            : now();

        $pengajuan->update([
            'status'              => 'ditolak',
            'disetujui_oleh'      => auth()->id(),
            'tanggal_persetujuan' => $tanggalPersetujuan,
            'catatan'             => $request->input('catatan'),
        ]);

        return back()->with('success', 'Pengajuan izin ditolak.');
    }

    public function export(Request $request): BinaryFileResponse
    {
        $tanggal = $request->input('tanggal');
        $unit    = $request->input('unit');
        $jenis   = $request->input('jenis');

        $data = PengajuanIzin::query()
            ->with(['user.unit', 'jenisIzin'])
            ->whereRaw('LOWER(status) = ?', ['disetujui'])
            ->when($tanggal, fn ($q) => $q->whereDate('tanggal_pengajuan', $tanggal))
            ->when($unit && $unit !== 'Semua Unit', function ($q) use ($unit) {
                $q->whereHas('user.unit', fn ($s) => $s->where('nama_unit', $unit));
            })
            ->when($jenis && $jenis !== 'Semua Jenis', function ($q) use ($jenis) {
                $q->whereHas('jenisIzin', fn ($s) => $s->where('nama_izin', $jenis));
            })
            ->orderByDesc('tanggal_pengajuan')
            ->get();

        $label = 'Pengajuan Disetujui';
        if ($tanggal) {
            $label .= ' - Tanggal ' . Carbon::parse($tanggal)->translatedFormat('d F Y');
        }
        if ($unit && $unit !== 'Semua Unit') {
            $label .= ' - ' . $unit;
        }

        $tahun = (string) ($tanggal ? Carbon::parse($tanggal)->year : now()->year);

        $namaFile = 'rekap-izin-disetujui-' . ($tanggal ?: 'semua-tanggal') . '.xlsx';

        return Excel::download(new IzinExport($data, $label, $tahun), $namaFile);
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
        $pengajuan = PengajuanIzin::with(['user.unit', 'jenisIzin'])
            ->findOrFail($id);

        return view('admin.izin.show', compact('pengajuan'));
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