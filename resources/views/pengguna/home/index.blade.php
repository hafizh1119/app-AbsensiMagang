{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Home')

@section('content')
{{--
    Contoh variabel status, ganti dengan data asli dari controller kamu:
    $sudahAbsenMasuk = true/false;
    $terlambat = true/false;
--}}
@php
    $sudahAbsenMasuk = $sudahAbsenMasuk ?? false;
    $terlambat = $terlambat ?? false;
@endphp

<div class="min-h-screen bg-gray-50 pb-24">

    {{-- Header --}}
    <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 px-5 pt-5 pb-16 rounded-b-3xl relative overflow-hidden">
        {{-- Dekorasi lingkaran --}}
        <div class="absolute -top-8 -right-10 w-32 h-32 rounded-full bg-white/10"></div>
        <div class="absolute top-16 -right-4 w-16 h-16 rounded-full bg-white/10"></div>
        <div class="relative flex items-center gap-3 mt-5">
            <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-lg ring-2 ring-white/40 shrink-0">
                {{-- Ganti dengan foto profil pengguna kalau ada --}}
                @if(auth()->check() && auth()->user()->avatar)
                    <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="Foto Profil" class="w-full h-full rounded-full object-cover">
                @else
                    {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'B' }}
                @endif
            </div>
            <div>
                <p class="text-emerald-100 text-sm">Selamat pagi,</p>
                <h1 class="text-white text-xl font-bold leading-tight">{{ auth()->user()->name ?? 'Budi Santoso' }}</h1>
                <p class="text-emerald-100 text-xs">{{ auth()->user()->jabatan ?? 'Staf Keuangan' }}</p>
            </div>
        </div>
    </div>

    {{-- Card Absensi Hari Ini (mengambang di atas header) --}}
    <div class="mx-4 -mt-10 relative">
        <div class="bg-white rounded-2xl shadow-lg shadow-emerald-900/10 border border-gray-100 p-5 flex items-center justify-between overflow-hidden">
            <div>
                <p class="text-xs text-gray-400">Hari ini</p>
                <p class="text-sm font-semibold text-gray-700 mt-0.5">{{ now()->translatedFormat('l, d F Y') }}</p>
                <p class="text-2xl font-extrabold text-gray-800 mt-1 tabular-nums" id="live-clock">{{ now()->format('H:i:s') }}</p>

                @if(!$sudahAbsenMasuk)
                    <span class="inline-block mt-2 text-xs font-semibold text-orange-600 bg-orange-50 px-3 py-1 rounded-full">
                        Belum Absen Masuk
                    </span>
                @elseif($terlambat)
                    <span class="inline-block mt-2 text-xs font-semibold text-red-600 bg-red-50 px-3 py-1 rounded-full">
                        Terlambat
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 mt-2 text-xs font-semibold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Datang Tepat Waktu
                    </span>
                @endif
            </div>

            {{-- Ilustrasi kantor sederhana --}}
            <svg class="w-20 h-20 shrink-0" viewBox="0 0 100 100" fill="none">
                <rect x="34" y="20" width="34" height="60" rx="2" fill="#D1FAE5"/>
                <rect x="40" y="28" width="6" height="6" fill="#10B981"/>
                <rect x="50" y="28" width="6" height="6" fill="#10B981"/>
                <rect x="60" y="28" width="6" height="6" fill="#10B981"/>
                <rect x="40" y="40" width="6" height="6" fill="#10B981"/>
                <rect x="50" y="40" width="6" height="6" fill="#10B981"/>
                <rect x="60" y="40" width="6" height="6" fill="#10B981"/>
                <rect x="40" y="52" width="6" height="6" fill="#10B981"/>
                <rect x="50" y="52" width="6" height="6" fill="#10B981"/>
                <rect x="60" y="52" width="6" height="6" fill="#10B981"/>
                <rect x="46" y="66" width="12" height="14" fill="#059669"/>
                <path d="M32 20l19-8 19 8" stroke="#059669" stroke-width="2" fill="none"/>
                <circle cx="18" cy="70" r="8" fill="#A7F3D0"/>
                <circle cx="80" cy="65" r="10" fill="#A7F3D0"/>
                <rect x="14" y="70" width="8" height="12" fill="#6EE7B7"/>
                <rect x="75" y="65" width="10" height="17" fill="#6EE7B7"/>
            </svg>
        </div>
    </div>

    {{-- Menu Cepat --}}
    <div class="mx-4 mt-6">
        <h2 class="text-sm font-bold text-gray-700 mb-3">Menu Cepat</h2>
        <div class="grid grid-cols-3 gap-3">

            {{-- Riwayat --}}
            <a href="{{ route('pengguna.riwayat') }}"
            class="bg-white rounded-2xl shadow-sm border border-gray-100 py-4 px-2 flex flex-col items-center gap-2 hover:shadow-md hover:-translate-y-0.5 transition duration-200">

                <div class="w-11 h-11 rounded-full bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>

                <span class="text-xs font-semibold text-gray-700">
                    Riwayat Absensi
                </span>

            </a>

            {{-- Jadwal Kerja --}}
            <a href="{{ route('pengguna.jadwal') }}"
            class="bg-white rounded-2xl shadow-sm border border-gray-100 py-4 px-2 flex flex-col items-center gap-2 hover:shadow-md hover:-translate-y-0.5 transition duration-200">

                <div class="w-11 h-11 rounded-full bg-amber-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>

                <span class="text-xs font-semibold text-gray-700">
                    Jadwal Kerja
                </span>

            </a>

            {{-- Pengajuan Izin --}}
            <a href="{{ route('pengguna.izin.riwayat') }}"
            class="bg-white rounded-2xl shadow-sm border border-gray-100 py-4 px-2 flex flex-col items-center gap-2 hover:shadow-md hover:-translate-y-0.5 transition duration-200">
                <div class="w-11 h-11 rounded-full bg-emerald-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-700">Riwayat Izin</span>
            </a>

        </div>
    </div>

    {{-- Kehadiran Hari Ini --}}
    <div class="bg-white mx-4 mt-5 rounded-2xl shadow-sm border border-gray-100 p-5">

        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-bold text-gray-700">
                Kehadiran Hari Ini
            </h2>

            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-[11px] font-semibold">
                Belum Absen
            </span>
        </div>

        <div class="grid grid-cols-3 gap-2">

        {{-- Jam Masuk --}}
        <div class="bg-gray-50 rounded-xl py-3 text-center">
            <p class="text-[11px] text-gray-500">
                Masuk
            </p>

            <p class="text-base font-bold text-gray-800 mt-1">
                --
            </p>
        </div>

        {{-- Jam Pulang --}}
        <div class="bg-gray-50 rounded-xl py-3 text-center">
            <p class="text-[11px] text-gray-500">
                Pulang
            </p>

            <p class="text-base font-bold text-gray-800 mt-1">
                --
            </p>
        </div>

        {{-- Durasi --}}
        <div class="bg-blue-50 rounded-xl py-3 text-center">
            <p class="text-[11px] text-gray-500">
                Durasi
            </p>

            <p class="text-base font-bold text-blue-600 mt-1">
                --
            </p>
        </div>

    </div>

