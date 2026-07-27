@extends('layouts.app')

@section('title', 'Riwayat Pengajuan Izin')

@section('content')

<div class="min-h-screen bg-gray-50">

    @include('pengguna.components.header', ['title' => 'Riwayat Pengajuan Izin'])

    {{-- Tab Filter --}}
    <div class="bg-white px-4 border-b border-gray-100">
        <div class="flex items-center w-full">
            @php
                $tabs = ['semua' => 'Semua', 'menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'];
                $activeTab = $activeTab ?? 'semua';
            @endphp

            @foreach ($tabs as $key => $label)
                <a href="{{ url()->current() . '?status=' . $key }}"
                   class="flex-1 text-center py-3 text-sm font-semibold border-b-2 {{ $activeTab === $key ? 'text-gray-900 border-emerald-600' : 'text-gray-400 border-transparent' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Daftar Riwayat Izin --}}
    <div class="px-4 py-4 space-y-3">

        @php
            $izin = $izin ?? [
                ['jenis' => 'Izin Sakit', 'tanggal' => '26 Mei 2025 - 26 Mei 2025', 'kode' => 'IZN-20250526-00123', 'status' => 'menunggu', 'icon' => 'sakit'],
                ['jenis' => 'Izin Terlambat', 'tanggal' => '24 Mei 2025', 'kode' => 'IZN-20250524-00098', 'status' => 'disetujui', 'icon' => 'terlambat'],
                ['jenis' => 'Izin Kepentingan Pribadi', 'tanggal' => '20 Mei 2025 - 20 Mei 2025', 'kode' => 'IZN-20250520-00076', 'status' => 'ditolak', 'icon' => 'pribadi'],
                ['jenis' => 'Izin Sakit', 'tanggal' => '15 Mei 2025 - 16 Mei 2025', 'kode' => 'IZN-20250515-00045', 'status' => 'disetujui', 'icon' => 'sakit'],
            ];
        @endphp

        @foreach ($izin as $item)
            <a href="{{ route('pengguna.izin.riwayat', $item['kode'] ?? $loop->index) }}"
               class="flex items-start gap-3 bg-white rounded-2xl shadow-sm px-4 py-4 active:bg-gray-50">

                {{-- Icon --}}
                <div class="shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                            {{ $item['icon'] === 'sakit' ? 'bg-emerald-50' : ($item['icon'] === 'terlambat' ? 'bg-amber-50' : 'bg-red-50') }}">

                    @if ($item['icon'] === 'sakit')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 1 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path>
                            <polyline points="7.5 12 10 12 11 10 13 14 14 12 16.5 12"></polyline>
                        </svg>
                    @elseif ($item['icon'] === 'terlambat')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="9"></circle>
                            <polyline points="12 7 12 12 15.5 14"></polyline>
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="8" r="4"></circle>
                            <path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"></path>
                        </svg>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <p class="text-gray-900 font-semibold mb-1">{{ $item['jenis'] }}</p>
                    <p class="text-sm text-gray-500 mb-1">{{ $item['tanggal'] }}</p>
                    <p class="text-xs text-gray-400">{{ $item['kode'] }}</p>
                </div>

                {{-- Status --}}
                <div class="shrink-0">
                    @if ($item['status'] === 'disetujui')
                        <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-medium whitespace-nowrap">Disetujui</span>
                    @elseif ($item['status'] === 'ditolak')
                        <span class="px-3 py-1 rounded-full bg-red-50 text-red-500 text-xs font-medium whitespace-nowrap">Ditolak</span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-medium whitespace-nowrap">Menunggu</span>
                    @endif
                </div>

            </a>
        @endforeach

    </div>

</div>
@include('pengguna.components.menu')
@endsection