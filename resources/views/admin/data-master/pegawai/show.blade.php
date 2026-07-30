{{-- resources/views/admin/data-master/pegawai/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Pegawai')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Detail Pegawai'])

        <main class="p-6">

            @php
                $unit = $pegawai->unit;
                $tanggalBergabung = optional($pegawai->created_at)->translatedFormat('d F Y') ?? '-';
                $statusLabel = ucfirst($pegawai->status ?? '-');
            @endphp

            {{-- Judul & Breadcrumb --}}
            <div class="flex items-center justify-between mb-5">
                <div>
                    <a href="{{ route('admin.data-master.pegawai') }}"
                       class="flex items-center gap-2 text-lg font-bold text-gray-900 hover:text-sky-600 transition w-fit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Detail Pegawai
                    </a>
                    <p class="text-xs text-gray-400 mt-1">
                        <a href="{{ route('admin.data-master.pegawai') }}" class="hover:text-sky-600">Data Pegawai</a>
                        <span class="mx-1">›</span>
                        Detail Pegawai
                    </p>
                </div>
            </div>

            {{-- Card Profil --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-gray-100 border border-gray-200 overflow-hidden flex items-center justify-center flex-shrink-0">
                            @if ($pegawai->foto)
                                <img src="{{ asset('foto-pegawai/' . $pegawai->foto) }}" alt="{{ $pegawai->nama }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <circle cx="12" cy="8" r="4"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 20c0-4 3.5-6 8-6s8 2 8 6"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">{{ $pegawai->nama }}</h2>
                            <p class="text-sm text-gray-400">{{ $pegawai->jabatan }}</p>
                            <span class="inline-block mt-1.5 text-[11px] font-semibold px-2.5 py-1 rounded-full
                                {{ $pegawai->status === 'aktif' ? 'bg-sky-50 text-sky-600' : 'bg-rose-50 text-rose-500' }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informasi Pribadi --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
                <h3 class="text-sm font-bold text-gray-900 mb-4">Informasi Pribadi</h3>

                <dl class="space-y-4">
                    <div class="flex items-start gap-4">
                        <dt class="text-xs text-gray-400 w-36 flex-shrink-0">No. Peserta</dt>
                        <dd class="text-sm text-gray-700 font-medium">{{ $pegawai->nip }}</dd>
                    </div>
                    <div class="flex items-start gap-4">
                        <dt class="text-xs text-gray-400 w-36 flex-shrink-0">Tempat, Tgl Lahir</dt>
                        <dd class="text-sm text-gray-700 font-medium">
                            {{ $pegawai->tempat_lahir }}, {{ optional($pegawai->tanggal_lahir)->translatedFormat('d F Y') }}
                        </dd>
                    </div>
                    <div class="flex items-start gap-4">
                        <dt class="text-xs text-gray-400 w-36 flex-shrink-0">Kewarganegaraan</dt>
                        <dd class="text-sm text-gray-700 font-medium">
                            {{ $pegawai->kewarganegaraan }} ({{ $pegawai->status_kewarganegaraan }})
                        </dd>
                    </div>
                    <div class="flex items-start gap-4">
                        <dt class="text-xs text-gray-400 w-36 flex-shrink-0">No. Telepon</dt>
                        <dd class="text-sm text-gray-700 font-medium">{{ $pegawai->no_hp ?: '-' }}</dd>
                    </div>
                    <div class="flex items-start gap-4">
                        <dt class="text-xs text-gray-400 w-36 flex-shrink-0">Email</dt>
                        <dd class="text-sm text-gray-700 font-medium">{{ $pegawai->email }}</dd>
                    </div>
                    <div class="flex items-start gap-4">
                        <dt class="text-xs text-gray-400 w-36 flex-shrink-0">Alamat</dt>
                        <dd class="text-sm text-gray-700 font-medium">{{ $pegawai->alamat ?: '-' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Informasi Pekerjaan --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-bold text-gray-900 mb-4">Informasi Pekerjaan</h3>

                <dl class="space-y-4">
                    <div class="flex items-start gap-4">
                        <dt class="text-xs text-gray-400 w-36 flex-shrink-0">Unit</dt>
                        <dd class="text-sm text-gray-700 font-medium">{{ $unit->nama_unit ?? '-' }}</dd>
                    </div>
                    <div class="flex items-start gap-4">
                        <dt class="text-xs text-gray-400 w-36 flex-shrink-0">Jabatan</dt>
                        <dd class="text-sm text-gray-700 font-medium">{{ $pegawai->jabatan }}</dd>
                    </div>
                    <div class="flex items-start gap-4">
                        <dt class="text-xs text-gray-400 w-36 flex-shrink-0">Tanggal Bergabung</dt>
                        <dd class="text-sm text-gray-700 font-medium">{{ $tanggalBergabung }}</dd>
                    </div>
                    <div class="flex items-start gap-4">
                        <dt class="text-xs text-gray-400 w-36 flex-shrink-0">Status</dt>
                        <dd class="text-sm text-gray-700 font-medium">{{ $statusLabel }}</dd>
                    </div>
                </dl>
            </div>

        </main>
    @include('admin.components.footer')
    </div>

</div>

@endsection