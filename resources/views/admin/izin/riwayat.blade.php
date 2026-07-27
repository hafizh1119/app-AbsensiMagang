{{-- resources/views/admin/izin/riwayat.blade.php --}}
@extends('layouts.app')

@section('title', 'Riwayat Pengajuan')

@section('content')

<div class="flex">

    {{-- Sidebar tetap di kiri --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 min-w-0 flex flex-col min-h-screen">

        @include('admin.components.header', ['title' => 'Riwayat Pengajuan'])

        <main class="p-6">

            @php
                // Data contoh, ganti dengan data asli dari controller (mis. $pengajuan dari paginate())
                $pengajuan = $pengajuan ?? collect([
                    ['no' => 1, 'diajukan' => '20 Mei 2025 09:10', 'nama' => 'Budi Santoso',  'nip' => '198701152010011001', 'unit' => 'Sekretariat',       'jenis' => 'Cuti Tahunan', 'tanggal_izin' => '12 Mei 2025 - 16 Mei 2025', 'durasi' => '5 Hari', 'alasan' => 'Liburan keluarga',       'status' => 'Disetujui', 'disetujui_oleh' => 'Kepala Bagian', 'tanggal_keputusan' => '21 Mei 2025 10:15'],
                    ['no' => 2, 'diajukan' => '19 Mei 2025 14:25', 'nama' => 'Dwi Prasetyo',  'nip' => '199205102017041003', 'unit' => 'Bidang UMKM',       'jenis' => 'Izin Pribadi', 'tanggal_izin' => '20 Mei 2025',               'durasi' => '1 Hari', 'alasan' => 'Urusan keluarga',        'status' => 'Ditolak',   'disetujui_oleh' => 'Kepala Bidang', 'tanggal_keputusan' => '19 Mei 2025 16:40'],
                    ['no' => 3, 'diajukan' => '18 Mei 2025 08:50', 'nama' => 'Agus Setiawan', 'nip' => '199104182018031005', 'unit' => 'Bidang Pengawasan', 'jenis' => 'Sakit',        'tanggal_izin' => '19 Mei 2025 - 20 Mei 2025', 'durasi' => '2 Hari', 'alasan' => 'Flu dan demam',          'status' => 'Disetujui', 'disetujui_oleh' => 'Kepala Bidang', 'tanggal_keputusan' => '18 Mei 2025 11:22'],
                    ['no' => 4, 'diajukan' => '16 Mei 2025 11:05', 'nama' => 'Siti Aminah',   'nip' => '198903252015032002', 'unit' => 'Bidang Koperasi',   'jenis' => 'Cuti Tahunan', 'tanggal_izin' => '5 Mei 2025 - 9 Mei 2025',   'durasi' => '5 Hari', 'alasan' => 'Liburan sekolah anak',   'status' => 'Disetujui', 'disetujui_oleh' => 'Kepala Bidang', 'tanggal_keputusan' => '16 Mei 2025 13:30'],
                ]);

                $statusStyle = [
                    'Disetujui' => 'bg-emerald-50 text-emerald-600',
                    'Ditolak'   => 'bg-rose-50 text-rose-500',
                ];
            @endphp

            {{-- Filter Bar --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4">
                <div class="flex flex-wrap items-end justify-between gap-3">

                    <div class="flex flex-wrap items-end gap-3">

                        {{-- Tanggal --}}
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Tanggal</label>
                            <div class="relative">
                                <input type="text" placeholder="Pilih tanggal"
                                       class="w-36 border border-gray-200 rounded-lg pl-3 pr-9 py-2 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                                    <path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Jenis Izin --}}
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Jenis Izin</label>
                            <div class="relative">
                                <select class="appearance-none w-36 border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <option>Semua Jenis</option>
                                    <option>Cuti Tahunan</option>
                                    <option>Sakit</option>
                                    <option>Izin Pribadi</option>
                                </select>
                                <svg class="w-4 h-4 text-gray-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Status</label>
                            <div class="relative">
                                <select class="appearance-none w-36 border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <option>Semua Status</option>
                                    <option>Disetujui</option>
                                    <option>Ditolak</option>
                                </select>
                                <svg class="w-4 h-4 text-gray-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Filter --}}
                        <button type="button" class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition duration-150 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18l-7 9v6l-4 2v-8L3 4z"/>
                            </svg>
                            Filter
                        </button>

                        {{-- Reset --}}
                        <button type="button" class="flex items-center gap-2 border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-semibold px-4 py-2 rounded-lg transition duration-150">
                            Reset
                        </button>
                    </div>

                    {{-- Export --}}
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button type="button" @click="open = !open"
                                class="flex items-center gap-2 border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold px-4 py-2 rounded-lg transition duration-150">
                            Export
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-cloak x-transition
                             class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 z-40">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:bg-emerald-50 hover:text-emerald-700">Export Excel</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:bg-emerald-50 hover:text-emerald-700">Export PDF</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 text-xs">
                                <th class="text-left font-medium px-3 py-2 w-8">No</th>
                                <th class="text-left font-medium px-3 py-2">Tanggal Pengajuan</th>
                                <th class="text-left font-medium px-3 py-2">Nama Pegawai</th>
                                <th class="text-left font-medium px-3 py-2">NIP</th>
                                <th class="text-left font-medium px-3 py-2">Unit / Divisi</th>
                                <th class="text-left font-medium px-3 py-2">Jenis Izin</th>
                                <th class="text-left font-medium px-3 py-2 max-w-[130px]">Tanggal Izin</th>
                                <th class="text-left font-medium px-3 py-2 w-14">Durasi</th>
                                <th class="text-left font-medium px-3 py-2 max-w-[140px]">Alasan</th>
                                <th class="text-left font-medium px-3 py-2">Status</th>
                                <th class="text-left font-medium px-3 py-2">Disetujui Oleh</th>
                                <th class="text-left font-medium px-3 py-2">Tanggal Keputusan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($pengajuan as $row)
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-3 py-2 text-gray-500">{{ $row['no'] }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $row['diajukan'] }}</td>
                                    <td class="px-3 py-2 font-medium text-gray-700">{{ $row['nama'] }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $row['nip'] }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $row['unit'] }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $row['jenis'] }}</td>
                                    <td class="px-3 py-2 text-gray-500 max-w-[130px] whitespace-normal">{{ $row['tanggal_izin'] }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $row['durasi'] }}</td>
                                    <td class="px-3 py-2 text-gray-500 max-w-[140px] whitespace-normal">{{ $row['alasan'] }}</td>
                                    <td class="px-3 py-2">
                                        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full {{ $statusStyle[$row['status']] ?? 'bg-gray-100 text-gray-500' }}">
                                            {{ $row['status'] }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 text-gray-500">{{ $row['disetujui_oleh'] }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $row['tanggal_keputusan'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-between px-5 py-4 border-t border-gray-100">
                    <p class="text-sm text-gray-400">Menampilkan 1 - {{ $pengajuan->count() }} dari 36 data</p>
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
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 text-sm font-medium">9</button>
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