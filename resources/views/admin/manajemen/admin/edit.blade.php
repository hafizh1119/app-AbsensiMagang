{{-- resources/views/admin/manajemen/admin/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Admin')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Edit Admin'])

        <main class="p-6">

            {{-- Header Halaman + Breadcrumb --}}
            <div class="flex items-center justify-between mb-5">
                <div>
                    <div class="flex items-center gap-1.5 text-xs text-gray-400 mb-1.5">
                        <a href="{{ route('admin.manajemen.admin') }}" class="hover:text-emerald-600">Manajemen Admin</a>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="text-gray-500 font-medium">Edit Admin</span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">Edit Admin</h1>
                    <p class="text-sm text-gray-400 mt-0.5">Perbarui data akun Super Admin: {{ $admin->nama }}</p>
                </div>

                <a href="{{ route('admin.manajemen.admin') }}"
                   class="flex items-center gap-2 border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-semibold px-4 py-2.5 rounded-lg transition duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>

            {{-- Form --}}
            <form action="{{ route('admin.manajemen.admin.update', $admin->id) }}" method="POST" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Nama Admin --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Admin <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $admin->nama) }}" placeholder="Masukkan nama lengkap admin"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('nama') border-rose-400 @enderror">
                        @error('nama')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIP --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">NIP <span class="text-rose-500">*</span></label>
                        <input type="text" name="nip" value="{{ old('nip', $admin->nip) }}" placeholder="Masukkan NIP"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('nip') border-rose-400 @enderror">
                        @error('nip')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jabatan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jabatan <span class="text-rose-500">*</span></label>
                        <input type="text" name="jabatan" value="{{ old('jabatan', $admin->jabatan) }}" placeholder="Masukkan jabatan"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('jabatan') border-rose-400 @enderror">
                        @error('jabatan')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $admin->email) }}" placeholder="nama@kopjateng.go.id"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('email') border-rose-400 @enderror">
                        @error('email')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('password') border-rose-400 @enderror">
                        <p class="text-xs text-gray-400 mt-1">Biarkan kosong jika tidak ingin mengganti password.</p>
                        @error('password')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                </div>

                {{-- Tombol Aksi --}}
                <div class="flex items-center justify-end gap-3 mt-7 pt-5 border-t border-gray-100">
                    <a href="{{ route('admin.manajemen.admin') }}"
                       class="flex items-center gap-2 border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-semibold px-5 py-2.5 rounded-lg transition duration-150">
                        Batal
                    </a>
                    <button type="submit"
                            class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition duration-150 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </main>
    @include('admin.components.footer')
    </div>

</div>

@endsection