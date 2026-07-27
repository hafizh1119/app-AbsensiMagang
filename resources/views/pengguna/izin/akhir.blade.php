@extends('layouts.app')

@section('title', 'Pengajuan Berhasil')

@section('content')

<div class="h-screen bg-gray-50 flex flex-col overflow-hidden">

    <div class="shrink-0">
        @include('pengguna.components.header', ['title' => 'Pengajuan Berhasil'])
    </div>

    {{-- Step Indicator --}}
    <div class="shrink-0 flex items-center justify-center gap-3 px-5 pt-4 pb-3">
        <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center text-sm font-semibold">1</div>
        <div class="w-10 border-t-2 border-dashed border-gray-300"></div>
        <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center text-sm font-semibold">2</div>
        <div class="w-10 border-t-2 border-dashed border-gray-300"></div>
        <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center text-sm font-semibold">3</div>
    </div>

    <div class="flex-1 px-5 pb-4 min-h-0">

        {{-- Card Putih --}}
        <div class="h-full bg-white rounded-2xl shadow-sm px-6 py-6 flex flex-col items-center justify-center text-center">

            {{-- Icon --}}
            <img src="{{ asset('icon/berhasil.svg') }}" alt="Berhasil" class="w-24 h-24 mb-4">

            {{-- Judul & Deskripsi --}}
            <h2 class="text-lg font-bold text-gray-900 mb-1.5">Pengajuan Berhasil!</h2>
            <p class="text-sm text-gray-500 leading-relaxed mb-4 max-w-xs">
                Pengajuan izin Anda telah berhasil dikirim. Menunggu persetujuan atasan.
            </p>

            {{-- Nomor Pengajuan --}}
            <div class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-3 mb-5">
                <div class="flex items-center justify-between">
                    <div class="text-left">
                        <p class="text-xs text-gray-400 mb-1">Nomor Pengajuan</p>
                        <p id="nomor-pengajuan" class="text-gray-900 font-bold tracking-wide">IZN-20250526-00123</p>
                    </div>
                    <button type="button" id="salin-nomor" class="shrink-0 text-gray-400 hover:text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="9" y="9" width="13" height="13" rx="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="w-full">
                <a href="{{ route('pengguna.izin.riwayat') }}"
                   class="block w-full text-center bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-2xl py-3 shadow-sm mb-3">
                    Lihat Riwayat Izin
                </a>

                <a href="{{ route('pengguna.home') }}"
                   class="block text-center text-emerald-600 font-semibold text-sm hover:underline">
                    Kembali ke Dashboard
                </a>
            </div>

        </div>

    </div>

</div>

<script>
    document.getElementById('salin-nomor').addEventListener('click', function () {
        const teks = document.getElementById('nomor-pengajuan').textContent;
        navigator.clipboard.writeText(teks);
    });
</script>

@endsection