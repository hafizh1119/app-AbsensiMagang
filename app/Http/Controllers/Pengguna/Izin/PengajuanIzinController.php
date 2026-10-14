<?php

namespace App\Http\Controllers\Pengguna\Izin;

use App\Http\Controllers\Controller;
use App\Models\JenisIzin;
use App\Models\PengajuanIzin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PengajuanIzinController extends Controller
{
    const SESSION_KEY = 'pengajuan_izin_draft';

    /**
     * Klik menu "Izin" -> langsung tampil form lengkap (pilih jenis izin ada di dalamnya).
     */
    public function index()
    {
        $jenisIzinList = JenisIzin::select('id', 'nama_izin', 'kuota')
            ->orderBy('nama_izin')
            ->get();

        $atasanList = User::where('role', 'admin_unit')->get();

        return view('pengguna.izin.form-pengajuan', compact('jenisIzinList', 'atasanList'));
    }

    /**
     * Terima input dari form, simpan sementara di session,
     * lalu tampilkan halaman review.
     */
    public function review(Request $request)
    {
        $validated = $request->validate([
            'jenis_izin_id'   => ['required', 'exists:jenis_izin,id'],
            'tanggal_mulai'   => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'ditujukan_kepada' => ['required', 'exists:users,id'],
            'alasan'          => ['required', 'string', 'max:200'],
            'lampiran'        => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $jenisIzin = JenisIzin::findOrFail($validated['jenis_izin_id']);

        $tanggalMulai   = Carbon::parse($validated['tanggal_mulai']);
        $tanggalSelesai = ($validated['tanggal_selesai'] ?? null)
            ? Carbon::parse($validated['tanggal_selesai'])
            : $tanggalMulai->copy();

        $durasi = $tanggalMulai->diffInDays($tanggalSelesai) + 1;

        $lampiranPath = null;
        $lampiranNamaAsli = null;

        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $namaFile = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('file-izin'), $namaFile);

            $lampiranPath = 'file-izin/' . $namaFile;
            $lampiranNamaAsli = $file->getClientOriginalName();
        }

        session([
            self::SESSION_KEY => [
                'jenis_izin_id'      => $jenisIzin->id,
                'jenis_izin_nama'    => $jenisIzin->nama_izin,
                'tanggal_mulai'      => $tanggalMulai->format('Y-m-d'),
                'tanggal_selesai'    => $tanggalSelesai->format('Y-m-d'),
                'durasi'             => $durasi,
                'ditujukan_kepada'   => $validated['ditujukan_kepada'],
                'alasan'             => $validated['alasan'],
                'lampiran'           => $lampiranPath,
                'lampiran_nama_asli' => $lampiranNamaAsli,
            ],
        ]);

        $atasan = User::find($validated['ditujukan_kepada']);

        return view('pengguna.izin.review', [
            'draft'  => session(self::SESSION_KEY),
            'atasan' => $atasan,
        ]);
    }

    /**
     * Simpan draft dari session ke database.
     */
    public function store(Request $request)
    {
        $draft = session(self::SESSION_KEY);

        if (! $draft) {
            return redirect()->route('pengguna.izin.index')
                ->with('error', 'Data pengajuan tidak ditemukan, silakan isi ulang.');
        }

        $pengajuan = PengajuanIzin::create([
            'user_id'           => Auth::id(),
            'jenis_izin_id'     => $draft['jenis_izin_id'],
            'tanggal_pengajuan' => now(),
            'tanggal_mulai'     => $draft['tanggal_mulai'],
            'tanggal_selesai'   => $draft['tanggal_selesai'],
            'durasi'            => $draft['durasi'],
            'alasan'            => $draft['alasan'],
            'lampiran'          => $draft['lampiran'],
            'status'            => 'Menunggu',
            'disetujui_oleh'    => $draft['ditujukan_kepada'],
        ]);

        session()->forget(self::SESSION_KEY);

        return redirect()->route('pengguna.izin.akhir', $pengajuan->id);
    }

    /**
     * Halaman "Pengajuan Berhasil" — muncul sesaat setelah submit.
     * Konfirmasi singkat, bukan detail lengkap.
     */
    public function show(string $id)
    {
        $pengajuan = PengajuanIzin::with(['jenisIzin', 'penyetuju'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $nomorPengajuan = 'IZN-' . $pengajuan->tanggal_pengajuan->format('Ymd')
            . '-' . str_pad($pengajuan->id, 5, '0', STR_PAD_LEFT);

        return view('pengguna.izin.akhir', compact('pengajuan', 'nomorPengajuan'));
    }

    /**
     * Halaman Detail pengajuan — dibuka dari klik item di Riwayat.
     * Menampilkan status berwarna (menunggu/disetujui/ditolak) + detail lengkap.
     */
    public function detail(string $id)
    {
        $pengajuan = PengajuanIzin::with(['jenisIzin', 'penyetuju'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $nomorPengajuan = 'IZN-' . $pengajuan->tanggal_pengajuan->format('Ymd')
            . '-' . str_pad($pengajuan->id, 5, '0', STR_PAD_LEFT);

        return view('pengguna.izin.detail', compact('pengajuan', 'nomorPengajuan'));
    }

    /**
     * Riwayat pengajuan izin milik user yang login.
     * Support filter tab: ?status=semua|menunggu|disetujui|ditolak
     */
    public function riwayat(Request $request)
    {
        $activeTab = $request->query('status', 'semua');

        $query = PengajuanIzin::with(['jenisIzin', 'penyetuju'])
            ->where('user_id', Auth::id());

        if ($activeTab !== 'semua') {
            $query->whereRaw('LOWER(status) = ?', [$activeTab]);
        }

        $riwayat = $query->latest('tanggal_pengajuan')->paginate(10)->withQueryString();

        return view('pengguna.izin.riwayat-izin', compact('riwayat', 'activeTab'));
    }
}