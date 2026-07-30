{{-- resources/views/admin/manajemen/admin/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Manajemen Admin')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Manajemen Admin'])

        <main class="p-6">

            @if (session('success'))
                <div class="mb-4 flex items-center gap-3 bg-emerald-50 border border-emerald-100 text-emerald-600 text-sm rounded-xl px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 flex items-center gap-3 bg-rose-50 border border-rose-100 text-rose-600 text-sm rounded-xl px-4 py-3">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Halaman ini KHUSUS mengelola akun dengan role 'admin' di database,
                 yang ditampilkan ke pengguna sebagai "Super Admin".
                 Akun dengan role 'admin_unit' TIDAK ditampilkan/dikelola di sini. --}}

            {{-- Header Halaman --}}
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Manajemen Admin</h1>
                    <p class="text-sm text-gray-400 mt-0.5">Kelola akun Super Admin sistem absensi</p>
                </div>
                <a href="{{ route('admin.manajemen.admin.create') }}"
                class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition duration-150 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Admin
                </a>
            </div>

            {{-- Cards Ringkasan --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">

                {{-- Total Admin --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400 mb-2">Total Admin</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalAdmin }}</p>
                        <p class="text-xs text-gray-400 mt-1">Akun</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2l7 3v6c0 5-3.5 8.5-7 10-3.5-1.5-7-5-7-10V5l7-3z"/>
                        </svg>
                    </div>
                </div>

                {{-- Admin Aktif --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400 mb-2">Admin Aktif</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalAktif }}</p>
                        <p class="text-xs text-gray-400 mt-1">Akun</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-violet-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 18l-1.5-9L8 12l4-7 4 7 4.5-3L19 18H5z"/>
                        </svg>
                    </div>
                </div>

                {{-- Admin Nonaktif --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400 mb-2">Admin Nonaktif</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalNonaktif }}</p>
                        <p class="text-xs text-gray-400 mt-1">Akun</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-rose-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 105.636 5.636a9 9 0 0012.728 12.728zM9.879 9.879l4.242 4.242M14.121 9.879l-4.242 4.242"/>
                        </svg>
                    </div>
                </div>

            </div>

            {{-- Filter Bar --}}
            <form method="GET" action="{{ route('admin.manajemen.admin') }}"
                  class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4">
                <div class="flex flex-wrap items-end justify-between gap-3">

                    <div class="flex flex-wrap items-end gap-3">

                        {{-- Cari --}}
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Cari</label>
                            <div class="relative">
                                <input type="text" name="q" value="{{ request('q') }}"
                                       placeholder="Cari nama atau email..."
                                       class="w-64 border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="7"/>
                                    <path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Status</label>
                            <div class="relative">
                                <select name="status" class="appearance-none w-36 border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <option value="">Semua Status</option>
                                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                <svg class="w-4 h-4 text-gray-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Filter --}}
                        <button type="submit" class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition duration-150 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18l-7 9v6l-4 2v-8L3 4z"/>
                            </svg>
                            Filter
                        </button>

                        {{-- Reset --}}
                        <a href="{{ route('admin.manajemen.admin') }}"
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
                                <th class="text-left font-medium px-3 py-2">Nama Admin</th>
                                <th class="text-left font-medium px-3 py-2">Email</th>
                                <th class="text-left font-medium px-3 py-2">Role</th>
                                <th class="text-left font-medium px-3 py-2">Status</th>
                                <th class="text-center font-medium px-3 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($admins as $admin)
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-3 py-2 text-gray-500">
                                        {{ $loop->iteration + ($admins->currentPage() - 1) * $admins->perPage() }}
                                    </td>
                                    <td class="px-3 py-2 font-medium text-gray-700">{{ $admin->nama }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $admin->email }}</td>
                                    <td class="px-3 py-2">
                                        {{-- Role di database adalah 'admin', ditampilkan sebagai "Super Admin" --}}
                                        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full bg-violet-50 text-violet-600">
                                            Super Admin
                                        </span>
                                    </td>
                                    <td class="px-3 py-2">
                                        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full
                                            {{ $admin->status === 'aktif' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-500' }}">
                                            {{ ucfirst($admin->status ?? '-') }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('admin.manajemen.admin.edit', $admin->id) }}"
                                            title="Edit"
                                            class="w-7 h-7 flex items-center justify-center rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                            </a>

                                            <div x-data="{ confirmOpen: false }" class="inline">
                                                <button type="button" @click="confirmOpen = true"
                                                        title="Hapus"
                                                        class="w-7 h-7 flex items-center justify-center rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-500 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"/>
                                                    </svg>
                                                </button>

                                                <div x-show="confirmOpen"
                                                    x-transition.opacity
                                                    style="display: none;"
                                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">

                                                    <div x-show="confirmOpen"
                                                        x-transition:enter="transition ease-out duration-200"
                                                        x-transition:enter-start="opacity-0 scale-95"
                                                        x-transition:enter-end="opacity-100 scale-100"
                                                        @click.outside="confirmOpen = false"
                                                        class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-center">

                                                        <div class="w-14 h-14 mx-auto rounded-full bg-rose-50 flex items-center justify-center mb-4">
                                                            <svg class="w-7 h-7 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/>
                                                            </svg>
                                                        </div>

                                                        <h3 class="text-base font-bold text-gray-900 mb-1">Hapus Akun Admin?</h3>
                                                        <p class="text-sm text-gray-500 mb-6">
                                                            Akun <span class="font-semibold">{{ $admin->nama }}</span> akan dihapus permanen dan tidak dapat dikembalikan.
                                                        </p>

                                                        <div class="flex items-center gap-3">
                                                            <button type="button" @click="confirmOpen = false"
                                                                    class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                                                Batal
                                                            </button>
                                                            <form action="{{ route('admin.manajemen.admin.destroy', $admin->id) }}" method="POST" class="flex-1">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                        class="w-full text-sm font-semibold px-4 py-2.5 rounded-lg bg-rose-500 hover:bg-rose-600 text-white transition">
                                                                    Ya, Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada data admin.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-between px-5 py-4 border-t border-gray-100">
                    <p class="text-sm text-gray-400">
                        Menampilkan {{ $admins->firstItem() ?? 0 }} - {{ $admins->lastItem() ?? 0 }} dari {{ $admins->total() }} data
                    </p>
                    <div>
                        {{ $admins->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>

        </main>
    @include('admin.components.footer')
    </div>

</div>

@endsection