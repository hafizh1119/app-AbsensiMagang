@extends('layouts.app')

@section('title', 'Detail Pengajuan Izin')

@section('content')

@include('pengguna.components.header', ['title' => 'Detail Pengajuan Izin'])

<div class="bg-gray-50 px-5 py-5">

    @php
        $statusLower = strtolower($pengajuan->status);
    @endphp

    {{-- Status Badge --}}
    <div class="mb-4">
        @if ($statusLower === 'disetujui')
            <span class="inline-block px-3 py-1.5 rounded-full bg-sky-50 text-sky-600 text-xs font-semibold">Disetujui</span>
        @elseif ($statusLower === 'ditolak')
            <span class="inline-block px-3 py-1.5 rounded-full bg-red-50 text-red-500 text-xs font-semibold">Ditolak</span>
        @else
            <span class="inline-block px-3 py-1.5 rounded-full bg-amber-50 text-amber-600 text-xs font-semibold">Menunggu Persetujuan</span>
        @endif
    </div>

    {{-- Kode & Tanggal Pengajuan --}}
    <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $nomorPengajuan }}</h2>
    <p class="text-sm text-gray-400 mb-5">
        Diajukan pada {{ $pengajuan->tanggal_pengajuan->translatedFormat('d M Y, H:i') }}
    </p>

    {{-- Detail Card --}}
    <div class="bg-white rounded-2xl shadow-sm divide-y divide-gray-100 mb-5">

        <div class="flex items-start justify-between px-5 py-4">
            <span class="text-sm text-gray-500">Jenis Izin</span>
            <span class="text-sm text-gray-900 font-semibold text-right">{{ $pengajuan->jenisIzin->nama_izin ?? '-' }}</span>
        </div>

        <div class="flex items-start justify-between px-5 py-4">
            <span class="text-sm text-gray-500">Tanggal</span>
            <div class="text-right">
                <p class="text-sm text-gray-900 font-medium">
                    {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->translatedFormat('d M Y') }}
                    @if($pengajuan->tanggal_mulai != $pengajuan->tanggal_selesai)
                        - {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->translatedFormat('d M Y') }}
                    @endif
                </p>
                <p class="text-xs text-gray-400 mt-0.5">({{ $pengajuan->durasi }} hari)</p>
            </div>
        </div>

        <div class="flex items-start justify-between px-5 py-4 gap-4">
            <span class="text-sm text-gray-500 shrink-0">Keterangan</span>
            <span class="text-sm text-gray-800 text-right leading-relaxed">{{ $pengajuan->alasan }}</span>
        </div>

        @if ($statusLower === 'disetujui' || $statusLower === 'ditolak')
        <div class="flex items-start justify-between px-5 py-4">
            <span class="text-sm text-gray-500">
                {{ $statusLower === 'disetujui' ? 'Disetujui Oleh' : 'Ditolak Oleh' }}
            </span>
            <div class="text-right">
                <p class="text-sm text-gray-900 font-semibold">
                    {{ $pengajuan->penyetuju->nama ?? '-' }}
                </p>
                @if($pengajuan->tanggal_persetujuan)
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ $pengajuan->tanggal_persetujuan->translatedFormat('d M Y, H:i') }}
                    </p>
                @endif
            </div>
        </div>
        @endif

    </div>

    {{-- Lampiran --}}
    @if ($pengajuan->lampiran)
        <div class="mb-5">
            <p class="text-sm font-semibold text-gray-800 mb-2">Lampiran</p>
            <div class="flex items-center justify-between bg-white rounded-2xl shadow-sm px-5 py-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="shrink-0 w-9 h-9 rounded-lg bg-sky-50 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-sky-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <path d="M14 2v6h6"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">
                            {{ $pengajuan->lampiran_nama_asli ?? basename($pengajuan->lampiran) }}
                        </p>
                    </div>
                </div>
                <a href="{{ asset($pengajuan->lampiran) }}" target="_blank" class="shrink-0 text-sky-600 hover:text-sky-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 3v12"></path>
                        <path d="M7 10l5 5 5-5"></path>
                        <path d="M5 21h14"></path>
                    </svg>
                </a>
            </div>
        </div>
    @else
        <div class="mb-5">
            <p class="text-sm font-semibold text-gray-800 mb-2">Lampiran</p>
            <p class="text-sm text-gray-400">Tidak ada lampiran</p>
        </div>
    @endif

    {{-- Catatan Tambahan --}}
    <div class="mb-5">
        <p class="text-sm font-semibold text-gray-800 mb-2">Catatan Tambahan</p>
        <p class="text-sm text-gray-400">{{ $pengajuan->catatan_tambahan ?? '-' }}</p>
    </div>

    {{-- Info Banner --}}
    @if ($statusLower === 'menunggu')
        <div class="flex items-center gap-2 bg-amber-50 rounded-2xl px-4 py-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-amber-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="9"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span class="text-sm text-amber-700">Pengajuan Anda sedang dalam proses persetujuan.</span>
        </div>
    @elseif ($statusLower === 'disetujui')
        <div class="flex items-center gap-2 bg-sky-50 rounded-2xl px-4 py-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-sky-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="9"></circle>
                <polyline points="8 12 11 15 16 9"></polyline>
            </svg>
            <span class="text-sm text-sky-700">Pengajuan Anda telah disetujui.</span>
        </div>
    @else
        <div class="flex items-center gap-2 bg-red-50 rounded-2xl px-4 py-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-red-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="9"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            <span class="text-sm text-red-600">Pengajuan Anda ditolak.</span>
        </div>
    @endif

</div>

@endsection