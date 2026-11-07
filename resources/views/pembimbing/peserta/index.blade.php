{{-- resources/views/pembimbing/peserta/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Peserta')

@section('content')
<div class="min-h-screen bg-gray-50 pb-24">

    @include('pembimbing.components.header', [
        'title' => 'Data Peserta',
        'back'  => route('pembimbing.dashboard'),
    ])

    {{-- Pencarian & Filter --}}
    <div class="px-4 pt-4">
        <div class="flex items-center gap-3">

            {{-- Search --}}
            <form action="{{ route('pembimbing.peserta.index') }}" method="GET" class="flex-1 relative">
                <input type="hidden" name="status" value="{{ $activeTab }}">

                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="7"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/>
                </svg>
                <input type="text"
                       name="q"
                       value="{{ $keyword }}"
                       placeholder="Cari nama peserta..."
                       class="w-full bg-white border border-gray-200 rounded-xl pl-10 pr-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
            </form>

            {{-- Filter --}}
            <button type="button"
                    class="shrink-0 w-11 h-11 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-sky-500 hover:border-sky-300 transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18l-7 8v6l-4 2v-8L3 4z"/>
                </svg>
            </button>

        </div>

        {{-- Tab Aktif / Selesai --}}
        <div class="mt-4 bg-gray-200/70 rounded-full p-1 flex items-center">
            <a href="{{ route('pembimbing.peserta.index', ['status' => 'aktif', 'q' => $keyword ?: null]) }}"
               class="flex-1 text-center py-2 rounded-full text-sm font-semibold transition-all duration-200
               {{ $activeTab === 'aktif' ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-sm shadow-blue-500/30' : 'text-gray-500' }}">
                Aktif ({{ $jumlahAktif }})
            </a>
            <a href="{{ route('pembimbing.peserta.index', ['status' => 'selesai', 'q' => $keyword ?: null]) }}"
               class="flex-1 text-center py-2 rounded-full text-sm font-semibold transition-all duration-200
               {{ $activeTab === 'selesai' ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-sm shadow-blue-500/30' : 'text-gray-500' }}">
                Selesai ({{ $jumlahSelesai }})
            </a>
        </div>
    </div>

    {{-- Daftar Peserta --}}
    <div class="px-4 py-4 space-y-3">

        @forelse ($pesertaList as $peserta)
            <a href="{{ route('pembimbing.peserta.detail', $peserta->id) }}"
               class="flex items-center gap-3 bg-white rounded-2xl shadow-sm border border-gray-100 px-4 py-4 active:bg-gray-50 group">

                {{-- Avatar --}}
                <div class="shrink-0 w-12 h-12 rounded-full bg-sky-100 text-blue-500 flex items-center justify-center overflow-hidden">
                    @if($peserta->foto)
                        <img src="{{ asset('foto-pegawai/'.$peserta->foto) }}" alt="Foto {{ $peserta->nama }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4.42 0-8 2.24-8 5v1h16v-1c0-2.76-3.58-5-8-5z"/>
                        </svg>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <p class="text-gray-900 font-bold text-sm truncate">{{ $peserta->nama }}</p>
                    <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $peserta->jurusan ?? '-' }}</p>
                    <p class="text-xs font-semibold text-gray-700 mt-0.5 truncate">{{ $peserta->asal_instansi ?? '-' }}</p>
                </div>

                {{-- Status --}}
                @if($activeTab === 'aktif')
                    <span class="shrink-0 px-3 py-1 rounded-full bg-emerald-100 text-emerald-600 text-[11px] font-semibold">
                        Aktif
                    </span>
                @else
                    <span class="shrink-0 px-3 py-1 rounded-full bg-gray-100 text-gray-500 text-[11px] font-semibold">
                        Selesai
                    </span>
                @endif

                <svg class="shrink-0 w-4 h-4 text-gray-400 group-hover:text-blue-500 transition-colors duration-200" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>

            </a>
        @empty
            <div class="text-center py-16">
                <p class="text-gray-400 text-sm">
                    @if($keyword !== '')
                        Peserta dengan nama "{{ $keyword }}" tidak ditemukan.
                    @elseif($activeTab === 'aktif')
                        Belum ada peserta aktif.
                    @else
                        Belum ada peserta yang selesai magang.
                    @endif
                </p>
            </div>
        @endforelse

    </div>

</div>

@include('pembimbing.components.menu')

@endsection