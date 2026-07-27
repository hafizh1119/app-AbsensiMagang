{{-- resources/views/admin/absensi/semua.blade.php --}}
@extends('layouts.app')

@section('title', 'Semua Absensi')

@section('content')

<div class="flex">

    {{-- Sidebar tetap di kiri --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 flex flex-col min-h-screen">

        @include('admin.components.header', ['title' => 'Semua Absensi'])

        <main class="p-6">

            @php
                // Data contoh, ganti dengan data asli dari controller (mis. $absensis dari paginate())
                $absensis = $absensis ?? collect([
                    ['no' => 1, 'nip' => '199203152019031005', 'nama' => 'Budi Santoso',   'tanggal' => '26/05/2025', 'unit' => 'Keuangan', 'masuk' => '07:58', 'pulang' => '16:31', 'status' => 'Hadir'],
                    ['no' => 2, 'nip' => '199508202020032011', 'nama' => 'Siti Rahmawati', 'tanggal' => '26/05/2025', 'unit' => 'SDM',      'masuk' => '08:12', 'pulang' => '-',     'status' => 'Terlambat'],
                    ['no' => 3, 'nip' => '199001102015041002', 'nama' => 'Agus Setiawan',  'tanggal' => '26/05/2025', 'unit' => 'IT',       'masuk' => '07:45', 'pulang' => '16:32', 'status' => 'Pulang'],
                    ['no' => 4, 'nip' => '199712052021032018', 'nama' => 'Dewi Lestari',   'tanggal' => '26/05/2025', 'unit' => 'Keuangan', 'masuk' => '-',     'pulang' => '-',     'status' => 'Tidak Hadir'],
                    ['no' => 5, 'nip' => '199403182018011007', 'nama' => 'Eko Prasetyo',   'tanggal' => '26/05/2025', 'unit' => 'Umum',     'masuk' => '08:05', 'pulang' => '16:33', 'status' => 'Hadir'],
                ]);

                $statusStyle = [
                    'Hadir'      => 'bg-emerald-50 text-emerald-600',
                    'Terlambat'  => 'bg-amber-50 text-amber-600',
                    'Pulang'     => 'bg-blue-50 text-blue-600',
                    'Tidak Hadir'=> 'bg-rose-50 text-rose-500',
                ];
            @endphp

            {{-- Filter Bar --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4">
                <div class="flex flex-wrap items-end gap-3">

                    {{-- Tanggal --}}
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5">Tanggal</label>
                        <div class="relative">
                            <input type="date" value="2025-05-26"
                                   class="appearance-none border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        </div>
                    </div>

                    {{-- Unit --}}
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5">Unit</label>
                        <div class="relative">
                            <select class="appearance-none border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
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

                    {{-- Cari Pegawai --}}
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs text-gray-400 mb-1.5 invisible">Cari</label>
                        <div class="relative">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                            </svg>
                            <input type="text" placeholder="Cari pegawai..."
                                   class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        </div>
                    </div>

                    {{-- Export --}}
                    <button type="button" class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition duration-150 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M7 10l5 5 5-5M12 15V3"/>
                        </svg>
                        Export
                    </button>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 text-xs">
                                <th class="text-left font-medium px-5 py-3 w-12">No.</th>
                                <th class="text-left font-medium px-5 py-3">Tanggal</th>
                                <th class="text-left font-medium px-5 py-3">NIP</th>
                                <th class="text-left font-medium px-5 py-3">Nama Pegawai</th>
                                <th class="text-left font-medium px-5 py-3">Unit</th>
                                <th class="text-left font-medium px-5 py-3">Jam Masuk</th>
                                <th class="text-left font-medium px-5 py-3">Jam Pulang</th>
                                <th class="text-left font-medium px-5 py-3">Status</th>
                                <th class="text-left font-medium px-5 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($absensis as $row)
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-5 py-3 text-gray-500">{{ $row['no'] }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ $row['tanggal'] }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ $row['nip'] }}</td>
                                    <td class="px-5 py-3 font-medium text-gray-700">{{ $row['nama'] }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ $row['unit'] }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ $row['masuk'] }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ $row['pulang'] }}</td>
                                    <td class="px-5 py-3">
                                        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full {{ $statusStyle[$row['status']] ?? 'bg-gray-100 text-gray-500' }}">
                                            {{ $row['status'] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3">
                                        <a href="#" class="inline-flex items-center justify-center w-7 h-7 rounded-full text-gray-400 hover:bg-gray-100 hover:text-emerald-600 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-between px-5 py-4 border-t border-gray-100">
                    <p class="text-sm text-gray-400">Menampilkan 1-5 dari 128 data</p>
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