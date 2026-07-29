@extends('layouts.app')

@section('title', 'Detail Pengajuan Izin')

@section('content')

@include('pengguna.components.header', ['title' => 'Detail Pengajuan Izin'])

<div class="min-h-screen bg-gray-50 pb-10">

    @php
        $statusLower = strtolower($pengajuan->status);

        // Peta warna & teks berdasarkan status
        $statusMap = [
            'menunggu' => [
                'label'      => 'Menunggu Persetujuan',
                'badge'      => 'bg-amber-50 text-amber-600',
                'note_bg'    => 'bg-amber-50 text-amber-700',
                'note_icon'  => 'text-amber-500',
                'note_text'  => 'Pengajuan Anda sedang dalam proses persetujuan.',
            ],
            'disetujui' => [
                'label'      => 'Disetujui',
                'badge'      => 'bg-emerald-50 text-emerald-600',
                'note_bg'    => 'bg-emerald-50 text-emerald-700',
                'note_icon'  => 'text-emerald-500',
                'note_text'  => 'Pengajuan Anda telah disetujui.',
            ],
            'ditolak' => [
                'label'      => 'Ditolak',
                'badge'      => 'bg-red-50 text-red-500',
                'note_bg'    => 'bg-red-50 text-red-600',
                'note_icon'  => 'text-red-400',
                'note_text'  => 'Pengajuan Anda ditolak.',
            ],
        ];

        $status = $statusMap[$statusLower] ?? $statusMap['menunggu'];
    @endphp

    <div class="px-5 pt-5">

        {{-- Badge Status --}}
        <div class="mb-4">
            <span class="inline-block px-3 py-1.5 rounded-full text-xs font-semibold {{ $status['badge'] }}">
                {{ $status['label'] }}
            </span>
        </div>

        {{-- Kode & Tanggal Pengajuan --}}
        <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $nomorPengajuan }}</h2>
        <p class="text-sm text-gray-400 mb-5">
            Diajukan pada {{ $pengajuan->tanggal_pengajuan->translatedFormat('d M Y, H:i') }}
        </p>

        {{-- Detail Card --}}
        <div class="bg-white rounded-2xl shadow-sm divide-y divide-gray-100">

            {{-- Jenis Izin --}}
            <div class="flex items-center justify-between px-5 py-4">
                <p class="text-sm text-gray-500">Jenis Izin</p>
                <p class="text-gray-900 font-semibold">{{ $pengajuan->jenisIzin->nama_izin ?? '-' }}</p>
            </div>

            {{-- Tanggal --}}
            <div class="flex items-start justify-between px-5 py-4">
                <p class="text-sm text-gray-500">Tanggal</p>
                <div class="text-right">
                    <p class="text-gray-900 font-semibold">
                        {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->translatedFormat('d M Y') }}
                        @if($pengajuan->tanggal_mulai != $pengajuan->tanggal_selesai)
                            - {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->translatedFormat('d M Y') }}
                        @endif
                    </p>
                    <p class="text-sm text-gray-400">({{ $pengajuan->durasi }} hari)</p>
                </div>
            </div>

            {{-- Keterangan --}}
            <div class="px-5 py-4">
                <p class="text-sm text-gray-500 mb-1.5">Keterangan</p>
                <p class="text-gray-800 text-right leading-relaxed">{{ $pengajuan->alasan }}</p>
            </div>

            {{-- Lampiran --}}
            <div class="px-5 py-4">
                <p class="text-sm text-gray-500 mb-2">Lampiran</p>

                @if($pengajuan->lampiran)
                    <a href="{{ asset($pengajuan->lampiran) }}" target="_blank"
                       class="flex items-center justify-between bg-gray-50 rounded-xl px-4 py-3 hover:bg-gray-100 transition">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="shrink-0 w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 3v12"></path>
                            <path d="M7 11l5 5 5-5"></path>
                            <path d="M5 21h14"></path>
                        </svg>
                    </a>
                @else
                    <p class="text-gray-400">Tidak ada lampiran</p>
                @endif
            </div>

            {{-- Catatan Tambahan --}}
            <div class="px-5 py-4">
                <p class="text-sm text-gray-500 mb-1.5">Catatan Tambahan</p>
                <p class="text-gray-800">{{ $pengajuan->catatan_tambahan ?? '-' }}</p>
            </div>

            {{-- Disetujui Oleh --}}
            @if($statusLower === 'disetujui' || $statusLower === 'ditolak')
            <div class="flex items-start justify-between px-5 py-4">
                <p class="text-sm text-gray-500">
                    {{ $statusLower === 'disetujui' ? 'Disetujui Oleh' : 'Ditolak Oleh' }}
                </p>
                <div class="text-right">
                    <p class="text-gray-900 font-semibold">
                        {{ $pengajuan->penyetuju->name ?? '-' }}
                    </p>
                    @if($pengajuan->tanggal_persetujuan)
                        <p class="text-sm text-gray-400">
                            {{ $pengajuan->tanggal_persetujuan->translatedFormat('d M Y, H:i') }}
                        </p>
                    @endif
                </div>
            </div>
            @endif

        </div>

        {{-- Info Box Status --}}
        <div class="mt-4 flex items-start gap-2.5 rounded-2xl px-4 py-3 {{ $status['note_bg'] }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 mt-0.5 {{ $status['note_icon'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="9"></circle>
                <line x1="12" y1="8" x2="12" y2="13"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <p class="text-sm leading-relaxed">{{ $status['note_text'] }}</p>
        </div>

        {{-- Tombol Kembali --}}
        <div class="mt-6">
            <a href="{{ route('pengguna.izin.riwayat') }}"
               class="block w-full text-center border-2 border-emerald-600 text-emerald-600 font-semibold rounded-2xl py-3.5">
                Kembali ke Riwayat
            </a>
        </div>

    </div>

</div>

@include('pengguna.components.menu')
@endsection