@extends('layouts.app')

@section('title', 'Riwayat Absensi')

@section('content')

@include('pengguna.components.header', ['title' => 'Riwayat Absensi'])

<div class="min-h-screen bg-gray-50 px-5 py-4">

    {{-- Filter: Bulan & Tahun --}}
    <form method="GET" action="{{ route('pengguna.riwayat') }}" class="flex items-center gap-2 mb-4">

        <div class="relative flex-1">
            <select name="bulan" onchange="this.form.submit()"
                    class="w-full appearance-none bg-white border border-gray-200 rounded-xl pl-3 pr-9 py-2.5 text-sm font-medium text-gray-800 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @php
                    $namaBulan = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                    ];
                @endphp
                @foreach ($namaBulan as $angka => $nama)
                    <option value="{{ $angka }}" {{ $bulan == $angka ? 'selected' : '' }}>{{ $nama }}</option>
                @endforeach
            </select>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </div>

        <div class="relative flex-1">
            <select name="tahun" onchange="this.form.submit()"
                    class="w-full appearance-none bg-white border border-gray-200 rounded-xl pl-3 pr-9 py-2.5 text-sm font-medium text-gray-800 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @foreach (range(now()->year, now()->year - 3) as $tahunOpsi)
                    <option value="{{ $tahunOpsi }}" {{ $tahun == $tahunOpsi ? 'selected' : '' }}>{{ $tahunOpsi }}</option>
                @endforeach
            </select>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </div>

        <button type="button"
                class="shrink-0 w-11 h-11 flex items-center justify-center bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
        </button>

    </form>

    {{-- Daftar Riwayat --}}
    <div class="space-y-3">

        @forelse ($riwayat as $item)
            <div class="flex items-center justify-between bg-white rounded-2xl shadow-sm px-5 py-4">

                <div class="flex-1">
                    <p class="text-gray-900 font-semibold mb-2">{{ $item['hari'] }}, {{ $item['tanggal'] }}</p>

                    <div class="flex items-center gap-8">
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Masuk</p>
                            <p class="text-sm font-semibold {{ $item['masuk'] ? 'text-emerald-600' : 'text-gray-400' }}">
                                {{ $item['masuk'] ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Pulang</p>
                            <p class="text-sm font-semibold {{ $item['pulang'] ? 'text-emerald-600' : 'text-gray-400' }}">
                                {{ $item['pulang'] ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    @if ($item['status'] === 'hadir')
                        <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-medium">Hadir</span>
                    @elseif ($item['status'] === 'terlambat')
                        <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-medium">Terlambat</span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-medium">Belum Pulang</span>
                    @endif
                </div>

            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm px-5 py-8 text-center">
                <p class="text-sm text-gray-400">Belum ada riwayat absensi pada bulan ini.</p>
            </div>
        @endforelse

    </div>

</div>
@endsection