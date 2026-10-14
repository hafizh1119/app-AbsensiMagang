{{-- resources/views/admin/absensi/hari-ini.blade.php --}}
@extends('layouts.app')

@section('title', 'Absensi Hari Ini')

@section('content')

<div class="flex h-screen overflow-hidden"
     x-data="{
        editOpen: {{ $errors->any() && old('_form') === 'edit' ? 'true' : 'false' }},
        form: {
            id: {{ old('_form') === 'edit' ? (int) old('id') : 'null' }},
            nama: @js(old('info_nama', '')),
            nip: @js(old('info_nip', '')),
            tanggal: @js(old('tanggal', '')),
            jam_masuk: @js(old('jam_masuk', '')),
            jam_pulang: @js(old('jam_pulang', '')),
            status: @js(old('status', 'hadir')),
            keterangan: @js(old('keterangan', ''))
        },
        openEdit(row) {
            this.form = {
                id: row.id,
                nama: row.nama,
                nip: row.nip,
                tanggal: row.tanggal,
                jam_masuk: row.jam_masuk,
                jam_pulang: row.jam_pulang,
                status: row.status,
                keterangan: row.keterangan
            };
            this.editOpen = true;
        }
     }">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Absensi Hari Ini'])

        <main class="p-6">

            @if (session('success'))
                <div class="mb-4 flex items-center gap-3 bg-sky-50 border border-sky-100 text-sky-600 text-sm rounded-xl px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $total = $ringkasan['total'];

                $persenHadir      = $total > 0 ? round($ringkasan['hadir']       / $total * 100, 2) : 0;
                $persenIzin       = $total > 0 ? round($ringkasan['izin']        / $total * 100, 2) : 0;
                $persenTidakHadir = $total > 0 ? round($ringkasan['tidak_hadir'] / $total * 100, 2) : 0;

                $statusStyle = [
                    'Hadir'          => 'bg-emerald-50 text-emerald-600',
                    'Terlambat'      => 'bg-amber-50 text-amber-600',
                    'Belum Absen'    => 'bg-purple-50 text-purple-600',
                    'Izin'           => 'bg-blue-50 text-blue-600',
                    'Sakit'          => 'bg-blue-50 text-blue-600',
                    'Cuti'           => 'bg-indigo-50 text-indigo-600',
                    'Izin Terlambat' => 'bg-orange-50 text-orange-600',
                    'Tidak Hadir'    => 'bg-rose-50 text-rose-500',
                    'Alpha'          => 'bg-rose-50 text-rose-500',
                ];

                // Export mengikuti tanggal & pencarian yang sedang diterapkan
                $exportParams = array_filter([
                    'filter' => 'custom',
                    'dari'   => $tanggalValue,
                    'sampai' => $tanggalValue,
                    'search' => request('cari'),
                ]);
            @endphp

            {{-- Judul Halaman --}}
            <div class="flex flex-wrap items-start justify-between gap-4 mb-5">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Absensi Hari Ini</h1>
                    <p class="text-sm text-gray-400 mt-0.5">Data absensi peserta tanggal {{ $tanggal }}</p>
                </div>

                <a href="{{ route('admin.absensi.export', $exportParams) }}"
                   class="flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition duration-150 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M7 10l5 5 5-5M12 15V3"/>
                    </svg>
                    Export
                </a>
            </div>

            {{-- Bar Filter: Cari Nama, Tanggal --}}
            <form method="GET" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5">
                <div class="flex flex-wrap items-end gap-3">

                    {{-- Cari Nama --}}
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs text-gray-400 mb-1.5">Cari Nama Peserta</label>
                        <div class="relative">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="7"/>
                                <path stroke-linecap="round" d="M21 21l-3.5-3.5"/>
                            </svg>
                            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Ketik nama peserta..."
                                   class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
                        </div>
                    </div>

                    {{-- Tanggal --}}
                    <div class="w-full sm:w-48">
                        <label class="block text-xs text-gray-400 mb-1.5">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ request('tanggal', $tanggalValue) }}"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center gap-2">
                        <button type="submit"
                                class="text-sm font-semibold px-4 py-2 rounded-lg bg-gray-800 hover:bg-gray-900 text-white transition">
                            Terapkan
                        </button>
                        <a href="{{ url()->current() }}"
                           class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:text-sky-600 hover:border-sky-300 transition"
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

                {{-- Total Peserta --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Total Peserta</p>
                        <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ $ringkasan['total'] }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Peserta aktif</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Hadir --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Hadir</p>
                        <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ $ringkasan['hadir'] }}</p>
                        <p class="text-xs text-emerald-500 mt-0.5">
                            {{ number_format($persenHadir, 2) }}%
                            @if ($ringkasan['terlambat'] > 0)
                                <span class="text-gray-400">&middot; {{ $ringkasan['terlambat'] }} terlambat</span>
                            @endif
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Izin --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Izin</p>
                        <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ $ringkasan['izin'] }}</p>
                        <p class="text-xs text-blue-500 mt-0.5">{{ number_format($persenIzin, 2) }}%</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>

                {{-- Tidak Hadir --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Tidak Hadir</p>
                        <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ $ringkasan['tidak_hadir'] }}</p>
                        <p class="text-xs text-rose-500 mt-0.5">{{ number_format($persenTidakHadir, 2) }}%</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-rose-50 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 text-xs">
                                <th class="text-left font-medium px-5 py-3 w-12">No.</th>
                                <th class="text-left font-medium px-5 py-3">No. Peserta</th>
                                <th class="text-left font-medium px-5 py-3">Nama Peserta</th>
                                <th class="text-left font-medium px-5 py-3">Jam Masuk</th>
                                <th class="text-left font-medium px-5 py-3">Jam Pulang</th>
                                <th class="text-left font-medium px-5 py-3">Status</th>
                                <th class="text-left font-medium px-5 py-3">Keterangan</th>
                                <th class="text-center font-medium px-5 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($absensis as $row)
                                <tr class="hover:bg-gray-50/60 transition-colors" x-data="{ confirmOpen: false }">
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
                                    <td class="px-5 py-3 text-center">
                                        @if ($row['edit'])
                                            <div class="flex items-center justify-center gap-2">

                                                {{-- Tombol Edit --}}
                                                <button type="button" @click="openEdit(@js($row['edit']))"
                                                        class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-sky-500 hover:bg-sky-600 text-white transition">
                                                    Edit
                                                </button>

                                                {{-- Tombol Hapus --}}
                                                <button type="button" @click="confirmOpen = true"
                                                        class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-rose-500 hover:bg-rose-600 text-white transition">
                                                    Hapus
                                                </button>

                                                {{-- Overlay konfirmasi hapus --}}
                                                <div x-show="confirmOpen"
                                                     x-transition.opacity
                                                     style="display: none;"
                                                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">

                                                    <div x-show="confirmOpen"
                                                         x-transition:enter="transition ease-out duration-200"
                                                         x-transition:enter-start="opacity-0 scale-95"
                                                         x-transition:enter-end="opacity-100 scale-100"
                                                         @click.outside="confirmOpen = false"
                                                         class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-center">

                                                        <div class="w-14 h-14 mx-auto rounded-full bg-rose-50 flex items-center justify-center mb-4">
                                                            <svg class="w-7 h-7 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/>
                                                            </svg>
                                                        </div>

                                                        <h3 class="text-base font-bold text-gray-900 mb-1">Hapus Data Absensi?</h3>
                                                        <p class="text-sm text-gray-500 mb-6">
                                                            Data absensi {{ $row['nama'] }} yang sudah dihapus tidak dapat dikembalikan.
                                                        </p>

                                                        <div class="flex items-center gap-3">
                                                            <button type="button" @click="confirmOpen = false"
                                                                    class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                                                Batal
                                                            </button>
                                                            <form action="{{ route('admin.absensi.destroy', $row['edit']['id']) }}" method="POST" class="flex-1">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                        class="w-full text-sm font-semibold px-4 py-2.5 rounded-lg bg-rose-500 hover:bg-rose-600 text-white transition">
                                                                    Ya, Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-300">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-8 text-center text-gray-400">Tidak ada data peserta.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Modal edit (file terpisah, dipakai bersama halaman Semua Absensi) --}}
            @include('admin.absensi.edit')

        </main>
        @include('admin.components.footer')
    </div>

</div>

@endsection