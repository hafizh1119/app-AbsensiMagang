@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

@php
    // Helper agar view tidak error kalau ada route menu cepat yang belum dibuat
    $safeRoute = fn (string $name) => \Illuminate\Support\Facades\Route::has($name) ? route($name) : '#';

    // PENTING: @json() di Blade meng-explode(',') argumennya, jadi TIDAK BOLEH
    // dikasih array literal langsung (koma di dalam array ikut ke-split dan
    // merusak hasil compile). Makanya semua default value array disiapkan
    // dulu di sini sebagai variabel biasa.
    $kehadiranLabelsJs = $kehadiranLabels ?? ['Rab','Kam','Jum','Sab','Sen','Hari ini','Besok'];
    $kehadiranDataJs   = $kehadiranData ?? [99,105,98,112,120,110,null];
    $statusDonutJs     = [$hadirHariIni ?? 0, $izinHariIni ?? 0, $sakitHariIni ?? 0, $alphaHariIni ?? 0];
    $bulananLabelsJs   = $bulananLabels ?? ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    $bulananDataJs     = $bulananData ?? [102,108,110,115,118,120,112,null,null,null,null,null];
@endphp

<div class="flex h-screen overflow-hidden bg-slate-50">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    <div class="h-screen overflow-y-auto shrink-0">
        @include('admin.components.sidebar')
    </div>

    {{-- Kolom kanan: header fixed di atas, konten scroll sendiri --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden">

        <div class="shrink-0 sticky top-0 z-20 bg-white">
            @include('admin.components.header')
        </div>

        {{-- HANYA area ini yang scroll, header & sidebar tidak ikut bergerak --}}
        <main class="flex-1 overflow-y-auto p-6 space-y-6">

            {{-- ================= GREETING ================= --}}
            <div class="fade-up" style="--d:0ms">
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    Selamat Datang, {{ auth()->user()->nama ?? 'Administrator' }}
                    <span class="inline-block animate-wave origin-[70%_70%]">👋</span>
                </h1>
                <p class="text-gray-500 mt-1">Berikut ringkasan aktivitas absensi hari ini.</p>
            </div>

            {{-- ================= STAT CARDS ================= --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

                {{-- Total Pegawai --}}
                <div class="stat-card fade-up" style="--d:60ms">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-sky-50 flex items-center justify-center text-sky-500 text-xl">👥</div>
                        <span class="text-sm text-gray-500">Total Pegawai</span>
                    </div>
                    <div class="mt-3 text-3xl font-bold text-gray-900 counter" data-value="{{ $totalPegawai ?? 0 }}">0</div>
                    <div class="mt-2 text-xs text-sky-500 font-medium flex items-center gap-1">
                        <span>↑</span> {{ $pegawaiBaruBulanIni ?? 0 }} pegawai bulan ini
                    </div>
                </div>

                {{-- Hadir Hari Ini --}}
                <div class="stat-card fade-up" style="--d:120ms">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-sky-50 flex items-center justify-center text-sky-500 text-xl">📅</div>
                        <span class="text-sm text-gray-500">Hadir Hari Ini</span>
                    </div>
                    <div class="mt-3 text-3xl font-bold text-gray-900 counter" data-value="{{ $hadirHariIni ?? 0 }}">0</div>
                    <div class="mt-2">
                        <div class="w-full h-1.5 bg-sky-100 rounded-full overflow-hidden">
                            <div class="h-full bg-sky-500 rounded-full progress-bar" data-width="{{ $persenHadir ?? 0 }}"></div>
                        </div>
                        <span class="text-xs text-sky-500 font-medium">{{ $persenHadir ?? 0 }}% dari total pegawai</span>
                    </div>
                </div>

                {{-- Terlambat Hari Ini --}}
                <div class="stat-card fade-up" style="--d:180ms">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 text-xl">⏰</div>
                        <span class="text-sm text-gray-500">Terlambat Hari Ini</span>
                    </div>
                    <div class="mt-3 text-3xl font-bold text-gray-900 counter" data-value="{{ $terlambatHariIni ?? 0 }}">0</div>
                    <div class="mt-2 text-xs text-amber-500 font-medium">{{ $persenTerlambat ?? 0 }}% dari total pegawai</div>
                </div>

                {{-- Izin / Sakit Hari Ini --}}
                <div class="stat-card fade-up" style="--d:240ms">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-sky-50 flex items-center justify-center text-sky-500 text-xl">🧑‍⚕️</div>
                        <span class="text-sm text-gray-500">Izin / Sakit Hari Ini</span>
                    </div>
                    <div class="mt-3 text-3xl font-bold text-gray-900 counter" data-value="{{ $izinSakitHariIni ?? 0 }}">0</div>
                    <div class="mt-2 text-xs text-sky-500 font-medium">{{ $persenIzinSakit ?? 0 }}% dari total pegawai</div>
                </div>
            </div>

            {{-- ================= CHART ROW ================= --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

                {{-- Line chart --}}
                <div class="stat-card xl:col-span-2 fade-up" x-data="{ open: false, mode: 'minggu' }" style="--d:300ms">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold text-gray-900" x-text="mode === 'minggu' ? 'Kehadiran 7 Hari Terakhir' : 'Kehadiran Bulanan Tahun Ini'"></h2>

                        <div class="relative">
                            <button type="button" @click="open = !open" @click.outside="open = false" class="dropdown-btn">
                                <span x-text="mode === 'minggu' ? '1 Minggu' : '1 Bulan'"></span>
                                <span>▾</span>
                            </button>

                            <div x-show="open" x-cloak
                                class="absolute right-0 mt-2 w-36 bg-white border border-gray-100 rounded-xl shadow-lg py-1 z-10">
                                <button type="button"
                                        @click="mode = 'minggu'; open = false; window.switchKehadiranChart('minggu')"
                                        class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50"
                                        :class="mode === 'minggu' ? 'text-sky-600 font-medium' : 'text-gray-600'">
                                    1 Minggu
                                </button>
                                <button type="button"
                                        @click="mode = 'bulan'; open = false; window.switchKehadiranChart('bulan')"
                                        class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50"
                                        :class="mode === 'bulan' ? 'text-sky-600 font-medium' : 'text-gray-600'">
                                    1 Bulan
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="chartKehadiran"></canvas>
                    </div>
                </div>

                {{-- Donut chart --}}
                <div class="stat-card fade-up flex flex-col" style="--d:360ms">
                    <h2 class="font-semibold text-gray-900 mb-4">Persentase Status Hari Ini</h2>
                    <div class="relative flex-1 flex items-center justify-center min-h-[200px]">
                        <div class="relative w-48 h-48">
                            <canvas id="chartStatus" class="w-full h-full"></canvas>
                            {{-- Center text --}}
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <span class="text-2xl font-bold text-gray-900">{{ $totalPegawai ?? 0 }}</span>
                                <span class="text-xs text-gray-400">Total Pegawai</span>
                            </div>
                        </div>
                    </div>
                    {{-- Legend --}}
                    <div class="grid grid-cols-2 gap-2 mt-4 pt-4 border-t border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-sky-500 inline-block"></span>
                            <span class="text-xs text-gray-600">Hadir</span>
                            <span class="text-xs font-semibold text-gray-800 ml-auto">{{ $hadirHariIni ?? 0 }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span>
                            <span class="text-xs text-gray-600">Izin</span>
                            <span class="text-xs font-semibold text-gray-800 ml-auto">{{ $izinHariIni ?? 0 }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-sky-400 inline-block"></span>
                            <span class="text-xs text-gray-600">Sakit</span>
                            <span class="text-xs font-semibold text-gray-800 ml-auto">{{ $sakitHariIni ?? 0 }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-rose-400 inline-block"></span>
                            <span class="text-xs text-gray-600">Alpha</span>
                            <span class="text-xs font-semibold text-gray-800 ml-auto">{{ $alphaHariIni ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= TABLE + INFO ROW ================= --}}
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-5 items-stretch">

                {{-- Absensi hari ini --}}
                <div class="stat-card xl:col-span-2 fade-up h-full flex flex-col" style="--d:420ms">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold text-gray-900">Absensi Hari Ini</h2>
                        <a href="{{ $safeRoute('admin.absensi.index') }}" class="pill-btn">Lihat Semua</a>
                    </div>
                    <div class="overflow-x-auto flex-1 min-h-[220px]">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-gray-400 text-xs uppercase">
                                    <th class="text-left font-medium pb-2 w-8">No</th>
                                    <th class="text-left font-medium pb-2">Nama Pegawai</th>
                                    <th class="text-left font-medium pb-2">Unit</th>
                                    <th class="text-left font-medium pb-2">Jam Masuk</th>
                                    <th class="text-left font-medium pb-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($absensiHariIni ?? [] as $i => $row)
                                <tr class="border-t border-gray-50">
                                    <td class="py-2.5 text-gray-500">{{ $i + 1 }}</td>
                                    <td class="py-2.5 font-medium text-gray-800">{{ $row->user->nama ?? '-' }}</td>
                                    <td class="py-2.5 text-gray-500">{{ $row->user->unit->nama_unit ?? '-' }}</td>
                                    <td class="py-2.5 text-gray-500">
                                        {{ $row->jam_masuk ? \Carbon\Carbon::parse($row->jam_masuk)->format('H:i') : '-' }}
                                    </td>
                                    <td class="py-2.5">
                                        @php
                                            $badge = match($row->status) {
                                                'terlambat' => 'bg-amber-50 text-amber-500',
                                                'hadir', 'tepat_waktu' => 'bg-sky-50 text-sky-500',
                                                default => 'bg-gray-50 text-gray-500',
                                            };
                                            $label = match($row->status) {
                                                'terlambat' => 'Terlambat',
                                                'hadir', 'tepat_waktu' => 'Tepat Waktu',
                                                default => ucfirst($row->status),
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-md text-xs font-medium {{ $badge }}">{{ $label }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-10 text-center text-gray-400">
                                        <div class="flex flex-col items-center gap-2">
                                            <span class="text-2xl">📭</span>
                                            <span>Belum ada absensi hari ini.</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Jam kerja hari ini --}}
                <div class="stat-card fade-up h-full flex flex-col" style="--d:480ms">
                    <h2 class="font-semibold text-gray-900 mb-4">Jam Kerja Hari Ini</h2>
                    <div class="flex justify-center mb-4">
                        <div class="w-14 h-14 rounded-full bg-sky-50 flex items-center justify-center text-sky-500 text-2xl">🕒</div>
                    </div>
                    <ul class="space-y-3 text-sm flex-1">
                        <li class="flex items-center justify-between pb-2 border-b border-gray-50">
                            <span class="text-gray-500">Hari</span>
                            <span class="font-semibold text-gray-800">{{ $jamKerja->hari ?? now()->translatedFormat('l') }}</span>
                        </li>
                        <li class="flex items-center justify-between pb-2 border-b border-gray-50">
                            <span class="text-gray-500">Jam Masuk</span>
                            <span class="font-semibold text-sky-600">
                                {{ $jamKerja ? \Carbon\Carbon::parse($jamKerja->jam_masuk)->format('H:i') : '07:30' }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between pb-2 border-b border-gray-50">
                            <span class="text-gray-500">Jam Pulang</span>
                            <span class="font-semibold text-rose-500">
                                {{ $jamKerja ? \Carbon\Carbon::parse($jamKerja->jam_pulang)->format('H:i') : '16:00' }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-gray-500">Toleransi Terlambat</span>
                            <span class="font-semibold text-amber-500">{{ $jamKerja->toleransi_terlambat ?? 15 }} Menit</span>
                        </li>
                    </ul>
                    <div class="mt-4 pt-3 border-t border-gray-100">
                        <p class="text-xs text-gray-400 flex items-center gap-1.5">
                            <span class="text-sky-500">●</span>
                            @if($jamKerja)
                                Jadwal berlaku untuk hari ini ({{ $jamKerja->hari ?? now()->translatedFormat('l') }})
                            @else
                                Belum ada jadwal yang disetel
                            @endif
                        </p>
                    </div>
                    <div class="mt-3">
                        <a href="{{ $safeRoute('admin.data-master.jam-kerja') }}" class="text-xs text-sky-600 font-medium hover:underline flex items-center gap-1">
                            Kelola Jam Kerja
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Pengajuan izin + menu cepat --}}
                <div class="flex flex-col gap-5 h-full">
                    <div class="stat-card fade-up flex-1 flex flex-col justify-between" style="--d:520ms">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center text-violet-500 text-lg">📄</div>
                            <div>
                                <p class="text-sm text-gray-500">Menunggu Persetujuan</p>
                                <p class="text-2xl font-bold text-gray-900 counter" data-value="{{ $pengajuanPending ?? 0 }}">0</p>
                            </div>
                        </div>
                        <a href="{{ $safeRoute('admin.izin.index') }}" class="pill-btn block text-center">Lihat Semua Pengajuan</a>
                    </div>

                    <div class="stat-card fade-up flex-1 flex flex-col" style="--d:560ms">
                        <h3 class="font-semibold text-gray-900 mb-3">Menu Cepat</h3>
                        <div class="grid grid-cols-2 gap-3 flex-1">
                            <a href="{{ $safeRoute('admin.data-master.pegawai.create') }}" class="quick-btn justify-center">
                                <span class="text-sky-500 text-lg">＋</span>
                                <span class="text-xs">Tambah Pegawai</span>
                            </a>
                            <a href="{{ $safeRoute('admin.data-master.jam-kerja') }}" class="quick-btn justify-center">
                                <span class="text-sky-500 text-lg">🕒</span>
                                <span class="text-xs">Jam Kerja</span>
                            </a>
                            <a href="{{ $safeRoute('admin.lokasi-kantor.create') }}" class="quick-btn justify-center">
                                <span class="text-sky-500 text-lg">📍</span>
                                <span class="text-xs">Tambah Lokasi</span>
                            </a>
                            <a href="{{ $safeRoute('admin.data-master.jenis-izin') }}" class="quick-btn justify-center">
                                <span class="text-sky-500 text-lg">📄</span>
                                <span class="text-xs">Jenis Izin</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= BOTTOM ROW ================= --}}
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 pb-2 items-stretch">

                {{-- Pegawai Terajin --}}
                <div class="stat-card fade-up h-full flex flex-col" style="--d:620ms">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold text-gray-900 leading-tight">Pegawai Terajin <span class="block text-xs font-normal text-gray-400">(Bulan Ini)</span></h2>
                        <a href="{{ $safeRoute('admin.absensi.index') }}" class="pill-btn shrink-0">Lihat Semua</a>
                    </div>
                    <ul class="space-y-3 flex-1">
                        @php $maxRajin = collect($topRajin ?? [])->max('total') ?: 1; @endphp
                        @forelse($topRajin ?? [] as $i => $item)
                        <li class="flex items-center gap-3 text-sm">
                            <span class="text-gray-400 w-4">
                                @if ($i === 0) 🥇
                                @elseif ($i === 1) 🥈
                                @elseif ($i === 2) 🥉
                                @else {{ $i + 1 }}
                                @endif
                            </span>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-gray-800 truncate">{{ $item->user->nama ?? '-' }}</span>
                                    <span class="text-gray-400 text-xs shrink-0">{{ $item->total }}x</span>
                                </div>
                                <span class="text-xs text-gray-400">{{ $item->user->unit->nama_unit ?? '-' }}</span>
                                <div class="w-full h-1.5 bg-sky-50 rounded-full mt-1 overflow-hidden">
                                    <div class="h-full bg-sky-500 rounded-full progress-bar" data-width="{{ round(($item->total / $maxRajin) * 100) }}"></div>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="h-full flex flex-col items-center justify-center text-center text-gray-400 text-sm gap-2 py-4">
                            <span class="text-2xl">📭</span>
                            Belum ada data kehadiran bulan ini.
                        </li>
                        @endforelse
                    </ul>
                </div>

                {{-- Top terlambat --}}
                <div class="stat-card fade-up h-full flex flex-col" style="--d:680ms">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold text-gray-900 leading-tight">Pegawai Terlambat Terbanyak <span class="block text-xs font-normal text-gray-400">(Bulan Ini)</span></h2>
                        <a href="{{ $safeRoute('admin.absensi.terlambat') }}" class="pill-btn shrink-0">Lihat Semua</a>
                    </div>
                    <ul class="space-y-3 flex-1">
                        @php $maxTerlambat = collect($topTerlambat ?? [])->max('total') ?: 1; @endphp
                        @forelse($topTerlambat ?? [] as $i => $item)
                        <li class="flex items-center gap-3 text-sm">
                            <span class="text-gray-400 w-4">{{ $i + 1 }}</span>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-gray-800 truncate">{{ $item->user->nama ?? '-' }}</span>
                                    <span class="text-gray-400 text-xs shrink-0">{{ $item->total }}x</span>
                                </div>
                                <span class="text-xs text-gray-400">{{ $item->user->unit->nama_unit ?? '-' }}</span>
                                <div class="w-full h-1.5 bg-rose-50 rounded-full mt-1 overflow-hidden">
                                    <div class="h-full bg-rose-400 rounded-full progress-bar" data-width="{{ round(($item->total / $maxTerlambat) * 100) }}"></div>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="h-full flex flex-col items-center justify-center text-center text-gray-400 text-sm gap-2 py-4">
                            <span class="text-2xl">🎉</span>
                            Belum ada data keterlambatan bulan ini.
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </main>
        
    </div>
</div>

{{-- ================= STYLES ================= --}}
@push('styles')
<style>
    .stat-card {
        background: #fff;
        border: 1px solid #f1f5f9;
        border-radius: 1.25rem;
        padding: 1.25rem;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        transition: box-shadow .25s ease, transform .25s ease;
    }
    .stat-card:hover { box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06); transform: translateY(-2px); }

    .pill-btn {
        font-size: .75rem; font-weight: 600; color: #0ea5e9;
        background: #f0f9ff; padding: .4rem .85rem; border-radius: .65rem;
        transition: background .2s ease;
    }
    .pill-btn:hover { background: #e0f2fe; }

    .dropdown-btn {
        font-size: .75rem; color: #64748b; border: 1px solid #e2e8f0;
        border-radius: .65rem; padding: .35rem .75rem; display: inline-flex;
        align-items: center; gap: .35rem; background: #fff; transition: border-color .2s ease;
    }
    .dropdown-btn:hover { border-color: #cbd5e1; }

    .quick-btn {
        font-size: .78rem; font-weight: 500; color: #334155;
        border: 1px solid #f1f5f9; border-radius: .85rem; padding: .7rem .6rem;
        display: flex; flex-direction: column; gap: .35rem; align-items: flex-start;
        transition: all .2s ease;
    }
    .quick-btn:hover { border-color: #bae6fd; background: #f0f9ff; transform: translateY(-1px); }
    .quick-btn span { font-size: 1.1rem; }

    .progress-bar { width: 0; transition: width 1s cubic-bezier(.22,1,.36,1); }

    .fade-up { opacity: 0; animation: fadeUp .55s ease forwards; animation-delay: var(--d, 0ms); }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes wave {
        0%, 100% { transform: rotate(0deg); }
        20% { transform: rotate(14deg); }
        40% { transform: rotate(-8deg); }
        60% { transform: rotate(14deg); }
        80% { transform: rotate(-4deg); }
    }
    .animate-wave { display: inline-block; animation: wave 1.8s ease-in-out infinite; }

    main::-webkit-scrollbar { width: 8px; }
    main::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 999px; }
    main::-webkit-scrollbar-track { background: transparent; }
</style>
@endpush

{{-- ================= SCRIPTS ================= --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ---- Count-up angka statistik ----
    document.querySelectorAll('.counter').forEach(function (el) {
        const target = parseInt(el.dataset.value || '0', 10);
        const duration = 900;
        const start = performance.now();
        function tick(now) {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.round(eased * target).toLocaleString('id-ID');
            if (progress < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    });

    // ---- Progress bar animasi lebar ----
    requestAnimationFrame(function () {
        document.querySelectorAll('.progress-bar').forEach(function (bar) {
            bar.style.width = (bar.dataset.width || 0) + '%';
        });
    });

    if (typeof Chart === 'undefined') {
        console.error('Chart.js belum ter-load.');
        return;
    }

    const sky = '#0ea5e9';
    const colors  = ['#0ea5e9', '#fbbf24', '#38bdf8', '#f87171'];

    // ---- Helper: gradient biru untuk area line chart ----
    function buildGradient(ctx) {
        const g = ctx.createLinearGradient(0, 0, 0, 220);
        g.addColorStop(0, 'rgba(14,165,233,0.25)');
        g.addColorStop(1, 'rgba(14,165,233,0)');
        return g;
    }

    // ---- Line chart: Kehadiran (Minggu / Bulan) ----
    const elBar = document.getElementById('chartKehadiran');
    let kehadiranChart = null;

    const dataMinggu = {
        labels: @json($kehadiranLabelsJs),
        data:   @json($kehadiranDataJs),
    };
    const dataBulan = {
        labels: @json($bulananLabelsJs),
        data:   @json($bulananDataJs),
    };

    if (elBar) {
        const ctxBar = elBar.getContext('2d');

        kehadiranChart = new Chart(elBar, {
            type: 'line',
            data: {
                labels: dataMinggu.labels,
                datasets: [{
                    data: dataMinggu.data,
                    borderColor: sky,
                    backgroundColor: buildGradient(ctxBar),
                    fill: true,
                    tension: 0.4,
                    spanGaps: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: sky,
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#0f172a', padding: 10, cornerRadius: 8 }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8', precision: 0 } },
                    x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                },
                animation: { duration: 700, easing: 'easeOutQuart' }
            }
        });

        window.switchKehadiranChart = function (mode) {
            const source = mode === 'bulan' ? dataBulan : dataMinggu;
            kehadiranChart.data.labels = source.labels;
            kehadiranChart.data.datasets[0].data = source.data;
            kehadiranChart.update();
        };
    }

    // ---- Donut chart: Persentase Status Hari Ini ----
    const elDonut = document.getElementById('chartStatus');
    if (elDonut) {
        new Chart(elDonut.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
                datasets: [{
                    data: @json($statusDonutJs),
                    backgroundColor: colors,
                    borderWidth: 0,
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = total > 0 ? Math.round((context.parsed / total) * 100) : 0;
                                return context.label + ': ' + context.parsed + ' (' + pct + '%)';
                            }
                        }
                    }
                },
                animation: { animateRotate: true, duration: 900 }
            }
        });
    }

    // ---- Line chart: Statistik Absensi Bulanan ----
    const elLine = document.getElementById('chartBulanan');
    if (elLine) {
        const ctxLine = elLine.getContext('2d');

        new Chart(elLine, {
            type: 'line',
            data: {
                labels: @json($bulananLabelsJs),
                datasets: [{
                    data: @json($bulananDataJs),
                    borderColor: sky,
                    backgroundColor: buildGradient(ctxLine),
                    fill: true,
                    tension: 0.4,
                    spanGaps: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: sky,
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#0f172a', padding: 10, cornerRadius: 8 }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8', precision: 0 } },
                    x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                },
                animation: { duration: 900, easing: 'easeOutQuart' }
            }
        });
    }

});
</script>
@endpush

@endsection