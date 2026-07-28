@extends('layouts.app')

@section('title', 'Jadwal Kerja')

@section('content')

@include('pengguna.components.header', ['title' => 'Jadwal Kerja'])

<div class="min-h-screen bg-gray-50 px-5 py-4">

    {{-- Filter: Bulan & Tahun --}}
    <form method="GET" action="{{ route('pengguna.jadwal') }}" class="flex items-center gap-2 mb-4">

        <div class="relative flex-1">
            <select name="bulan" onchange="this.form.submit()"
                    class="w-full appearance-none bg-white border border-gray-200 rounded-xl pl-3 pr-9 py-2.5 text-sm font-medium text-gray-800 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @php
                    $namaBulanList = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                    ];
                @endphp
                @foreach ($namaBulanList as $angka => $nama)
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
                @foreach (range(now()->year - 1, now()->year + 1) as $tahunOpsi)
                    <option value="{{ $tahunOpsi }}" {{ $tahun == $tahunOpsi ? 'selected' : '' }}>{{ $tahunOpsi }}</option>
                @endforeach
            </select>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </div>

    </form>

    {{-- Ringkasan singkat --}}
    <div class="bg-white rounded-2xl shadow-sm px-5 py-4 mb-4">
        <p class="text-gray-900 font-semibold mb-1">{{ ucfirst($namaBulan) }}</p>
        <p class="text-sm text-gray-500">
            Jam kerja (Senin–Jumat): <span class="font-medium text-gray-700">{{ $shiftMasuk }} - {{ $shiftPulang }}</span>
        </p>
    </div>

    {{-- Kalender --}}
    <div class="bg-white rounded-2xl shadow-sm p-4 mb-4">

        {{-- Header hari --}}
        <div class="grid grid-cols-7 mb-2">
            @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $h)
                <div class="text-center text-xs font-medium text-gray-400 py-1">{{ $h }}</div>
            @endforeach
        </div>

        {{-- Grid tanggal --}}
        <div class="grid grid-cols-7 gap-1.5">

            {{-- Sel kosong sebelum tanggal 1 --}}
            @for ($i = 1; $i < $hariPertama; $i++)
                <div></div>
            @endfor

            @for ($tgl = 1; $tgl <= $jumlahHari; $tgl++)
                @php
                    $tanggalObj = \Carbon\Carbon::createFromDate($tahun, $bulan, $tgl);
                    $tanggalKey = $tanggalObj->format('Y-m-d');
                    $isWeekend   = $tanggalObj->isSunday();
                    $namaLibur  = $liburNasional[$tanggalKey] ?? null;
                    $isLibur    = $isWeekend || $namaLibur;
                    $isHariIni  = $tanggalKey === $hariIni;
                @endphp

                <div class="aspect-square rounded-xl flex flex-col items-center justify-center relative
                            {{ $isLibur ? 'bg-red-50' : 'bg-emerald-50' }}
                            {{ $isHariIni ? 'ring-2 ring-emerald-600' : '' }}"
                     title="{{ $namaLibur ?? ($isLibur ? 'Libur' : 'Hari kerja') }}">

                    <span class="text-sm font-semibold {{ $isLibur ? 'text-red-500' : 'text-emerald-700' }}">
                        {{ $tgl }}
                    </span>

                    <span class="w-1 h-1 rounded-full mt-0.5 {{ $isLibur ? 'bg-red-400' : 'bg-emerald-500' }}"></span>
                </div>
            @endfor

        </div>

        {{-- Legenda --}}
        <div class="flex items-center gap-4 mt-4 pt-4 border-t border-gray-100">
            <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span class="text-xs text-gray-500">Hari Kerja</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                <span class="text-xs text-gray-500">Libur</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-md ring-2 ring-emerald-600"></span>
                <span class="text-xs text-gray-500">Hari Ini</span>
            </div>
        </div>
    </div>

    {{-- Daftar hari libur bulan ini --}}
    @php
        $daftarLibur = collect($liburNasional)
            ->filter(fn ($nama, $tgl) => \Carbon\Carbon::parse($tgl)->month == $bulan && \Carbon\Carbon::parse($tgl)->year == $tahun)
            ->sortKeys();
    @endphp

    <div class="bg-white rounded-2xl shadow-sm px-5 py-4 mb-20">
        <p class="text-gray-900 font-semibold mb-3">Libur Nasional Bulan Ini</p>

        @forelse ($daftarLibur as $tgl => $nama)
            <div class="flex items-center justify-between py-1.5 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($tgl)->translatedFormat('d F Y') }}</span>
                <span class="text-sm font-medium text-red-500">{{ $nama }}</span>
            </div>
        @empty
            <p class="text-sm text-gray-400">Tidak ada hari libur nasional bulan ini.</p>
        @endforelse
    </div>

</div>
@endsection