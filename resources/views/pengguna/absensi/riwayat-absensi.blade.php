@extends('layouts.app')

@section('title', 'Riwayat Absensi')

@section('content')

@include('pengguna.components.header', ['title' => 'Riwayat Absensi'])

<div class="min-h-screen bg-gray-50 px-5 py-4">

    {{-- Filter: Bulan, Tahun, & Kalender dalam satu baris --}}
    <div class="flex items-center gap-2 mb-4">

        <div class="relative flex-1">
            <select name="bulan"
                    class="w-full appearance-none bg-white border border-gray-200 rounded-xl pl-3 pr-9 py-2.5 text-sm font-medium text-gray-800 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="1">Januari</option>
                <option value="2">Februari</option>
                <option value="3">Maret</option>
                <option value="4">April</option>
                <option value="5" selected>Mei</option>
                <option value="6">Juni</option>
                <option value="7">Juli</option>
                <option value="8">Agustus</option>
                <option value="9">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </div>

        <div class="relative flex-1">
            <select name="tahun"
                    class="w-full appearance-none bg-white border border-gray-200 rounded-xl pl-3 pr-9 py-2.5 text-sm font-medium text-gray-800 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025" selected>2025</option>
                <option value="2026">2026</option>
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

    </div>

    {{-- Daftar Riwayat --}}
    <div class="space-y-3">

        @php
            $riwayat = $riwayat ?? [
                ['hari' => 'Senin', 'tanggal' => '26 Mei 2025', 'masuk' => '07:58', 'pulang' => null, 'status' => 'belum_pulang'],
                ['hari' => 'Jumat', 'tanggal' => '23 Mei 2025', 'masuk' => '07:45', 'pulang' => '16:32', 'status' => 'hadir'],
                ['hari' => 'Kamis', 'tanggal' => '22 Mei 2025', 'masuk' => '07:50', 'pulang' => '16:30', 'status' => 'hadir'],
                ['hari' => 'Rabu', 'tanggal' => '21 Mei 2025', 'masuk' => '07:55', 'pulang' => '16:28', 'status' => 'hadir'],
                ['hari' => 'Selasa', 'tanggal' => '20 Mei 2025', 'masuk' => '08:10', 'pulang' => '16:40', 'status' => 'terlambat'],
            ];
        @endphp

        @foreach ($riwayat as $item)
            <a href="{{ url('/riwayat-absensi/' . $loop->index) }}"
               class="flex items-center justify-between bg-white rounded-2xl shadow-sm px-5 py-4 active:bg-gray-50">

                <div class="flex-1">
                    <p class="text-gray-900 font-semibold mb-2">{{ $item['hari'] }}, {{ $item['tanggal'] }}</p>

                    <div class="flex items-center gap-8">
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Masuk</p>
                            <p class="text-sm font-semibold text-emerald-600">{{ $item['masuk'] }}</p>
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

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>

            </a>
        @endforeach

    </div>

</div>
@include('pengguna.components.menu')
@endsection