</div>

    {{-- Status Bulan Ini --}}
    <div class="bg-white mx-4 mt-5 rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-bold text-gray-700">Status Bulan Ini</h2>
            <a href="#" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">Lihat Detail</a>
        </div>
        <div class="grid grid-cols-4 gap-2">
            <div class="text-center bg-gray-50 rounded-xl py-3">
                <p class="text-lg font-extrabold text-emerald-600">20</p>
                <p class="text-[11px] text-gray-500 mt-0.5">Hadir</p>
            </div>
            <div class="text-center bg-gray-50 rounded-xl py-3">
                <p class="text-lg font-extrabold text-amber-500">2</p>
                <p class="text-[11px] text-gray-500 mt-0.5">Terlambat</p>
            </div>
            <div class="text-center bg-gray-50 rounded-xl py-3">
                <p class="text-lg font-extrabold text-blue-500">1</p>
                <p class="text-[11px] text-gray-500 mt-0.5">Izin</p>
            </div>
            <div class="text-center bg-gray-50 rounded-xl py-3">
                <p class="text-lg font-extrabold text-rose-500">0</p>
                <p class="text-[11px] text-gray-500 mt-0.5">Sakit</p>
            </div>
        </div>
    </div>

    {{-- Spacer untuk bottom nav --}}
    <div class="h-8"></div>
</div>

<script>
    // Jam berjalan otomatis, hapus kalau waktunya mau full dari server
    setInterval(() => {
        const el = document.getElementById('live-clock');
        if (el) el.textContent = new Date().toLocaleTimeString('id-ID', { hour12: false });
    }, 1000);
</script>

@include('pengguna.components.menu')

@endsection