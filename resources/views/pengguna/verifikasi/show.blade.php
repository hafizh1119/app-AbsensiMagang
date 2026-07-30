{{-- resources/views/pengguna/verifikasi/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Verifikasi Pengajuan Izin')

@section('content')

@include('pengguna.components.header', ['title' => 'Verifikasi Pengajuan Izin'])

<div class="bg-gray-50 px-5 py-5">

    @php
        $statusLower = strtolower($pengajuan->status);

        $statusStyle = [
            'menunggu'  => 'bg-amber-50 text-amber-600',
            'disetujui' => 'bg-sky-50 text-sky-600',
            'ditolak'   => 'bg-red-50 text-red-500',
        ];

        $statusLabel = [
            'menunggu'  => 'Menunggu Persetujuan',
            'disetujui' => 'Disetujui',
            'ditolak'   => 'Ditolak',
        ];
    @endphp

    @if(session('success'))
        <div class="bg-sky-50 border border-sky-200 text-sky-700 text-sm rounded-xl px-4 py-3 mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Status Badge --}}
    <div class="mb-4">
        <span class="inline-block px-3 py-1.5 rounded-full text-xs font-semibold {{ $statusStyle[$statusLower] ?? 'bg-gray-100 text-gray-600' }}">
            {{ $statusLabel[$statusLower] ?? $pengajuan->status }}
        </span>
    </div>

    {{-- Jenis Izin & Tanggal Pengajuan --}}
    <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $pengajuan->jenisIzin->nama_izin ?? '-' }}</h2>
    <p class="text-sm text-gray-400 mb-5">
        Diajukan pada {{ $pengajuan->tanggal_pengajuan->translatedFormat('d M Y, H:i') }}
    </p>

    {{-- Card: Data Pemohon --}}
    <div class="bg-white rounded-2xl shadow-sm divide-y divide-gray-100 mb-5">

        <div class="flex items-start justify-between px-5 py-4">
            <span class="text-sm text-gray-500">Diajukan Oleh</span>
            <div class="text-right">
                <p class="text-sm text-gray-900 font-semibold">{{ $pengajuan->user->nama ?? '-' }}</p>
                <p class="text-xs text-gray-400">{{ $pengajuan->user->jabatan ?? '-' }}</p>
            </div>
        </div>

        <div class="flex items-center justify-between px-5 py-4">
            <span class="text-sm text-gray-500">NIP</span>
            <span class="text-sm text-gray-900 font-semibold">{{ $pengajuan->user->nip ?? '-' }}</span>
        </div>

        <div class="flex items-center justify-between px-5 py-4">
            <span class="text-sm text-gray-500">Unit</span>
            <span class="text-sm text-gray-900 font-semibold">{{ $pengajuan->user->unit->nama_unit ?? '-' }}</span>
        </div>

    </div>

    {{-- Card: Detail Pengajuan --}}
    <div class="bg-white rounded-2xl shadow-sm divide-y divide-gray-100 mb-5">

        <div class="flex items-start justify-between px-5 py-4">
            <span class="text-sm text-gray-500">Tanggal Izin</span>
            <div class="text-right">
                <p class="text-sm text-gray-900 font-semibold">
                    {{ $pengajuan->tanggal_mulai->translatedFormat('d M Y') }}
                    @if($pengajuan->tanggal_selesai && !$pengajuan->tanggal_mulai->equalTo($pengajuan->tanggal_selesai))
                        - {{ $pengajuan->tanggal_selesai->translatedFormat('d M Y') }}
                    @endif
                </p>
                <p class="text-xs text-gray-400 mt-0.5">({{ $pengajuan->durasi }} hari)</p>
            </div>
        </div>

        <div class="flex items-start justify-between px-5 py-4 gap-4">
            <span class="text-sm text-gray-500 shrink-0">Keterangan</span>
            <span class="text-sm text-gray-800 text-right leading-relaxed">{{ $pengajuan->alasan }}</span>
        </div>

        @if($statusLower !== 'menunggu')
            <div class="flex items-center justify-between px-5 py-4">
                <span class="text-sm text-gray-500">
                    {{ $statusLower === 'disetujui' ? 'Disetujui Oleh' : 'Ditolak Oleh' }}
                </span>
                <div class="text-right">
                    <p class="text-sm text-gray-900 font-semibold">{{ $pengajuan->penyetuju->nama ?? '-' }}</p>
                    @if($pengajuan->tanggal_persetujuan)
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ \Carbon\Carbon::parse($pengajuan->tanggal_persetujuan)->translatedFormat('d M Y, H:i') }}
                        </p>
                    @endif
                </div>
            </div>

            @if($statusLower === 'ditolak')
                <div class="px-5 py-4">
                    <p class="text-sm text-gray-500 mb-1.5">Catatan Penolakan</p>
                    <p class="text-sm text-gray-800">{{ $pengajuan->catatan ?? '-' }}</p>
                </div>
            @endif
        @endif

    </div>

    {{-- Lampiran --}}
    <div class="mb-5">
        <p class="text-sm font-semibold text-gray-800 mb-2">Lampiran</p>
        @if($pengajuan->lampiran)
            <a href="{{ asset($pengajuan->lampiran) }}" target="_blank"
               class="flex items-center justify-between bg-white rounded-2xl shadow-sm px-5 py-4 hover:bg-gray-50 transition">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="shrink-0 w-9 h-9 rounded-lg bg-sky-50 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-sky-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <path d="M14 2v6h6"></path>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-800 truncate">{{ basename($pengajuan->lampiran) }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-sky-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 3v12"></path>
                    <path d="M7 10l5 5 5-5"></path>
                    <path d="M5 21h14"></path>
                </svg>
            </a>
        @else
            <p class="text-sm text-gray-400">Tidak ada lampiran</p>
        @endif
    </div>

    {{-- Aksi Verifikasi (hanya tampil kalau masih menunggu) --}}
    @if($statusLower === 'menunggu')
        <div class="bg-white rounded-2xl shadow-sm px-5 py-5">

            <div class="mb-4">
                <label class="block text-xs text-gray-400 mb-1.5">Tanggal Persetujuan</label>
                <input type="date" id="tanggal_persetujuan_input" value="{{ now()->format('Y-m-d') }}"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-500">
                <p class="text-xs text-gray-400 mt-1.5">Isi sesuai tanggal keputusan sebenarnya jika berbeda dari hari ini.</p>
            </div>

            <div class="flex flex-col gap-3">

                {{-- Tombol Setuju --}}
                <form action="{{ route('pengguna.verifikasi.approve', $pengajuan->id) }}" method="POST" id="form-approve">
                    @csrf
                    <input type="hidden" name="tanggal_persetujuan" id="tanggal_persetujuan_approve">
                    <button type="submit"
                            class="w-full text-center bg-sky-500 hover:bg-sky-600 text-white font-semibold rounded-2xl py-3.5 transition">
                        Setujui
                    </button>
                </form>

                {{-- Tombol Tolak --}}
                <button type="button" onclick="document.getElementById('modal-tolak').classList.remove('hidden')"
                        class="w-full text-center border-2 border-red-200 text-red-500 font-semibold rounded-2xl py-3.5">
                    Tolak
                </button>

            </div>

        </div>
    @endif

</div>

{{-- Modal Tolak --}}
@if($statusLower === 'menunggu')
    <div id="modal-tolak" class="hidden fixed inset-0 bg-black/40 flex items-end sm:items-center justify-center z-50">
        <div class="bg-white rounded-t-2xl sm:rounded-2xl w-full max-w-sm p-5">
            <h3 class="text-base font-semibold text-gray-900 mb-1">Tolak Pengajuan</h3>
            <p class="text-sm text-gray-500 mb-4">Berikan alasan penolakan untuk pegawai.</p>

            <form action="{{ route('pengguna.verifikasi.reject', $pengajuan->id) }}" method="POST" id="form-reject">
                @csrf
                <input type="hidden" name="tanggal_persetujuan" id="tanggal_persetujuan_reject">

                <textarea name="catatan" rows="3" required placeholder="Tulis alasan penolakan..."
                          class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 resize-none focus:outline-none focus:ring-2 focus:ring-red-400"></textarea>

                <div class="flex items-center gap-3 mt-4">
                    <button type="button" onclick="document.getElementById('modal-tolak').classList.add('hidden')"
                            class="flex-1 text-center border-2 border-gray-200 text-gray-600 font-semibold rounded-xl py-2.5">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 text-center bg-red-500 text-white font-semibold rounded-xl py-2.5">
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const tglInput = document.getElementById('tanggal_persetujuan_input');
        const formApprove = document.getElementById('form-approve');
        const formReject = document.getElementById('form-reject');

        formApprove.addEventListener('submit', function () {
            document.getElementById('tanggal_persetujuan_approve').value = tglInput.value;
        });

        formReject.addEventListener('submit', function () {
            document.getElementById('tanggal_persetujuan_reject').value = tglInput.value;
        });
    </script>
@endif

@endsection