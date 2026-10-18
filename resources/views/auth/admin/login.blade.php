@extends('layouts.app')

@section('title', 'Login Admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-sky-100 relative overflow-hidden flex items-center justify-center p-4">

    {{-- Dekorasi latar belakang --}}
    <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-sky-200/40 blur-3xl"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-sky-300/30 blur-3xl"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full bg-sky-100/20 blur-3xl"></div>

    {{-- Container Login --}}
    <div class="relative w-full max-w-5xl bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-sky-900/10 border border-white/50 overflow-hidden flex">

        {{-- Side Kiri: Branding --}}
        <div class="hidden lg:flex lg:w-2/5 bg-gradient-to-br from-sky-200 to-sky-300 p-10 flex-col justify-between relative overflow-hidden">
            {{-- Dekorasi --}}
            <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full bg-sky-400/20 blur-2xl"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 rounded-full bg-sky-400/10 blur-2xl"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-white/30 backdrop-blur flex items-center justify-center">
                        <svg class="w-7 h-7 text-sky-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m4 0h10M9 7h1m4 0h1M9 11h1m4 0h1m-6 4h1m4 0h1"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sky-800 font-bold text-sm">Presensi Peserta Magang</p>
                        <p class="text-sky-700/80 text-[10px] font-medium">Dinas Koperasi Prov Jawa Tengah</p>
                    </div>
                </div>
            </div>

            {{-- Logo --}}
            <div class="relative z-10 flex justify-center">
                <img
                    src="{{ asset('icon/diskop.svg') }}"
                    alt="Logo Dinas Koperasi Provinsi Jawa Tengah"
                    class="w-64 h-auto object-contain">
            </div>

            <div class="relative z-10">
                <p class="text-sky-700/60 text-[10px] text-center">&copy; Dibuat oleh Muhammad Hanif &middot; Udinus &middot; 2026</p>
            </div>
        </div>

        {{-- Side Kanan: Form Login --}}
        <div class="w-full lg:w-3/5 p-8 lg:p-12">
            <div class="max-w-sm mx-auto lg:mx-0">

                {{-- Logo Mobile --}}
                <div class="lg:hidden flex justify-center mb-6">
                    <img
                        src="{{ asset('icon/diskop.svg') }}"
                        alt="Logo Dinas Koperasi Provinsi Jawa Tengah"
                        class="w-56 h-auto object-contain">
                </div>

                <div class="text-center lg:text-left mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">Login Admin</h2>
                    <p class="text-sm text-gray-400 mt-1">Masukkan kredensial Anda untuk mengakses dashboard</p>
                </div>

                <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                    @csrf

                    @if (session('error'))
                        <div class="flex items-center gap-3 bg-rose-50 border border-rose-100 text-rose-600 text-sm rounded-xl px-4 py-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="Masukkan email admin"
                                   class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition @error('email') border-rose-400 @enderror">
                        </div>
                        @error('email')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   placeholder="Masukkan password Anda"
                                   class="w-full pl-10 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition @error('password') border-rose-400 @enderror">
                            <button type="button" onclick="togglePassword()"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                                <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Masuk --}}
                    <button type="submit"
                            class="w-full bg-sky-600 hover:bg-sky-700 text-white font-semibold py-3.5 px-4 rounded-xl transition duration-200 shadow-lg shadow-sky-600/20 hover:shadow-xl hover:scale-[1.02] active:scale-[0.98]">
                        Masuk ke Dashboard
                    </button>
                </form>

                {{-- Divider --}}
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="px-3 bg-white text-gray-400">Akses Terbatas Admin</span>
                    </div>
                </div>

            </div>
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