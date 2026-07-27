{{-- resources/views/admin/data-master/unit/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Unit')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Unit'])

        <main class="p-6">

            {{-- Judul & Tombol Tambah --}}
            <div class="flex items-center justify-between mb-1">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Unit</h2>
                    <p class="text-sm text-gray-400 mt-1">Kelola data unit atau divisi di lingkungan dinas</p>
                </div>
                <a href="#"
                   class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Tambah Unit
                </a>
            </div>

            @php
                $stats = [
                    ['label' => 'Total Unit',     'value' => '12',  'suffix' => 'Unit',     'icon' => 'building',   'color' => 'emerald'],
                    ['label' => 'Total Pegawai',  'value' => '128', 'suffix' => 'Pegawai',  'icon' => 'users',      'color' => 'blue'],
                ];

                $unit = $unit ?? collect([
                    ['no' => 1,  'kode' => 'SET',      'nama' => 'Sekretariat',               'jenis' => 'Struktural',            'jumlah' => 15],
                    ['no' => 2,  'kode' => 'BID-KOP',  'nama' => 'Bidang Koperasi',           'jenis' => 'Struktural',            'jumlah' => 28],
                    ['no' => 3,  'kode' => 'BID-UMKM', 'nama' => 'Bidang UMKM',               'jenis' => 'Struktural',            'jumlah' => 25],
                    ['no' => 4,  'kode' => 'BID-PMB',  'nama' => 'Bidang Pembiayaan',         'jenis' => 'Struktural',            'jumlah' => 20],
                    ['no' => 5,  'kode' => 'BID-PGW',  'nama' => 'Bidang Pengawasan',         'jenis' => 'Struktural',            'jumlah' => 18],
                    ['no' => 6,  'kode' => 'UPT-LAB',  'nama' => 'UPT Laboratorium',          'jenis' => 'Unit Pelaksana Teknis', 'jumlah' => 8],
                    ['no' => 7,  'kode' => 'UPT-BLK',  'nama' => 'UPT Balatkop',              'jenis' => 'Unit Pelaksana Teknis', 'jumlah' => 7],
                    ['no' => 8,  'kode' => 'SUB-PRG',  'nama' => 'Subbag Program',            'jenis' => 'Sub Bagian',            'jumlah' => 5],
                    ['no' => 9,  'kode' => 'SUB-KEU',  'nama' => 'Subbag Keuangan',           'jenis' => 'Sub Bagian',            'jumlah' => 6],
                    ['no' => 10, 'kode' => 'SUB-UMUM', 'nama' => 'Subbag Umum & Kepegawaian', 'jenis' => 'Sub Bagian',            'jumlah' => 6],
                ]);
            @endphp

            {{-- Kartu Statistik --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 my-5">
                @foreach ($stats as $stat)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0
                            {{ match($stat['color']) {
                                'emerald' => 'bg-emerald-50 text-emerald-600',
                                'blue'    => 'bg-blue-50 text-blue-600',
                                'amber'   => 'bg-amber-50 text-amber-600',
                                'rose'    => 'bg-rose-50 text-rose-500',
                                default   => 'bg-gray-50 text-gray-500',
                            } }}">
                            @switch($stat['icon'])
                                @case('building')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="4" y="3" width="16" height="18" rx="1"/>
                                        <path stroke-linecap="round" d="M9 7h1M14 7h1M9 11h1M14 11h1M9 15h1M14 15h1"/>
                                    </svg>
                                    @break
                                @case('users')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/>
                                        <circle cx="10" cy="7" r="4"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                                    </svg>
                                    @break
                            @endswitch
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">{{ $stat['label'] }}</p>
                            <p class="text-2xl font-bold text-gray-900 leading-tight">{{ $stat['value'] }}</p>
                            <p class="text-xs text-gray-400">{{ $stat['suffix'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Card utama: filter + tabel --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">

                {{-- Filter --}}
                <div class="flex flex-wrap items-end gap-3 p-5 border-b border-gray-100">

                    {{-- Cari --}}
                    <div class="flex-1 min-w-[220px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Cari</label>
                        <div class="relative">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="7"/>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                            <input type="text" placeholder="Cari nama unit atau kode..."
                                   class="w-full bg-white border border-gray-200 rounded-lg pl-9 pr-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>

                    {{-- Tombol Filter & Reset --}}
                    <div class="flex items-center gap-2">
                        <button type="button"
                                class="flex items-center gap-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-semibold px-4 py-2.5 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5M4 9a8 8 0 0 1 14.5-4.5M20 15a8 8 0 0 1-14.5 4.5"/>
                            </svg>
                        </button>
                    </div>

                </div>

                {{-- Tabel --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 text-xs">
                                <th class="text-left font-medium px-4 py-3 w-10">No</th>
                                <th class="text-left font-medium px-4 py-3">Nama Unit</th>
                                <th class="text-center font-medium px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($unit as $row)
                                <tr class="hover:bg-gray-50/60 transition-colors" x-data="{ confirmOpen: false }">
                                    <td class="px-4 py-3 text-gray-500">{{ $row['no'] }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $row['nama'] }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="#"
                                            class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white transition">
                                                Edit
                                            </a>
                                            
                                            {{-- Tombol Hapus --}}
                                            <button type="button" @click="confirmOpen = true"
                                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-rose-500 hover:bg-rose-600 text-white transition">
                                                Hapus
                                            </button>

                                            {{-- Overlay --}}
                                            <div x-show="confirmOpen"
                                                x-transition.opacity
                                                style="display: none;"
                                                class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">

                                                {{-- Card konfirmasi --}}
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

                                                    <h3 class="text-base font-bold text-gray-900 mb-1">Hapus Data Unit?</h3>
                                                    <p class="text-sm text-gray-500 mb-6">
                                                        Data yang sudah dihapus tidak dapat dikembalikan. Pastikan kamu yakin sebelum melanjutkan.
                                                    </p>

                                                    <div class="flex items-center gap-3">
                                                        <button type="button" @click="confirmOpen = false"
                                                                class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                                            Batal
                                                        </button>
                                                        <form action="#" method="POST" class="flex-1">
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
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-between px-5 py-4 border-t border-gray-100">
                    <p class="text-sm text-gray-400">Menampilkan 1 - {{ $unit->count() }} dari {{ $unit->count() }} data</p>
                    <div class="flex items-center gap-1.5">
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 disabled:opacity-40" disabled>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-600 text-white text-sm font-semibold">1</button>
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100" disabled>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>

            </div>

        </main>

    </div>

</div>

@endsection