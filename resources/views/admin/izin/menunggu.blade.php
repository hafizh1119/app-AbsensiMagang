{{-- resources/views/admin/izin/menunggu.blade.php --}}
@extends('layouts.app')

@section('title', 'Menunggu Persetujuan')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Menunggu Persetujuan'])

        <main class="p-6">

            @php
                // Data contoh, ganti dengan data asli dari controller
                $pengajuan = $pengajuan ?? collect([
                    ['no' => 1, 'diajukan' => '24 Mei 2025 10:20', 'nama' => 'Siti Aminah',    'nip' => '198903252015032002', 'unit' => 'Bidang Koperasi',   'jenis' => 'Sakit',        'tanggal_izin' => '25 Mei 2025',              'durasi' => '1 Hari', 'alasan' => 'Demam dan flu',          'diajukan_kepada' => 'Kepala Bidang'],
                    ['no' => 2, 'diajukan' => '23 Mei 2025 08:40', 'nama' => 'Rina Wulandari', 'nip' => '199001202016042004', 'unit' => 'Bidang Pembiayaan', 'jenis' => 'Cuti Tahunan', 'tanggal_izin' => '2 Jun 2025 - 6 Jun 2025',  'durasi' => '5 Hari', 'alasan' => 'Liburan sekolah anak',   'diajukan_kepada' => 'Kepala Bidang'],
                    ['no' => 3, 'diajukan' => '22 Mei 2025 09:55', 'nama' => 'Ahmad Fauzi',    'nip' => '199307122019031006', 'unit' => 'Bidang UMKM',       'jenis' => 'Izin Pribadi', 'tanggal_izin' => '26 Mei 2025',              'durasi' => '1 Hari', 'alasan' => 'Urusan keluarga',        'diajukan_kepada' => 'Kepala Bidang'],
                    ['no' => 4, 'diajukan' => '22 Mei 2025 11:30', 'nama' => 'Dewi Lestari',   'nip' => '199212062020122007', 'unit' => 'Sekretariat',       'jenis' => 'Sakit',        'tanggal_izin' => '23 Mei 2025 - 24 Mei 2025', 'durasi' => '2 Hari', 'alasan' => 'Cek kesehatan',          'diajukan_kepada' => 'Kepala Subbag'],
                ]);

                $totalMenunggu = $totalMenunggu ?? $pengajuan->count();
                $totalCuti     = $totalCuti     ?? $pengajuan->where('jenis', 'Cuti Tahunan')->count();
                $totalSakit    = $totalSakit    ?? $pengajuan->where('jenis', 'Sakit')->count();
                $totalPribadi  = $totalPribadi  ?? $pengajuan->where('jenis', 'Izin Pribadi')->count();
            @endphp

            {{-- Ringkasan --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

                {{-- Total Menunggu --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Total Menunggu</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalMenunggu }}</p>
                        <p class="text-xs text-gray-400">Pengajuan</p>
                    </div>
                    <svg class="w-9 h-9 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 2h12M6 22h12"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 2c0 5 4 6 6 8-2 2-6 3-6 8M18 2c0 5-4 6-6 8 2 2 6 3 6 8"/>
                    </svg>
                </div>

                {{-- Cuti Tahunan --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Cuti Tahunan</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalCuti }}</p>
                        <p class="text-xs text-gray-400">Pengajuan</p>
                    </div>
                    <svg class="w-9 h-9 text-amber-500 shrink-0" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                        <rect x="3" y="8" width="18" height="12" rx="2"/>
                        <path stroke-linecap="round" d="M8 8V6a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        <line x1="3" y1="13" x2="21" y2="13"/>
                    </svg>
                </div>

                {{-- Izin Sakit --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Izin Sakit</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalSakit }}</p>
                        <p class="text-xs text-gray-400">Pengajuan</p>
                    </div>
                    <svg class="w-9 h-9 text-sky-500 shrink-0" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 1 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"/>
                        <polyline points="7.5 12 10 12 11 10 13 14 14 12 16.5 12" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                {{-- Izin Pribadi --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Izin Pribadi</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalPribadi }}</p>
                        <p class="text-xs text-gray-400">Pengajuan</p>
                    </div>
                    <svg class="w-9 h-9 text-violet-500 shrink-0" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="4"/>
                        <path stroke-linecap="round" d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/>
                    </svg>
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
                                <th class="text-left font-medium px-3 py-2">Diajukan Kepada</th>
                                <th class="text-left font-medium px-3 py-2">Aksi</th>
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
                                    <td class="px-3 py-2 text-gray-500">{{ $row['diajukan_kepada'] }}</td>
                                    <td class="px-3 py-2">
                                        <div class="flex items-center gap-1">
                                            {{-- Lihat Detail --}}
                                            <a href="#" title="Lihat Detail"
                                               class="inline-flex items-center justify-center w-7 h-7 rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </a>

                                            {{-- Setujui --}}
                                            <form action="#" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" title="Setujui"
                                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full text-emerald-500 hover:bg-emerald-50 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/>
                                                    </svg>
                                                </button>
                                            </form>

                                            {{-- Tolak --}}
                                            <form action="#" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" title="Tolak"
                                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full text-rose-500 hover:bg-rose-50 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12"/>
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
                    <p class="text-sm text-gray-400">Menampilkan 1 - {{ $pengajuan->count() }} dari {{ $pengajuan->count() }} data</p>
                </div>
            </div>

        </main>

    </div>

</div>

@endsection