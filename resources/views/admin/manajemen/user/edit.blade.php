{{-- resources/views/admin/manajemen/user/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Role User')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Edit Role User'])

        <main class="p-6">

            {{-- Judul & Breadcrumb --}}
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit Role User</h2>
                    <p class="text-sm text-gray-400 mt-1">Data pengguna hanya dapat dilihat — role dapat diubah di sini</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">

                <form action="{{ route('admin.manajemen.user.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- Nama Lengkap (read-only) --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Nama Lengkap</label>
                            <input type="text" value="{{ $user->nama }}" disabled
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-500 cursor-not-allowed">
                        </div>

                        {{-- NIP (read-only) --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">NIP</label>
                            <input type="text" value="{{ $user->nip }}" disabled
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-500 cursor-not-allowed">
                        </div>

                        {{-- Email (read-only) --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Email</label>
                            <input type="text" value="{{ $user->email }}" disabled
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-500 cursor-not-allowed">
                        </div>

                        {{-- Unit (read-only) --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Unit</label>
                            <input type="text" value="{{ $user->unit->nama_unit ?? '-' }}" disabled
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-500 cursor-not-allowed">
                        </div>

                        {{-- Role (satu-satunya field yang bisa diubah) --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Role</label>
                            <div class="relative max-w-sm">
                                <select name="role"
                                        class="appearance-none w-full bg-white border border-gray-200 rounded-lg pl-3 pr-8 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('role') border-rose-400 @enderror">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}" {{ old('role', $user->role) === $role ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $role)) }}
                                        </option>
                                    @endforeach
                                </select>
                                <svg class="w-4 h-4 text-gray-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            @error('role') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-end gap-3 mt-6">
                        <a href="{{ route('admin.manajemen.user') }}"
                           class="text-sm font-semibold px-5 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit"
                                class="text-sm font-semibold px-5 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition">
                            Simpan Role
                        </button>
                    </div>

                </form>

            </div>

        </main>

    </div>

</div>

@endsection