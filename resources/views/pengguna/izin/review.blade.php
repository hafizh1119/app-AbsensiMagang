@extends('layouts.app')

@section('title', 'Review Pengajuan Izin')

@section('content')

@include('pengguna.components.header', ['title' => 'Review Pengajuan Izin'])

<div class="min-h-screen bg-gray-50 pb-10">

    {{-- Step Indicator --}}
    <div class="flex items-center justify-center gap-3 px-5 pt-6 pb-5">
        <div class="w-8 h-8 rounded-full bg-sky-500 text-white flex items-center justify-center text-sm font-semibold">1</div>
        <div class="w-10 border-t-2 border-dashed border-gray-300"></div>
        <div class="w-8 h-8 rounded-full bg-sky-500 text-white flex items-center justify-center text-sm font-semibold">2</div>
        <div class="w-10 border-t-2 border-dashed border-gray-300"></div>
        <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-sm font-semibold">3</div>
    </div>

    <div class="px-5">

    <h2 class="text-xl font-bold text-gray-900 mb-1">Review Pengajuan Izin</h2>
    <p class="text-sm text-gray-500 mb-5">Periksa kembali detail pengajuan izin Anda</p>

    <div class="bg-white rounded-2xl shadow-sm divide-y divide-gray-100">

        {{-- Jenis Izin --}}
        <div class="flex items-center gap-3 px-5 py-4">
            <div class="shrink-0 w-10 h-10 rounded-full bg-sky-50 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-sky-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 1 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path>
                    <polyline points="7.5 12 10 12 11 10 13 14 14 12 16.5 12"></polyline>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Jenis Izin</p>
                <p class="text-gray-900 font-semibold">{{ $draft['jenis_izin_nama'] }}</p>
            </div>
        </div>

        {{-- Ditujukan Kepada --}}
        <div class="px-5 py-4">
            <p class="text-xs text-gray-400 mb-1">Ditujukan Kepada</p>
            <p class="text-gray-800 font-medium">
                {{ $atasan->nama ?? '-' }}
                <span class="text-gray-400 font-normal">- {{ $atasan->jabatan ?? '-' }}</span>
            </p>
        </div>

        {{-- Tanggal --}}
        <div class="px-5 py-4">
            <p class="text-xs text-gray-400 mb-1">Tanggal</p>
            <p class="text-sky-600 font-semibold mb-1">Izin</p>
            <p class="text-gray-800">
                {{ \Carbon\Carbon::parse($draft['tanggal_mulai'])->translatedFormat('d F Y') }}
                @if($draft['tanggal_mulai'] !== $draft['tanggal_selesai'])
                    - {{ \Carbon\Carbon::parse($draft['tanggal_selesai'])->translatedFormat('d F Y') }}
                @endif
            </p>
            <p class="text-gray-400 text-sm">({{ $draft['durasi'] }} hari)</p>
        </div>

        {{-- Keterangan --}}
        <div class="px-5 py-4">
            <p class="text-xs text-gray-400 mb-1">Keterangan</p>
            <p class="text-gray-800 leading-relaxed">{{ $draft['alasan'] }}</p>
        </div>

        {{-- Lampiran --}}
        <div class="px-5 py-4">
            <p class="text-xs text-gray-400 mb-1">Lampiran</p>
            @if($draft['lampiran'])
                <p class="text-gray-800 font-medium">{{ $draft['lampiran_nama_asli'] }}</p>
                <a href="{{ asset($draft['lampiran']) }}" target="_blank" class="text-sky-600 text-sm hover:underline">Lihat File</a>
            @else
                <p class="text-gray-400">Tidak ada lampiran</p>
            @endif
        </div>

    </div>

    </div>

    {{-- Tombol Aksi --}}
    <div class="px-5 mt-5 mb-20">
        <div class="flex items-center gap-3">
            <a href="{{ route('pengguna.izin.index') }}"
               class="flex-1 text-center border-2 border-sky-500 text-sky-500 font-semibold rounded-2xl py-3.5 hover:bg-sky-50 transition">
                Kembali
            </a>

            <form action="{{ route('pengguna.izin.simpan') }}" method="POST" class="flex-1">
                @csrf
                <button type="submit"
                        class="block w-full text-center bg-sky-500 hover:bg-sky-600 text-white font-semibold rounded-2xl py-3.5 shadow-sm transition">
                    Kirim Pengajuan
                </button>
            </form>
        </div>
    </div>

</div>

@endsection