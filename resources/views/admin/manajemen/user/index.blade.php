{{-- resources/views/admin/manajemen/user/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Manajemen User'])

        <main class="p-6">

            @if (session('success'))
                <div class="mb-4 flex items-center gap-3 bg-sky-50 border border-sky-100 text-sky-600 text-sm rounded-xl px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header Halaman --}}
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Manajemen User</h1>
                    <p class="text-sm text-gray-400 mt-0.5">Kelola role seluruh pengguna sistem absensi</p>
                </div>
            </div>

            {{-- Cards Ringkasan --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">

                {{-- Total User --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400 mb-2">Total User</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalUser }}</p>
                        <p class="text-xs text-gray-400 mt-1">Orang</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-sky-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-8a4 4 0 11-8 0 4 4 0 018 0zm6 3a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Total Unit --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400 mb-2">Total Unit</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalUnit }}</p>
                        <p class="text-xs text-gray-400 mt-1">Unit</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-violet-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 9h1m4 0h1m-6 4h1m4 0h1m-6 4h1m4 0h1"/>
                        </svg>
                    </div>
                </div>

            </div>

            {{-- Filter Bar --}}
            <form method="GET" action="{{ route('admin.manajemen.user') }}"
                  class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4">
                <div class="flex flex-wrap items-end justify-between gap-3">

                    <div class="flex flex-wrap items-end gap-3">

                        {{-- Cari --}}
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Cari</label>
                            <div class="relative">
                                <input type="text" name="q" value="{{ request('q') }}"
                                       placeholder="Cari nama, NIP, atau email..."
                                       class="w-64 border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500">
                                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="7"/>
                                    <path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Unit / Divisi --}}
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Pilih Unit / Divisi</label>
                            <div class="relative">
                                <select name="unit_id"
                                        class="appearance-none w-48 border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
                                    <option value="">Semua Unit</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->nama_unit }}
                                        </option>
                                    @endforeach
                                </select>
                                <svg class="w-4 h-4 text-gray-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Filter --}}
                        <button type="submit" class="flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition duration-150 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18l-7 9v6l-4 2v-8L3 4z"/>
                            </svg>
                            Filter
                        </button>

                        {{-- Reset --}}
                        <a href="{{ route('admin.manajemen.user') }}"
                           class="flex items-center gap-2 border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-semibold px-4 py-2 rounded-lg transition duration-150">
                            Reset
                        </a>
                    </div>

                </div>
            </form>

            {{-- Tabel --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 text-xs">
                                <th class="text-left font-medium px-3 py-2 w-8">No</th>
                                <th class="text-left font-medium px-3 py-2">Nama Lengkap</th>
                                <th class="text-left font-medium px-3 py-2">NIP</th>
                                <th class="text-left font-medium px-3 py-2">Email</th>
                                <th class="text-left font-medium px-3 py-2">Unit</th>
                                <th class="text-left font-medium px-3 py-2">Role</th>
                                <th class="text-center font-medium px-3 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-3 py-2 text-gray-500">
                                        {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                                    </td>
                                    <td class="px-3 py-2 font-medium text-gray-700">{{ $user->nama }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $user->nip }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $user->email }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $user->unit->nama_unit ?? '-' }}</td>
                                    <td class="px-3 py-2">
                                        @php
                                            $roleColors = [
                                                'admin_unit' => 'bg-violet-50 text-violet-600',
                                                'pegawai'    => 'bg-sky-50 text-sky-600',
                                            ];
                                            $roleColor = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-600';
                                        @endphp
                                        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full {{ $roleColor }}">
                                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('admin.manajemen.user.edit', $user->id) }}"
                                               title="Edit Role"
                                               class="w-7 h-7 flex items-center justify-center rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-600 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-400">Belum ada data user.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-between px-5 py-4 border-t border-gray-100">
                    <p class="text-sm text-gray-400">
                        Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data
                    </p>
                    <div>
                        {{ $users->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>

        </main>
    @include('admin.components.footer')
    </div>

</div>

@endsection