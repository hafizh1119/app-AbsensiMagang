@extends('layouts.app')

@section('title', 'Profil')

@section('content')

<div class="min-h-screen bg-gray-50">
@include('pengguna.components.header', ['title' => 'Absensi'])
    {{-- Header --}}
    <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 px-5 pt-5 pb-24 rounded-b-3xl relative overflow-hidden">
        {{-- Dekorasi lingkaran --}}
        <div class="absolute -top-8 -right-10 w-32 h-32 rounded-full bg-white/10"></div>
        <div class="absolute top-16 -right-4 w-16 h-16 rounded-full bg-white/10"></div>

        {{-- Avatar --}}
        <div class="flex flex-col items-center mt-4 relative z-10">
            <div class="relative">
                <img src="{{ $user->foto_url ?? asset('images/default-avatar.png') }}"
                     alt="Foto Profil"
                     class="w-24 h-24 rounded-full object-cover bg-white ring-4 ring-white">
                <button type="button"
                        class="absolute bottom-0 right-0 bg-white rounded-full p-1.5 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                        <circle cx="12" cy="13" r="4"></circle>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Name + Role (overlapping header) --}}
    <div class="text-center -mt-16 mb-8 relative z-10">
        <h2 class="text-white text-2xl font-bold tracking-tight drop-shadow-sm">{{ $user->name ?? 'Budi Santoso' }}</h2>
        <span class="inline-flex items-center gap-1.5 mt-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-sm border border-white/20">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="7" width="20" height="14" rx="2"></rect>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
            </svg>
            <span class="text-white text-sm font-medium">{{ $user->jabatan ?? 'Staf Keuangan' }}</span>
        </span>
    </div>

    <div class="pb-24">

        {{-- Informasi Pribadi Card --}}
        <div class="px-5 mt-2 relative z-10">
            <div class="bg-white rounded-2xl shadow-lg p-5">
                <h3 class="font-semibold text-gray-800 mb-4">Informasi Pribadi</h3>

                <div class="space-y-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">NIP</span>
                        <span class="text-gray-800 font-medium">{{ $user->nip ?? '199203152019031005' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Email</span>
                        <span class="text-gray-800 font-medium">{{ $user->email ?? 'budi.santoso@kopjateng.go.id' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">No. Telepon</span>
                        <span class="text-gray-800 font-medium">{{ $user->telepon ?? '0812-3456-7890' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Unit Kerja</span>
                        <span class="text-gray-800 font-medium">{{ $user->unit_kerja ?? 'Keuangan' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Menu Card --}}
        <div class="px-5 mt-4">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

                <div x-data="{ open: false }">
                    <button type="button" @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 border-b border-gray-100 active:bg-gray-50">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
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
                        class="px-5 py-4 bg-gray-50 border-b border-gray-100">
                        {{-- Isi konten Data Diri di sini --}}
                        <p class="text-sm text-gray-600">Nama, alamat, tanggal lahir, dll.</p>
                    </div>
                </div>

                <div x-data="{ open: false }">
                    <button type="button" @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 border-b border-gray-100 active:bg-gray-50">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M4 7V4a1 1 0 0 1 1-1h3"></path>
                                <path d="M20 7V4a1 1 0 0 0-1-1h-3"></path>
                                <path d="M4 17v3a1 1 0 0 0 1 1h3"></path>
                                <path d="M20 17v3a1 1 0 0 1-1 1h-3"></path>
                                <circle cx="12" cy="11" r="3"></circle>
                                <path d="M8 16c0.5-2 2-3 4-3s3.5 1 4 3"></path>
                            </svg>
                            <span class="text-gray-800 font-medium">Registrasi / Ubah Wajah</span>
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
                        class="px-5 py-4 bg-gray-50 border-b border-gray-100">
                        {{-- Isi konten Registrasi / Ubah Wajah di sini --}}
                        <p class="text-sm text-gray-600 mb-3">Silakan lakukan registrasi atau perbarui data wajah Anda.</p>
                        <a href="{{ url('/profil/registrasi-wajah') }}" class="inline-block px-4 py-2 bg-green-700 text-white text-sm font-medium rounded-lg active:bg-green-800">
                            Buka Halaman Registrasi Wajah
                        </a>
                    </div>
                </div>

                <div x-data="{ open: false }">
                    <button type="button" @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 active:bg-gray-50">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
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
                        class="px-5 py-4 bg-gray-50">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Password Lama</label>
                                <input type="password" name="current_password"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-600">
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Password Baru</label>
                                <input type="password" name="password"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-600">
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-600">
                            </div>

                            <button type="button" class="w-full bg-green-700 text-white text-sm font-medium py-2 rounded-lg active:bg-green-800">
                                Simpan Password Baru
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Card Keluar --}}
        <div class="px-5 mt-4">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <form method="POST" action="#>
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-between px-5 py-4 active:bg-gray-50">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span class="text-red-500 font-medium">Keluar</span>
                        </div>
                    </button>
                </form>
            </div>
        </div>

    </div>

</div>
@include('pengguna.components.menu')
@endsection