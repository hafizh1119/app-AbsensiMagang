@extends('layouts.app')

@section('title', 'Pengajuan Izin')

@section('content')

@include('pengguna.components.header', ['title' => 'Pengajuan Izin'])

<div class="min-h-screen bg-gray-50 px-5 py-5">

    <h2 class="text-xl font-bold text-gray-900 mb-1">Pilih Jenis Izin</h2>
    <p class="text-sm text-gray-500 mb-5">Pilih jenis izin yang akan diajukan</p>

    <div class="space-y-3">

        {{-- Izin Sakit --}}
        <a href="{{ route('pengguna.izin.form') }}"
           class="flex items-start gap-4 bg-white rounded-2xl shadow-sm px-5 py-4 active:bg-gray-50">
            <div class="shrink-0 w-11 h-11 rounded-full bg-emerald-50 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 1 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path>
                    <polyline points="7.5 12 10 12 11 10 13 14 14 12 16.5 12"></polyline>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-gray-900 font-semibold mb-1">Izin Sakit</p>
                <p class="text-sm text-gray-500 leading-relaxed">Izin karena kondisi kesehatan yang tidak memungkinkan untuk bekerja.</p>
            </div>
        </a>

        {{-- Izin Terlambat --}}
        <a href="{{ url('/pengajuan-izin/terlambat') }}"
           class="flex items-start gap-4 bg-white rounded-2xl shadow-sm px-5 py-4 active:bg-gray-50">
            <div class="shrink-0 w-11 h-11 rounded-full bg-amber-50 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="9"></circle>
                    <polyline points="12 7 12 12 15.5 14"></polyline>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-gray-900 font-semibold mb-1">Izin Terlambat</p>
                <p class="text-sm text-gray-500 leading-relaxed">Izin datang terlambat dari jam kerja yang telah ditentukan.</p>
            </div>
        </a>

        {{-- Izin Kepentingan Pribadi --}}
        <a href="{{ url('/pengajuan-izin/pribadi') }}"
           class="flex items-start gap-4 bg-white rounded-2xl shadow-sm px-5 py-4 active:bg-gray-50">
            <div class="shrink-0 w-11 h-11 rounded-full bg-violet-50 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-violet-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="8" r="4"></circle>
                    <path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"></path>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-gray-900 font-semibold mb-1">Izin Kepentingan Pribadi</p>
                <p class="text-sm text-gray-500 leading-relaxed">Izin untuk keperluan pribadi atau keluarga.</p>
            </div>
        </a>

        {{-- Izin Lainnya --}}
        <a href="{{ url('/pengajuan-izin/lainnya') }}"
           class="flex items-start gap-4 bg-white rounded-2xl shadow-sm px-5 py-4 active:bg-gray-50">
            <div class="shrink-0 w-11 h-11 rounded-full bg-gray-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="9"></circle>
                    <circle cx="8" cy="12" r="1" fill="currentColor" stroke="none"></circle>
                    <circle cx="12" cy="12" r="1" fill="currentColor" stroke="none"></circle>
                    <circle cx="16" cy="12" r="1" fill="currentColor" stroke="none"></circle>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-gray-900 font-semibold mb-1">Izin Lainnya</p>
                <p class="text-sm text-gray-500 leading-relaxed">Izin untuk keperluan lainnya.</p>
            </div>
        </a>

    </div>

</div>
@include('pengguna.components.menu')
@endsection