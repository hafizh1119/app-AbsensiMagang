{{-- resources/views/admin/lokasi/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Lokasi Kantor')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Lokasi Kantor'])

        <main class="p-6">

            @if (session('success'))
                <div id="toastSuccess"
                     class="fixed top-5 right-5 z-[9999] flex items-center gap-3 bg-white border border-emerald-100 shadow-lg rounded-xl px-4 py-3 pr-5 transition-all duration-300 translate-x-0">
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Berhasil</p>
                        <p class="text-xs text-gray-500">{{ session('success') }}</p>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var toast = document.getElementById('toastSuccess');
                        if (!toast) return;

                        setTimeout(function () {
                            toast.style.opacity = '0';
                            toast.style.transform = 'translateX(120%)';
                            setTimeout(function () { toast.remove(); }, 300);
                        }, 3000);
                    });
                </script>
            @endif

            {{-- Judul & Tombol Tambah --}}
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Lokasi Kantor</h2>
                    <p class="text-sm text-gray-400 mt-1">Kelola titik lokasi absensi pegawai</p>
                </div>
                <a href="{{ route('admin.lokasi-kantor.create') }}"
                   class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Tambah Lokasi
                </a>
            </div>

            @php
                // Fallback data dummy kalau controller belum mengirim $lokasi (mis. saat preview desain).
                // Perhatikan: setiap baris sekarang wajib punya 'id' karena dipakai untuk link show/edit/destroy.
                $lokasi = $lokasi ?? collect([
                    [
                        'id' => 1,
                        'no' => 1,
                        'nama' => 'Kantor Dinas Koperasi Prov. Jateng',
                        'utama' => true,
                        'alamat' => 'Jl. Pahlawan No. 17, Semarang, Jawa Tengah',
                        'latitude' => '-6.986931',
                        'longitude' => '110.420279',
                        'radius' => '100 Meter',
                        'jumlah_pegawai' => 156,
                        'status' => 'Aktif',
                    ],
                    [
                        'id' => 2,
                        'no' => 2,
                        'nama' => 'Kantor Cabang Bidang UMKM',
                        'utama' => false,
                        'alamat' => 'Jl. Pemuda No. 45, Semarang, Jawa Tengah',
                        'latitude' => '-6.975432',
                        'longitude' => '110.415678',
                        'radius' => '75 Meter',
                        'jumlah_pegawai' => 32,
                        'status' => 'Aktif',
                    ],
                    [
                        'id' => 3,
                        'no' => 3,
                        'nama' => 'Kantor Cabang Bidang Pengawasan',
                        'utama' => false,
                        'alamat' => 'Jl. Diponegoro No. 8, Semarang, Jawa Tengah',
                        'latitude' => '-6.993456',
                        'longitude' => '110.408765',
                        'radius' => '50 Meter',
                        'jumlah_pegawai' => 18,
                        'status' => 'Nonaktif',
                    ],
                ]);
            @endphp

            {{-- Daftar Lokasi Kantor --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">

                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">Daftar Lokasi Kantor</h3>
                    <div class="flex items-center gap-2">
                        <button type="button"
                                class="flex items-center gap-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-semibold px-4 py-2.5 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5M4 9a8 8 0 0 1 14.5-4.5M20 15a8 8 0 0 1-14.5 4.5"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 text-xs">
                                <th class="text-left font-medium px-4 py-3 w-10">No</th>
                                <th class="text-left font-medium px-4 py-3">Nama Lokasi</th>
                                <th class="text-left font-medium px-4 py-3">Alamat</th>
                                <th class="text-left font-medium px-4 py-3">Latitude</th>
                                <th class="text-left font-medium px-4 py-3">Longitude</th>
                                <th class="text-left font-medium px-4 py-3">Radius</th>
                                <th class="text-center font-medium px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($lokasi as $row)
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-4 py-3 text-gray-500">{{ $row['no'] }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-gray-800">{{ $row['nama'] }}</span>
                                            @if ($row['utama'])
                                                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600">Utama</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500 max-w-[200px] truncate" title="{{ $row['alamat'] }}">{{ $row['alamat'] }}</td>
                                    <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $row['latitude'] }}</td>
                                    <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $row['longitude'] }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $row['radius'] }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <a href="{{ route('admin.lokasi-kantor.show', $row['id']) }}"
                                               title="Lihat Detail"
                                               class="inline-flex items-center justify-center w-7 h-7 rounded-full text-gray-400 hover:bg-gray-100 hover:text-emerald-600 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.lokasi-kantor.edit', $row['id']) }}"
                                               title="Edit Lokasi"
                                               class="inline-flex items-center justify-center w-7 h-7 rounded-full text-gray-400 hover:bg-gray-100 hover:text-emerald-600 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 4H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.lokasi-kantor.destroy', $row['id']) }}" method="POST" class="inline" onsubmit="return confirm('Hapus lokasi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        title="Hapus Lokasi"
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
                    <p class="text-sm text-gray-400">Menampilkan 1 - {{ $lokasi->count() }} dari {{ $lokasi->count() }} data</p>
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
    @include('admin.components.footer')
    </div>

</div>

@endsection