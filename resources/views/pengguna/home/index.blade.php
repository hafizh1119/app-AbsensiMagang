{{-- resources/views/pengguna/home/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Home')

@section('content')
@php
    // Set locale ke Indonesia untuk Carbon
    Carbon\Carbon::setLocale('id');
    setlocale(LC_TIME, 'id_ID');
    
    $sudahAbsenMasuk = $sudahAbsenMasuk ?? false;
    $terlambat = $terlambat ?? false;
    
    // Gunakan waktu lokal (Asia/Jakarta)
    $waktuSekarang = now('Asia/Jakarta');
    
    // Menentukan sapaan berdasarkan jam (waktu lokal)
    $jam = $waktuSekarang->format('H');
    if ($jam >= 5 && $jam < 11) {
        $sapaan = 'pagi';
    } elseif ($jam >= 11 && $jam < 15) {
        $sapaan = 'siang';
    } elseif ($jam >= 15 && $jam < 18) {
        $sapaan = 'sore';
    } else {
        $sapaan = 'malam';
    }
@endphp

<div class="min-h-screen bg-gray-50 pb-24">

    {{-- Header --}}
    <div class="bg-gradient-to-br from-sky-400 via-blue-500 to-indigo-600 px-5 pt-5 pb-16 rounded-b-3xl relative overflow-hidden">
        {{-- Dekorasi lingkaran --}}
        <div class="absolute -top-8 -right-10 w-32 h-32 rounded-full bg-white/15"></div>
        <div class="absolute top-16 -right-4 w-16 h-16 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-10 -left-8 w-28 h-28 rounded-full bg-white/10"></div>
        <div class="relative flex items-center gap-3 mt-5">
            {{-- Header foto & nama --}}
            <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-lg ring-2 ring-white/40 shrink-0 backdrop-blur-sm">
                @if($user->foto)
                    <img src="{{ asset('foto-pegawai/'.$user->foto) }}" alt="Foto Profil" class="w-full h-full rounded-full object-cover">
                @else
                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                @endif
            </div>
            <div>
                <p class="text-sky-100 text-sm">Selamat {{ $sapaan }},</p>
                <h1 class="text-white text-xl font-bold leading-tight">{{ $user->nama }}</h1>
                <p class="text-sky-100 text-xs">{{ $user->unit->nama_unit ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Card Absensi Hari Ini (mengambang di atas header) --}}
    <div class="mx-4 -mt-10 relative">
        <div class="bg-white rounded-2xl shadow-lg shadow-blue-900/10 border border-gray-100 p-5 flex items-center justify-between overflow-hidden">
            <div>
                <p class="text-xs text-gray-400">Hari ini</p>
                <p class="text-sm font-semibold text-gray-700 mt-0.5">{{ $waktuSekarang->translatedFormat('l, d F Y') }}</p>
                <p class="text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-blue-600 mt-1 tabular-nums" id="live-clock">{{ $waktuSekarang->format('H:i:s') }}</p>
                @if($statusBadge === 'belum_absen')
                    <span class="inline-block mt-2 text-xs font-semibold text-orange-600 bg-orange-50 px-3 py-1 rounded-full">
                        Belum Presensi Masuk
                    </span>
                @elseif($statusBadge === 'terlambat')
                    <span class="inline-block mt-2 text-xs font-semibold text-red-600 bg-red-50 px-3 py-1 rounded-full">
                        Terlambat
                    </span>
                @elseif($statusBadge === 'izin')
                    <span class="inline-flex items-center gap-1 mt-2 text-xs font-semibold text-sky-600 bg-sky-50 px-3 py-1 rounded-full">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ $labelBadge }}
                    </span>
                @elseif($statusBadge === 'cuti')
                    <span class="inline-flex items-center gap-1 mt-2 text-xs font-semibold text-violet-600 bg-violet-50 px-3 py-1 rounded-full">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ $labelBadge }}
                    </span>
                @elseif($statusBadge === 'sakit')
                    <span class="inline-flex items-center gap-1 mt-2 text-xs font-semibold text-amber-600 bg-amber-50 px-3 py-1 rounded-full">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/>
                        </svg>
                        {{ $labelBadge }}
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 mt-2 text-xs font-semibold text-white bg-gradient-to-r from-sky-500 to-blue-600 px-3 py-1 rounded-full shadow-sm shadow-blue-500/30">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Datang Tepat Waktu
                    </span>
                @endif
            </div>

            {{-- Ilustrasi kantor sederhana --}}
            <svg class="w-20 h-20 shrink-0" viewBox="0 0 100 100" fill="none">
                <defs>
                    <linearGradient id="bldgGrad" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#38BDF8"/>
                        <stop offset="100%" stop-color="#2563EB"/>
                    </linearGradient>
                </defs>
                <rect x="34" y="20" width="34" height="60" rx="2" fill="#E0F2FE"/>
                <rect x="40" y="28" width="6" height="6" fill="url(#bldgGrad)"/>
                <rect x="50" y="28" width="6" height="6" fill="url(#bldgGrad)"/>
                <rect x="60" y="28" width="6" height="6" fill="url(#bldgGrad)"/>
                <rect x="40" y="40" width="6" height="6" fill="url(#bldgGrad)"/>
                <rect x="50" y="40" width="6" height="6" fill="url(#bldgGrad)"/>
                <rect x="60" y="40" width="6" height="6" fill="url(#bldgGrad)"/>
                <rect x="40" y="52" width="6" height="6" fill="url(#bldgGrad)"/>
                <rect x="50" y="52" width="6" height="6" fill="url(#bldgGrad)"/>
                <rect x="60" y="52" width="6" height="6" fill="url(#bldgGrad)"/>
                <rect x="46" y="66" width="12" height="14" fill="#2563EB"/>
                <path d="M32 20l19-8 19 8" stroke="#2563EB" stroke-width="2" fill="none"/>
                <circle cx="18" cy="70" r="8" fill="#BAE6FD"/>
                <circle cx="80" cy="65" r="10" fill="#BAE6FD"/>
                <rect x="14" y="70" width="8" height="12" fill="#7DD3FC"/>
                <rect x="75" y="65" width="10" height="17" fill="#7DD3FC"/>
            </svg>
        </div>
    </div>

    {{-- Menu Cepat --}}
    <div class="mx-4 mt-6">
        <h2 class="text-sm font-bold text-gray-700 mb-4">Menu Cepat</h2>

        <div class="grid {{ $user->role === 'admin_unit' ? 'grid-cols-3' : 'grid-cols-2' }} gap-6">

            {{-- Riwayat Absensi --}}
            <a href="{{ route('pengguna.riwayat') }}"
            class="flex flex-col items-center gap-2 group">

                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center shadow-md shadow-blue-500/30 transition-all duration-200 group-hover:shadow-lg group-hover:shadow-blue-500/40 group-hover:scale-105">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>

                <span class="text-sm font-semibold text-gray-700 text-center">
                    Riwayat Presensi
                </span>
            </a>

            {{-- Riwayat Izin --}}
            <a href="{{ route('pengguna.izin.riwayat') }}"
            class="flex flex-col items-center gap-2 group">

                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-md shadow-indigo-500/30 transition-all duration-200 group-hover:shadow-lg group-hover:shadow-indigo-500/40 group-hover:scale-105">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                <span class="text-sm font-semibold text-gray-700 text-center">
                    Riwayat Izin
                </span>
            </a>

            {{-- Verifikasi Izin --}}
            @if($user->role === 'admin_unit')
            <a href="{{ route('pengguna.verifikasi.index') }}"
            class="flex flex-col items-center gap-2 group">

                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-cyan-400 to-sky-600 flex items-center justify-center shadow-md shadow-sky-500/30 transition-all duration-200 group-hover:shadow-lg group-hover:shadow-sky-500/40 group-hover:scale-105">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                <span class="text-sm font-semibold text-gray-700 text-center">
                    Verifikasi Izin
                </span>
            </a>
            @endif

        </div>
    </div>

    {{-- Kehadiran Hari Ini --}}
    <div class="bg-white mx-4 mt-5 rounded-2xl shadow-sm border border-gray-100 p-5">

        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-bold text-gray-700">
                Kehadiran Hari Ini
            </h2>

            @if(!$sudahAbsenMasuk)
                <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-[11px] font-semibold">
                    Belum Presensi
                </span>
            @elseif($terlambat)
                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-[11px] font-semibold">
                    Terlambat
                </span>
            @else
                <span class="px-3 py-1 rounded-full bg-gradient-to-r from-sky-500 to-blue-600 text-white text-[11px] font-semibold shadow-sm shadow-blue-500/30">
                    Tepat Waktu
                </span>
            @endif
        </div>

        <div class="grid grid-cols-3 gap-2">

            {{-- Jam Masuk --}}
            <div class="bg-sky-50 rounded-xl py-3 text-center">
                <p class="text-[11px] text-gray-500">Masuk</p>
                <p class="text-base font-bold text-gray-800 mt-1">
                    {{ $absensiHariIni?->jam_masuk ? \Carbon\Carbon::parse($absensiHariIni->jam_masuk)->format('H:i') : '--' }}
                </p>
            </div>

            {{-- Jam Pulang --}}
            <div class="bg-sky-50 rounded-xl py-3 text-center">
                <p class="text-[11px] text-gray-500">Pulang</p>
                <p class="text-base font-bold text-gray-800 mt-1">
                    {{ $absensiHariIni?->jam_pulang ? \Carbon\Carbon::parse($absensiHariIni->jam_pulang)->format('H:i') : '--' }}
                </p>
            </div>

            {{-- Durasi --}}
            <div class="bg-gradient-to-br from-sky-500 to-blue-600 rounded-xl py-3 text-center shadow-sm shadow-blue-500/20">
                <p class="text-[11px] text-sky-100">Durasi</p>
                <p class="text-base font-bold text-white mt-1">
                    {{ $durasi ?? '--' }}
                </p>
            </div>

        </div>

    </div>

    {{-- Status Bulan Ini --}}
    <div class="bg-white mx-4 mt-5 rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-bold text-gray-700">Status Bulan Ini</h2>
            <a href="{{ route('pengguna.riwayat') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline">Lihat Detail</a>
        </div>
        <div class="grid grid-cols-4 gap-2">
            <div class="text-center bg-sky-50 rounded-xl py-3">
                <p class="text-lg font-extrabold text-blue-700">{{ $rekapBulanIni->hadir ?? 0 }}</p>
                <p class="text-[11px] text-gray-500 mt-0.5">Hadir</p>
            </div>
            <div class="text-center bg-sky-50 rounded-xl py-3">
                <p class="text-lg font-extrabold text-amber-500">{{ $rekapBulanIni->terlambat ?? 0 }}</p>
                <p class="text-[11px] text-gray-500 mt-0.5">Terlambat</p>
            </div>
            <div class="text-center bg-sky-50 rounded-xl py-3">
                <p class="text-lg font-extrabold text-sky-500">{{ $rekapBulanIni->izin ?? 0 }}</p>
                <p class="text-[11px] text-gray-500 mt-0.5">Izin</p>
            </div>
            <div class="text-center bg-sky-50 rounded-xl py-3">
                <p class="text-lg font-extrabold text-rose-500">{{ $rekapBulanIni->sakit ?? 0 }}</p>
                <p class="text-[11px] text-gray-500 mt-0.5">Sakit</p>
            </div>
        </div>
    </div>

    {{-- Spacer untuk bottom nav --}}
    <div class="h-8"></div>
</div>

<script>
    // Jam berjalan otomatis dengan locale Indonesia
    setInterval(() => {
        const el = document.getElementById('live-clock');
        if (el) {
            const now = new Date();
            // Konversi ke WIB (UTC+7)
            const wibTime = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Jakarta' }));
            el.textContent = wibTime.toLocaleTimeString('id-ID', { hour12: false });
        }
    }, 1000);
</script>

@include('pengguna.components.menu')

@endsection