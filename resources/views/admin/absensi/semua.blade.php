{{-- resources/views/admin/absensi/semua.blade.php --}}
@extends('layouts.app')

@section('title', 'Semua Absensi')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Semua Absensi'])

        <main class="p-6">

            @if (session('success'))
                <div class="mb-4 flex items-center gap-3 bg-sky-50 border border-sky-100 text-sky-600 text-sm rounded-xl px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $statusStyle = [
                    'hadir'          => 'bg-emerald-50 text-emerald-600',
                    'terlambat'      => 'bg-amber-50 text-amber-600',
                    'izin'           => 'bg-blue-50 text-blue-600',
                    'sakit'          => 'bg-blue-50 text-blue-600',
                    'cuti'           => 'bg-purple-50 text-purple-600',
                    'izin_terlambat' => 'bg-orange-50 text-orange-600',
                    'tidak_hadir'    => 'bg-rose-50 text-rose-500',
                    'alpha'          => 'bg-rose-50 text-rose-500',
                ];
            @endphp

            {{-- Filter Bar --}}
            <form action="{{ route('admin.absensi.semua') }}" method="GET" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4">
                <div class="flex flex-wrap items-end gap-3">

                    {{-- Tanggal --}}
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5">Tanggal</label>
                        <div class="relative">
                            <input type="date" name="tanggal" value="{{ $tanggal }}"
                                   class="appearance-none border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                        </div>
                    </div>

                    {{-- Cari Pegawai --}}
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs text-gray-400 mb-1.5 invisible">Cari</label>
                        <div class="relative">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                            </svg>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau NIP pegawai..."
                                   class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                        </div>
                    </div>

                    {{-- Terapkan Filter --}}
                    <button type="submit" class="flex items-center gap-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold px-4 py-2 rounded-lg transition duration-150 shadow-sm">
                        Terapkan
                    </button>

                    @if ($tanggal || $search)
                        <a href="{{ route('admin.absensi.semua') }}"
                           class="text-sm font-semibold text-gray-500 hover:text-gray-700 px-2 py-2">
                            Reset
                        </a>
                    @endif

                    {{-- Export --}}
                    <button type="button" class="flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition duration-150 shadow-sm ml-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M7 10l5 5 5-5M12 15V3"/>
                        </svg>
                        Export
                    </button>
                </div>
            </form>

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
                                <th class="text-left font-medium px-5 py-3">Jam Masuk</th>
                                <th class="text-left font-medium px-5 py-3">Jam Pulang</th>
                                <th class="text-left font-medium px-5 py-3">Status</th>
                                <th class="text-left font-medium px-5 py-3">Keterangan</th>
                                <th class="text-center font-medium px-5 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($absensis as $absensi)
                                @php
                                    $no = ($absensis->currentPage() - 1) * $absensis->perPage() + $loop->iteration;
                                @endphp
                                <tr class="hover:bg-gray-50/60 transition-colors" x-data="{ confirmOpen: false }">
                                    <td class="px-5 py-3 text-gray-500">{{ $no }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ optional($absensi->tanggal)->format('d/m/Y') }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ $absensi->user->nip ?? '-' }}</td>
                                    <td class="px-5 py-3 font-medium text-gray-700">{{ $absensi->user->nama ?? '-' }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '-' }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ $absensi->jam_pulang ? \Carbon\Carbon::parse($absensi->jam_pulang)->format('H:i') : '-' }}</td>
                                    <td class="px-5 py-3">
                                        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full {{ $statusStyle[$absensi->status] ?? 'bg-gray-100 text-gray-500' }}">
                                            {{ ucwords(str_replace('_', ' ', $absensi->status ?? '-')) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-gray-500">{{ $absensi->keterangan ?? '-' }}</td>
                                    <td class="px-5 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.absensi.edit', $absensi->id) }}"
                                               class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white transition">
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

                                                    <h3 class="text-base font-bold text-gray-900 mb-1">Hapus Data Absensi?</h3>
                                                    <p class="text-sm text-gray-500 mb-6">
                                                        Data yang sudah dihapus tidak dapat dikembalikan. Pastikan kamu yakin sebelum melanjutkan.
                                                    </p>

                                                    <div class="flex items-center gap-3">
                                                        <button type="button" @click="confirmOpen = false"
                                                                class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                                            Batal
                                                        </button>
                                                        <form action="{{ route('admin.absensi.destroy', $absensi->id) }}" method="POST" class="flex-1">
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
                            @empty
                                <tr>
                                    <td colspan="9" class="px-5 py-8 text-center text-gray-400">Belum ada data absensi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-between px-5 py-4 border-t border-gray-100">
                    <p class="text-sm text-gray-400">
                        Menampilkan {{ $absensis->firstItem() ?? 0 }}-{{ $absensis->lastItem() ?? 0 }} dari {{ $absensis->total() }} data
                    </p>
                    {{ $absensis->onEachSide(1)->links() }}
                </div>
            </div>

        </main>
    @include('admin.components.footer')
    </div>
    
</div>

@endsection