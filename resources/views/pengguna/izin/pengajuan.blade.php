@extends('layouts.app')

@section('title', 'Pengajuan Izin')

@section('content')

@include('pengguna.components.header', ['title' => 'Pengajuan Izin'])

<div class="min-h-screen bg-gray-50 pb-28">

    <div class="px-5 pt-4">
        <p class="text-sm text-gray-500">Pilih jenis izin yang ingin Anda ajukan</p>
    </div>

    {{-- List Jenis Izin --}}
    <div class="px-5 mt-4 space-y-3">
        @forelse($jenisIzinList as $jenis)
            <a href="{{ route('pengguna.izin.form', ['jenis' => $jenis->id]) }}" 
               class="block bg-white rounded-2xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    {{-- Icon --}}
                    <div class="shrink-0 w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center">
                        @if($jenis->nama_izin == 'Sakit')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 1 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path>
                                <polyline points="7.5 12 10 12 11 10 13 14 14 12 16.5 12"></polyline>
                            </svg>
                        @elseif($jenis->nama_izin == 'Cuti Tahunan')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="12" y1="18" x2="12" y2="12"></line>
                                <line x1="9" y1="15" x2="15" y2="15"></line>
                            </svg>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold text-gray-800">{{ $jenis->nama_izin }}</h3>
                        @if($jenis->deskripsi)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $jenis->deskripsi }}</p>
                        @endif
                        @if($jenis->kuota)
                            <p class="text-xs text-gray-400 mt-1">Kuota: {{ $jenis->kuota }} hari</p>
                        @endif
                    </div>

                    {{-- Arrow --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>
        @empty
            <div class="text-center py-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-300 mx-auto mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="12" y1="18" x2="12" y2="12"></line>
                    <line x1="9" y1="15" x2="15" y2="15"></line>
                </svg>
                <p class="text-sm text-gray-500">Belum ada jenis izin yang tersedia</p>
                <p class="text-xs text-gray-400 mt-1">Silakan hubungi administrator</p>
            </div>
        @endforelse
    </div>

    {{-- Riwayat Izin --}}
    <div class="px-5 mt-6">
        <a href="{{ route('pengguna.izin.riwayat') }}" 
           class="flex items-center justify-center gap-2 w-full bg-white rounded-2xl shadow-sm border border-gray-100 py-3.5 hover:bg-gray-50 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="9"></circle>
                <polyline points="12 7 12 12 15 15"></polyline>
            </svg>
            <span class="text-sm font-medium text-gray-700">Lihat Riwayat Izin</span>
        </a>
    </div>

</div>

@include('pengguna.components.menu')
@endsection