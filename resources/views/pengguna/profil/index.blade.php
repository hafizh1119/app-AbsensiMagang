@extends('layouts.app')

@section('title', 'Profil')

@section('content')

@php
    $statusPeserta = $user->status_peserta ? ucfirst($user->status_peserta) : '-';
    $jenisKelamin = match ($user->jenis_kelamin) {
        'L' => 'Laki-laki',
        'P' => 'Perempuan',
        default => '-',
    };

    $labelAsal    = $user->status_peserta === 'mahasiswa' ? 'Perguruan Tinggi' : ($user->status_peserta === 'siswa' ? 'Sekolah' : 'Sekolah / Perguruan Tinggi');
    $labelJurusan = $user->status_peserta === 'mahasiswa' ? 'Program Studi' : ($user->status_peserta === 'siswa' ? 'Jurusan' : 'Jurusan / Prodi');
    $labelKelas   = $user->status_peserta === 'mahasiswa' ? 'Semester' : ($user->status_peserta === 'siswa' ? 'Kelas' : 'Kelas / Semester');

    $inisial = strtoupper(mb_substr(trim($user->nama ?? ''), 0, 1)) ?: '?';
@endphp

<div class="min-h-screen bg-gray-50">
@include('pengguna.components.header', ['title' => 'Profil'])
    {{-- Header --}}
    <div class="bg-gradient-to-br from-sky-400 via-blue-500 to-indigo-600 px-5 pt-5 pb-24 rounded-b-3xl relative overflow-hidden">
        {{-- Dekorasi lingkaran --}}
        <div class="absolute -top-8 -right-10 w-32 h-32 rounded-full bg-white/15"></div>
        <div class="absolute top-16 -right-4 w-16 h-16 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-10 -left-8 w-28 h-28 rounded-full bg-white/10"></div>

        {{-- Avatar --}}
        <div class="flex flex-col items-center mt-4 relative z-10">
            <div class="relative">
                @if ($user->foto)
                    <img src="{{ asset('foto-pegawai/'.$user->foto) }}"
                        alt="Foto Profil"
                        class="w-24 h-24 rounded-full object-cover bg-white ring-4 ring-white">
                @else
                    <div class="w-24 h-24 rounded-full bg-white/20 backdrop-blur-sm ring-4 ring-white flex items-center justify-center text-white text-4xl font-bold">
                        {{ $inisial }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Name + Unit (overlapping header) --}}
    <div class="text-center -mt-16 mb-8 relative z-10">
        <h2 class="text-white text-2xl font-bold tracking-tight drop-shadow-sm">{{ $user->nama }}</h2>
        <span class="inline-flex items-center gap-1.5 mt-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-sm border border-white/20">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="7" width="20" height="14" rx="2"></rect>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
            </svg>
            <span class="text-white text-sm font-medium">{{ $user->unit->nama_unit ?? '-' }}</span>
        </span>
    </div>

    <div class="pb-24">

        {{-- Alert sukses/gagal --}}
        <div class="px-5">
            @if (session('success'))
                <div class="mb-4 rounded-xl bg-sky-50 border border-sky-200 px-4 py-2.5 text-sm text-sky-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-xl bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif
        </div>

        {{-- Informasi Peserta Card --}}
        <div class="px-5 mt-2 relative z-10">
            <div class="bg-white rounded-2xl shadow-lg p-5">
                <h3 class="font-semibold text-gray-800 mb-4">Informasi Peserta</h3>

                <div class="space-y-4 text-sm">
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Nama Lengkap</span>
                        <span class="text-gray-800 font-medium text-right">{{ $user->nama ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Status Peserta</span>
                        <span class="text-gray-800 font-medium text-right">{{ $statusPeserta }}</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Ruangan Penempatan</span>
                        <span class="text-gray-800 font-medium text-right">{{ $user->unit->nama_unit ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Mulai Magang</span>
                        <span class="text-gray-800 font-medium text-right">{{ $user->tanggal_mulai_magang?->translatedFormat('d F Y') ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Selesai Magang</span>
                        <span class="text-gray-800 font-medium text-right">{{ $user->tanggal_selesai_magang?->translatedFormat('d F Y') ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Asal Pendidikan --}}
        <div class="px-5 mt-4">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div x-data="{ open: false }">
                    <button type="button" @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M22 10L12 5 2 10l10 5 10-5z"></path>
                                <path d="M6 12v5c0 1.5 2.7 3 6 3s6-1.5 6-3v-5"></path>
                            </svg>
                            <span class="text-gray-800 font-medium">Asal Pendidikan</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4 text-gray-400 transition-transform duration-300"
                            :class="{ 'rotate-90': open }"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>

                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                        style="display: none;"
                        class="px-5 py-4 bg-gray-50">
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">{{ $labelAsal }}</span>
                                <span class="text-gray-800 font-medium text-right">{{ $user->asal_instansi ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">{{ $labelJurusan }}</span>
                                <span class="text-gray-800 font-medium text-right">{{ $user->jurusan ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">{{ $labelKelas }}</span>
                                <span class="text-gray-800 font-medium text-right">{{ $user->kelas_semester ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Data Diri --}}
        <div class="px-5 mt-4">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div x-data="{ open: false }">
                    <button type="button" @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <path d="M14 2v6h6"></path>
                                <line x1="9" y1="13" x2="15" y2="13"></line>
                                <line x1="9" y1="17" x2="15" y2="17"></line>
                            </svg>
                            <span class="text-gray-800 font-medium">Data Diri</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4 text-gray-400 transition-transform duration-300"
                            :class="{ 'rotate-90': open }"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>

                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                        style="display: none;"
                        class="px-5 py-4 bg-gray-50">
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">Jenis Kelamin</span>
                                <span class="text-gray-800 font-medium text-right">{{ $jenisKelamin }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">Email</span>
                                <span class="text-gray-800 font-medium text-right break-all">{{ $user->email ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">No. HP</span>
                                <span class="text-gray-800 font-medium text-right">{{ $user->no_hp ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">Alamat Domisili</span>
                                <span class="text-gray-800 font-medium text-right">{{ $user->alamat ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">Status Akun</span>
                                <span class="text-gray-800 font-medium text-right">{{ ucfirst($user->status ?? '-') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Ubah Password --}}
        <div class="px-5 mt-4">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div x-data="{ open: false }">
                    <button type="button" @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="4" y="11" width="16" height="10" rx="2"></rect>
                                <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                            </svg>
                            <span class="text-gray-800 font-medium">Ubah Password</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4 text-gray-400 transition-transform duration-300"
                            :class="{ 'rotate-90': open }"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>

                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                        style="display: none;"
                        class="px-5 py-4 bg-gray-50">
                        <form method="POST" action="{{ route('pengguna.profil.password.update') }}" class="space-y-3">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Password Lama</label>
                                <input type="password" name="current_password"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Password Baru</label>
                                <input type="password" name="password"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <button type="submit" class="w-full bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white text-sm font-medium py-2 rounded-lg shadow-sm shadow-blue-500/30 transition">
                                Simpan Password Baru
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Keluar --}}
        <div class="px-5 mt-4">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-between px-5 py-4 hover:bg-red-50 transition duration-200 group">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500 group-hover:text-red-600 transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span class="text-red-500 font-medium group-hover:text-red-600 transition">Keluar</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-400 group-hover:text-red-500 transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

    </div>

</div>
@include('pengguna.components.menu')
@endsection