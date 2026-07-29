@extends('layouts.app')

@section('title', 'Pengajuan Berhasil')

@section('content')

<div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center px-6 text-center">

    {{-- Icon Sukses --}}
    <div class="w-20 h-20 rounded-full bg-emerald-50 flex items-center justify-center mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 6 9 17l-5-5"></path>
        </svg>
    </div>

    <h2 class="text-xl font-bold text-gray-900 mb-1">Pengajuan Berhasil Dikirim!</h2>
    <p class="text-sm text-gray-500 mb-6">Pengajuan izin Anda sedang menunggu persetujuan.</p>

    {{-- Nomor Pengajuan --}}
    <div class="bg-white rounded-2xl shadow-sm px-6 py-4 mb-8 w-full max-w-sm">
        <p class="text-xs text-gray-400 mb-1">Nomor Pengajuan</p>
        <p class="text-gray-900 font-bold text-lg">{{ $nomorPengajuan }}</p>
    </div>

    {{-- Tombol Aksi --}}
    <div class="w-full max-w-sm space-y-3">
        <a href="{{ route('pengguna.izin.detail', $pengajuan->id) }}"
           class="block w-full text-center bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-2xl py-3.5 shadow-sm transition">
            Lihat Detail
        </a>
        <a href="{{ route('pengguna.home') }}"
           class="block w-full text-center border-2 border-emerald-600 text-emerald-600 font-semibold rounded-2xl py-3.5">
            Kembali ke Beranda
        </a>
    </div>

</div>

@endsection