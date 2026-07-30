{{-- resources/views/admin/izin/semua.blade.php --}}
@extends('layouts.app')

@section('title', 'Semua Pengajuan')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Semua Pengajuan'])

        <main class="p-6">

        @php
            $statusStyle = [
                'menunggu'  => 'bg-yellow-100 text-yellow-700',
                'disetujui' => 'bg-emerald-100 text-emerald-700',
                'ditolak'   => 'bg-red-100 text-red-700',
            ];
        @endphp

            {{-- Filter Bar --}}
            <form method="GET" action="{{ route('admin.izin.semua') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div class="flex flex-wrap items-end gap-3">

                        {{-- Tanggal --}}
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Tanggal</label>
                            <div class="relative">
                                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                                       class="w-36 border border-gray-200 rounded-lg pl-3 pr-9 py-2 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>

                        {{-- Unit --}}
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Unit</label>
                            <select name="unit" class="appearance-none w-36 border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option>Semua Unit</option>
                                @foreach($unitList as $unit)
                                    <option value="{{ $unit }}" @selected(request('unit') == $unit)>{{ $unit }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Jenis Izin --}}
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Jenis Izin</label>
                            <select name="jenis" class="appearance-none w-36 border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option>Semua Jenis</option>
                                @foreach($jenisIzinList as $jenis)
                                    <option value="{{ $jenis }}" @selected(request('jenis') == $jenis)>{{ $jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Status</label>
                            <select name="status" class="appearance-none w-36 border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="">Semua Status</option>
                                <option value="Disetujui" @selected(request('status') == 'Disetujui')>Disetujui</option>
                                <option value="Menunggu" @selected(request('status') == 'Menunggu')>Menunggu</option>
                                <option value="Ditolak" @selected(request('status') == 'Ditolak')>Ditolak</option>
                            </select>
                        </div>

                        {{-- Filter --}}
                        <button type="submit" class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition duration-150 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18l-7 9v6l-4 2v-8L3 4z"/>
                            </svg>
                            Filter
                        </button>

                        {{-- Reset --}}
                        <a href="{{ route('admin.izin.semua') }}" class="flex items-center gap-2 border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-semibold px-4 py-2 rounded-lg transition duration-150">
                            Reset
                        </a>
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
            </form>

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
                                <th class="text-left font-medium px-3 py-2">Unit</th>
                                <th class="text-left font-medium px-3 py-2">Jenis Izin</th>
                                <th class="text-left font-medium px-3 py-2 max-w-[130px]">Tanggal Izin</th>
                                <th class="text-left font-medium px-3 py-2 w-14">Durasi</th>
                                <th class="text-left font-medium px-3 py-2 max-w-[140px]">Alasan</th>
                                <th class="text-left font-medium px-3 py-2">Status</th>
                                <th class="text-center font-medium px-3 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($pengajuan as $row)
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-3 py-2 text-gray-500">{{ $loop->iteration + ($pengajuan->currentPage() - 1) * $pengajuan->perPage() }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $row->tanggal_pengajuan->format('d M Y') }}</td>
                                    <td class="px-3 py-2 font-medium text-gray-700">{{ $row->user->nama ?? '-' }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $row->user->nip ?? '-' }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $row->user->unit->nama_unit ?? '-' }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $row->jenisIzin->nama_izin ?? '-' }}</td>
                                    <td class="px-3 py-2 text-gray-500 max-w-[130px] whitespace-normal">
                                        {{ $row->tanggal_mulai->format('d M Y') }}
                                        @if($row->tanggal_selesai && !$row->tanggal_mulai->equalTo($row->tanggal_selesai))
                                            - {{ $row->tanggal_selesai->format('d M Y') }}
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-gray-500">{{ $row->durasi }} Hari</td>
                                    <td class="px-3 py-2 text-gray-500 max-w-[140px] whitespace-normal">{{ $row->alasan }}</td>
                                    <td class="px-3 py-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusStyle[$row->status] ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ $row->status }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <a href="{{ route('admin.izin.show', $row->id) }}"
                                            class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-gray-500 hover:bg-gray-600 text-white transition">
                                                Lihat
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="px-3 py-6 text-center text-gray-400">Belum ada data pengajuan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-between px-5 py-4 border-t border-gray-100">
                    <p class="text-sm text-gray-400">
                        Menampilkan {{ $pengajuan->firstItem() ?? 0 }} - {{ $pengajuan->lastItem() ?? 0 }} dari {{ $pengajuan->total() }} data
                    </p>
                    <div class="flex items-center gap-1.5">
                        {{ $pengajuan->links() }}
                    </div>
                </div>
            </div>

        </main>
    @include('admin.components.footer')
    </div>

</div>

@endsection