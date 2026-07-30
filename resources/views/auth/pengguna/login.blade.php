@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="h-screen bg-gradient-to-b from-sky-50 via-white to-blue-50 relative overflow-hidden flex flex-col justify-center">

    {{-- Dekorasi lengkung biru kiri atas --}}
    <div class="absolute -top-10 -left-24 w-72 h-72 rounded-full bg-sky-100/60 blur-2xl"></div>
    <div class="absolute top-24 -left-16 w-40 h-40 rounded-full bg-blue-200/50"></div>

    {{-- Dekorasi lengkung biru kiri bawah --}}
    <div class="absolute bottom-0 -left-20 w-64 h-64 rounded-full bg-sky-50 blur-xl"></div>

    <div class="relative w-full max-w-md mx-auto px-6">

        {{-- Logo --}}
        <div class="flex justify-center mb-4">
            <img
                src="{{ asset('icon/diskop.svg') }}"
                alt="Logo Dinas Koperasi Provinsi Jawa Tengah"
                class="w-72 h-auto object-contain">
        </div>

        {{-- Card Form Login --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-blue-900/5 border border-gray-100 mt-4 px-6 py-5">

            {{-- Header Card --}}
            <div class="flex items-center gap-3 mb-4">
                <div>
                    <h2 class="font-bold text-gray-800">Absensi Peserta Magang</h2>
                    <p class="text-xs text-gray-400">
                        Silakan masuk untuk melakukan absensi peserta magang
                    </p>
                </div>
            </div>

            {{-- Tampilkan pesan error validasi --}}
            @if ($errors->any())
                <div class="mb-3 rounded-xl bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-3">
                @csrf

                {{-- NIP / Email --}}
                <div>
                    <label for="identifier" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <div class="flex items-stretch rounded-xl border border-gray-300 overflow-hidden focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent transition">
                        <span class="flex items-center justify-center w-11 bg-gradient-to-br from-sky-400 to-blue-600 text-white shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <input type="text"
                            id="identifier"
                            name="identifier"
                            value="{{ old('identifier') }}"
                            placeholder="Masukkan email Anda"
                            class="w-full px-4 py-2.5 text-sm focus:outline-none">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <div class="flex items-stretch rounded-xl border border-gray-300 overflow-hidden focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent transition">
                        <span class="flex items-center justify-center w-11 bg-gradient-to-br from-sky-400 to-blue-600 text-white shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password Anda"
                            class="w-full px-4 py-2.5 text-sm focus:outline-none">
                        <button type="button" onclick="togglePassword()" class="flex items-center justify-center w-11 text-gray-400 hover:text-gray-600 shrink-0">
                            <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Tombol Masuk --}}
                <button type="submit"
                        class="w-full bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition duration-200 shadow-md shadow-blue-500/30 hover:shadow-lg">
                    Masuk
                </button>
            </form>

        </div>

        {{-- Footer Keamanan --}}
        <div class="text-center mt-3">
            <div class="flex justify-center mb-1">
                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
            </div>
            <p class="text-xs font-medium text-gray-500">Data Anda aman dan terlindungi</p>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-2.982m3.043-2.5A9.98 9.98 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.99 9.99 0 01-1.63 2.972M3 3l18 18"/>';
        } else {
            input.type = 'password';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>';
        }
    }
</script>
@endsection