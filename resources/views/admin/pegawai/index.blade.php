{{-- resources/views/admin/pegawai/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Pegawai')

@section('content')

<div class="flex">

    {{-- Sidebar tetap di kiri --}}
    @include('admin.components.sidebar')

    <div class="flex-1 min-w-0 flex flex-col min-h-screen">

        @include('admin.components.header', ['title' => 'Data Pegawai'])

        <main class="p-6">

            {{-- Judul & Tombol Tambah --}}
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900">Data Pegawai</h2>
                <a href="#"
                   class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Tambah Pegawai
                </a>
            </div>

            @php
                $pegawai = $pegawai ?? collect([
                    ['no' => 1, 'nama' => 'Budi Santoso',   'nip' => '199203152019031005', 'unit' => 'Keuangan', 'jabatan' => 'Staf Keuangan',  'status' => 'Aktif'],
                    ['no' => 2, 'nama' => 'Siti Rahmawati', 'nip' => '199401102008032002', 'unit' => 'SDM',      'jabatan' => 'Staf SDM',       'status' => 'Aktif'],
                    ['no' => 3, 'nama' => 'Agus Setiawan',  'nip' => '199007110015031002', 'unit' => 'IT',       'jabatan' => 'Programmer',     'status' => 'Aktif'],
                    ['no' => 4, 'nama' => 'Dewi Lestari',   'nip' => '198905250018032001', 'unit' => 'Keuangan', 'jabatan' => 'Staff Akuntansi', 'status' => 'Aktif'],
                    ['no' => 5, 'nama' => 'Eko Prasetyo',   'nip' => '199301820017031004', 'unit' => 'Umum',     'jabatan' => 'Driver',         'status' => 'Aktif'],
                ]);
            @endphp

            {{-- Filter --}}
            <div class="flex flex-wrap items-center gap-3 mb-4">

                {{-- Cari --}}
                <div class="relative flex-1 min-w-[220px]">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="7"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" placeholder="Cari pegawai..."
                           class="w-full bg-white border border-gray-200 rounded-lg pl-9 pr-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                {{-- Status --}}
                <div class="relative">
                    <select class="appearance-none w-32 bg-white border border-gray-200 rounded-lg pl-3 pr-8 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option>Semua Status</option>
                        <option>Aktif</option>
                        <option>Nonaktif</option>
                    </select>
                    <svg class="w-4 h-4 text-gray-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>

                {{-- Unit --}}
                <div class="relative">
                    <select class="appearance-none w-40 bg-white border border-gray-200 rounded-lg pl-3 pr-8 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option>Semua Unit</option>
                        <option>Keuangan</option>
                        <option>SDM</option>
                        <option>IT</option>
                        <option>Umum</option>
                    </select>
                    <svg class="w-4 h-4 text-gray-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>

            </div>

            {{-- Tabel --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 text-xs">
                                <th class="text-left font-medium px-4 py-3 w-10">No.</th>
                                <th class="text-left font-medium px-4 py-3">Nama</th>
                                <th class="text-left font-medium px-4 py-3">NIP</th>
                                <th class="text-left font-medium px-4 py-3">Unit</th>
                                <th class="text-left font-medium px-4 py-3">Jabatan</th>
                                <th class="text-left font-medium px-4 py-3">Status</th>
                                <th class="text-left font-medium px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($pegawai as $row)
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-4 py-3 text-gray-500">{{ $row['no'] }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $row['nama'] }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $row['nip'] }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $row['unit'] }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $row['jabatan'] }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full
                                            {{ $row['status'] === 'Aktif' ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-500' }}">
                                            {{ $row['status'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-1">
                                            <a href="#"
                                               class="inline-flex items-center justify-center w-7 h-7 rounded-full text-gray-400 hover:bg-gray-100 hover:text-emerald-600 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 4H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                            </a>
                                            <form action="#" method="POST" class="inline" onsubmit="return confirm('Hapus data pegawai ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full text-gray-400 hover:bg-rose-50 hover:text-rose-500 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-between px-5 py-4 border-t border-gray-100">
                    <p class="text-sm text-gray-400">Menampilkan 1 - {{ $pegawai->count() }} dari 128 data</p>
                    <div class="flex items-center gap-1.5">
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 disabled:opacity-40" disabled>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-600 text-white text-sm font-semibold">1</button>
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 text-sm font-medium">2</button>
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 text-sm font-medium">3</button>
                        <span class="w-8 h-8 flex items-center justify-center text-gray-400 text-sm">...</span>
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 text-sm font-medium">26</button>
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100">
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