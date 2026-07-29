{{-- resources/views/admin/absensi/hari-ini.blade.php --}}
@extends('layouts.app')

@section('title', 'Absensi Hari Ini')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Absensi Hari Ini'])

        <main class="p-6">

            @php
                $persenHadir      = $ringkasan['total'] > 0 ? round($ringkasan['hadir']       / $ringkasan['total'] * 100, 2) : 0;
                $persenTerlambat  = $ringkasan['total'] > 0 ? round($ringkasan['terlambat']   / $ringkasan['total'] * 100, 2) : 0;
                $persenBelumAbsen = $ringkasan['total'] > 0 ? round($ringkasan['belum_absen'] / $ringkasan['total'] * 100, 2) : 0;
                $persenIzin       = $ringkasan['total'] > 0 ? round($ringkasan['izin']        / $ringkasan['total'] * 100, 2) : 0;

                $statusStyle = [
                    'Hadir'       => 'bg-emerald-50 text-emerald-600',
                    'Terlambat'   => 'bg-amber-50 text-amber-600',
                    'Belum Absen' => 'bg-purple-50 text-purple-600',
                    'Izin'        => 'bg-blue-50 text-blue-600',
                    'Sakit'       => 'bg-blue-50 text-blue-600',
                ];

                // Titik potong conic-gradient buat donat chart (kumulatif persen)
                $c1 = $persenHadir;
                $c2 = $c1 + $persenTerlambat;
                $c3 = $c2 + $persenBelumAbsen;
            @endphp

            {{-- Judul Halaman --}}
            <div class="flex flex-wrap items-start justify-between gap-4 mb-5">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Absensi Hari Ini</h1>
                    <p class="text-sm text-gray-400 mt-0.5">Data absensi pegawai tanggal {{ $tanggal }}</p>
                </div>
            </div>

            {{-- Bar Filter: Cari Nama, Tanggal --}}
            <form method="GET" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5">
                <div class="flex flex-wrap items-end gap-3">

                    {{-- Cari Nama --}}
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs text-gray-400 mb-1.5">Cari Nama Pegawai</label>
                        <div class="relative">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="7"/>
                                <path stroke-linecap="round" d="M21 21l-3.5-3.5"/>
                            </svg>
                            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Ketik nama pegawai..."
                                   class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>

                    {{-- Tanggal --}}
                    <div class="w-full sm:w-48">
                        <label class="block text-xs text-gray-400 mb-1.5">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ request('tanggal', $tanggalValue) }}"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center gap-2">
                        <button type="submit"
                                class="text-sm font-semibold px-4 py-2 rounded-lg bg-gray-800 hover:bg-gray-900 text-white transition">
                            Terapkan
                        </button>
                        <a href="{{ url()->current() }}"
                           class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:text-emerald-600 hover:border-emerald-300 transition"
                           title="Reset Filter">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </a>
                    </div>

                </div>
            </form>

            {{-- Kartu Statistik --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Total Pegawai</p>
                        <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ $ringkasan['total'] }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Orang</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Hadir</p>
                        <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ $ringkasan['hadir'] }}</p>
                        <p class="text-xs text-emerald-500 mt-0.5">{{ number_format($persenHadir, 2) }}%</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Terlambat</p>
                        <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ $ringkasan['terlambat'] }}</p>
                        <p class="text-xs text-amber-500 mt-0.5">{{ number_format($persenTerlambat, 2) }}%</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Belum Absen</p>
                        <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ $ringkasan['belum_absen'] }}</p>
                        <p class="text-xs text-purple-500 mt-0.5">{{ number_format($persenBelumAbsen, 2) }}%</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h8m6-4v-3m0 0V9m0 3h3m-3 0h-3"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Tabel & Ringkasan Kehadiran --}}
            <div class="flex flex-col xl:flex-row gap-5 items-start">

                {{-- Tabel --}}
                <div class="flex-1 w-full bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 text-gray-400 text-xs">
                                    <th class="text-left font-medium px-5 py-3 w-12">No</th>
                                    <th class="text-left font-medium px-5 py-3">NIP</th>
                                    <th class="text-left font-medium px-5 py-3">Nama Pegawai</th>
                                    <th class="text-left font-medium px-5 py-3">Jam Masuk</th>
                                    <th class="text-left font-medium px-5 py-3">Jam Pulang</th>
                                    <th class="text-left font-medium px-5 py-3">Status</th>
                                    <th class="text-left font-medium px-5 py-3">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($absensis as $row)
                                    <tr class="hover:bg-gray-50/60 transition-colors">
                                        <td class="px-5 py-3 text-gray-500">{{ $row['no'] }}</td>
                                        <td class="px-5 py-3 text-gray-500">{{ $row['nip'] }}</td>
                                        <td class="px-5 py-3 font-medium text-gray-700">{{ $row['nama'] }}</td>
                                        <td class="px-5 py-3 text-gray-500">{{ $row['masuk'] }}</td>
                                        <td class="px-5 py-3 text-gray-500">{{ $row['pulang'] }}</td>
                                        <td class="px-5 py-3">
                                            <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full {{ $statusStyle[$row['status']] ?? 'bg-gray-100 text-gray-500' }}">
                                                {{ $row['status'] }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3 text-gray-500">{{ $row['keterangan'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-5 py-8 text-center text-gray-400">Tidak ada data pegawai.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Ringkasan Kehadiran (Donut Chart) --}}
                <div class="w-full xl:w-80 shrink-0 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-sm font-bold text-gray-700 mb-5">Ringkasan Kehadiran</h3>

                    <div class="flex items-center justify-center">
                        <div class="relative w-40 h-40 rounded-full"
                             style="background: conic-gradient(
                                #10b981 0% {{ $c1 }}%,
                                #f59e0b {{ $c1 }}% {{ $c2 }}%,
                                #a855f7 {{ $c2 }}% {{ $c3 }}%,
                                #3b82f6 {{ $c3 }}% 100%
                             );">
                            <div class="absolute inset-3 bg-white rounded-full flex flex-col items-center justify-center">
                                <span class="text-xl font-extrabold text-gray-800">{{ $ringkasan['total'] }}</span>
                                <span class="text-xs text-gray-400">Total</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2 text-gray-600">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Hadir
                            </span>
                            <span class="text-gray-500">{{ $ringkasan['hadir'] }} ({{ number_format($persenHadir, 2) }}%)</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2 text-gray-600">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Terlambat
                            </span>
                            <span class="text-gray-500">{{ $ringkasan['terlambat'] }} ({{ number_format($persenTerlambat, 2) }}%)</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2 text-gray-600">
                                <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span> Belum Absen
                            </span>
                            <span class="text-gray-500">{{ $ringkasan['belum_absen'] }} ({{ number_format($persenBelumAbsen, 2) }}%)</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2 text-gray-600">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Izin
                            </span>
                            <span class="text-gray-500">{{ $ringkasan['izin'] }} ({{ number_format($persenIzin, 2) }}%)</span>
                        </div>
                    </div>
                </div>

            </div>

        </main>

    </div>

</div>

@endsection