{{-- resources/views/admin/absensi/rekap.blade.php --}}
@extends('layouts.app')

@section('title', 'Rekap Absensi')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Rekap Absensi'])

        <main class="p-6">

            @php
                $totalPegawai   = $rekap['total'];
                $totalHadir     = $rekap['hadir'];
                $totalTerlambat = $rekap['terlambat'];
                $totalIzin      = $rekap['izin'];
                $totalSakit     = $rekap['sakit'];
                $totalAlpha     = $rekap['alpha'];

                $persen = fn($jumlah, $total) => $total > 0 ? number_format($jumlah / $total * 100, 2) . '%' : '0%';

                // Hitung koordinat SVG untuk line chart
                $chartWidth  = 400;
                $chartHeight = 140;
                $count = count($grafikHarian);
                $points = [];
                foreach ($grafikHarian as $i => $val) {
                    $x = $count > 1 ? ($i / ($count - 1)) * $chartWidth : 0;
                    $y = $chartHeight - ($val / 100 * $chartHeight);
                    $points[] = [$x, $y];
                }
                $polylinePoints = implode(' ', array_map(fn($p) => round($p[0], 1) . ',' . round($p[1], 1), $points));
                $areaPoints = $polylinePoints . ' ' . round($chartWidth, 1) . ',' . $chartHeight . ' 0,' . $chartHeight;
            @endphp

            {{-- Judul Halaman & Filter --}}
            <form method="GET" class="flex flex-wrap items-start justify-between gap-4 mb-5">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Rekap Absensi</h1>
                    <p class="text-sm text-gray-400 mt-0.5">Rekapitulasi kehadiran pegawai</p>
                </div>

                <div class="flex flex-wrap items-end gap-3">

                    {{-- Periode --}}
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5">Periode</label>
                        <input type="month" name="periode" value="{{ $periodeInput }}"
                               class="w-40 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
                    </div>

                    {{-- Terapkan --}}
                    <button type="submit"
                            class="text-sm font-semibold px-4 py-2 rounded-lg bg-gray-800 hover:bg-gray-900 text-white transition">
                        Terapkan
                    </button>

                    {{-- Export --}}
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button type="button" @click="open = !open"
                                class="flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition duration-150 shadow-sm">
                            Export
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-cloak x-transition
                             class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 z-40">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:bg-sky-50 hover:text-sky-700">Export Excel</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:bg-sky-50 hover:text-sky-700">Export PDF</a>
                        </div>
                    </div>
                </div>
            </form>

            <p class="text-sm text-gray-400 -mt-3 mb-5">Periode: <span class="font-semibold text-gray-600">{{ $periode }}</span></p>

            {{-- Grafik Kehadiran --}}
            <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
                <h3 class="text-sm font-bold text-gray-700 mb-4">Grafik Kehadiran</h3>

                <div class="flex gap-2">
                    {{-- Sumbu Y --}}
                    <div class="flex flex-col justify-between text-[10px] text-gray-400 py-0.5" style="height: 140px;">
                        <span>100%</span>
                        <span>75%</span>
                        <span>50%</span>
                        <span>25%</span>
                        <span>0%</span>
                    </div>

                    {{-- Chart SVG --}}
                    <div class="flex-1 min-w-0">
                        <svg viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" class="w-full" style="height: 140px;" preserveAspectRatio="none">
                            {{-- Garis grid horizontal --}}
                            <line x1="0" y1="0"   x2="{{ $chartWidth }}" y2="0"   stroke="#f1f5f9" stroke-width="1"/>
                            <line x1="0" y1="35"  x2="{{ $chartWidth }}" y2="35"  stroke="#f1f5f9" stroke-width="1"/>
                            <line x1="0" y1="70"  x2="{{ $chartWidth }}" y2="70"  stroke="#f1f5f9" stroke-width="1"/>
                            <line x1="0" y1="105" x2="{{ $chartWidth }}" y2="105" stroke="#f1f5f9" stroke-width="1"/>
                            <line x1="0" y1="{{ $chartHeight }}" x2="{{ $chartWidth }}" y2="{{ $chartHeight }}" stroke="#f1f5f9" stroke-width="1"/>

                            @if(count($points) > 0)
                                {{-- Area di bawah garis --}}
                                <polygon points="{{ $areaPoints }}" fill="#0ea5e9" fill-opacity="0.12"/>

                                {{-- Garis kehadiran --}}
                                <polyline points="{{ $polylinePoints }}" fill="none" stroke="#0ea5e9" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"/>

                                {{-- Titik data --}}
                                @foreach($points as $p)
                                    <circle cx="{{ $p[0] }}" cy="{{ $p[1] }}" r="2" fill="#0ea5e9"/>
                                @endforeach
                            @endif
                        </svg>

                        {{-- Sumbu X --}}
                        <div class="flex justify-between text-[10px] text-gray-400 mt-1.5 px-0.5">
                            @foreach($sumbuX as $label)
                                <span>{{ $label }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Rekap --}}
            <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 text-xs">
                                <th rowspan="2" class="text-left font-medium px-5 py-3 align-bottom">Total Pegawai</th>
                                <th colspan="2" class="text-center font-medium px-3 py-2 border-l border-gray-100">Hadir</th>
                                <th colspan="2" class="text-center font-medium px-3 py-2 border-l border-gray-100">Terlambat</th>
                                <th colspan="2" class="text-center font-medium px-3 py-2 border-l border-gray-100">Izin</th>
                                <th colspan="2" class="text-center font-medium px-3 py-2 border-l border-gray-100">Sakit</th>
                                <th colspan="2" class="text-center font-medium px-3 py-2 border-l border-gray-100">Alpha</th>
                            </tr>
                            <tr class="bg-gray-50 text-gray-400 text-xs">
                                <th class="text-center font-medium px-3 py-2 border-l border-gray-100">Jumlah</th>
                                <th class="text-center font-medium px-3 py-2">%</th>
                                <th class="text-center font-medium px-3 py-2 border-l border-gray-100">Jumlah</th>
                                <th class="text-center font-medium px-3 py-2">%</th>
                                <th class="text-center font-medium px-3 py-2 border-l border-gray-100">Jumlah</th>
                                <th class="text-center font-medium px-3 py-2">%</th>
                                <th class="text-center font-medium px-3 py-2 border-l border-gray-100">Jumlah</th>
                                <th class="text-center font-medium px-3 py-2">%</th>
                                <th class="text-center font-medium px-3 py-2 border-l border-gray-100">Jumlah</th>
                                <th class="text-center font-medium px-3 py-2">%</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @if($totalPegawai > 0)
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-5 py-3 font-medium text-gray-700">{{ $totalPegawai }}</td>
                                    <td class="text-center px-3 py-3 text-gray-500 border-l border-gray-100">{{ $totalHadir }}</td>
                                    <td class="text-center px-3 py-3 text-gray-500">{{ $persen($totalHadir, $totalPegawai) }}</td>
                                    <td class="text-center px-3 py-3 text-gray-500 border-l border-gray-100">{{ $totalTerlambat }}</td>
                                    <td class="text-center px-3 py-3 text-gray-500">{{ $persen($totalTerlambat, $totalPegawai) }}</td>
                                    <td class="text-center px-3 py-3 text-gray-500 border-l border-gray-100">{{ $totalIzin }}</td>
                                    <td class="text-center px-3 py-3 text-gray-500">{{ $persen($totalIzin, $totalPegawai) }}</td>
                                    <td class="text-center px-3 py-3 text-gray-500 border-l border-gray-100">{{ $totalSakit }}</td>
                                    <td class="text-center px-3 py-3 text-gray-500">{{ $persen($totalSakit, $totalPegawai) }}</td>
                                    <td class="text-center px-3 py-3 text-gray-500 border-l border-gray-100">{{ $totalAlpha }}</td>
                                    <td class="text-center px-3 py-3 text-gray-500">{{ $persen($totalAlpha, $totalPegawai) }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="11" class="px-5 py-8 text-center text-gray-400">Belum ada data untuk periode ini.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
        @include('admin.components.footer')
    </div>

</div>

@endsection