{{-- resources/views/admin/manajemen/admin/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Manajemen Admin')

@section('content')

<div class="flex h-screen overflow-hidden"
     x-data="{
        createOpen: {{ $errors->any() && old('_form') === 'create' ? 'true' : 'false' }},
        editOpen: {{ $errors->any() && old('_form') === 'edit' ? 'true' : 'false' }},
        deleteOpen: false,
        deleteTarget: { id: null, nama: '' },
        form: {
            id: {{ old('_form') === 'edit' ? (int) old('id') : 'null' }},
            nama: @js(old('_form') === 'edit' ? old('nama', '') : ''),
            email: @js(old('_form') === 'edit' ? old('email', '') : '')
        },
        openEdit(row) {
            this.form = { id: row.id, nama: row.nama, email: row.email };
            this.editOpen = true;
        },
        openDelete(row) {
            this.deleteTarget = { id: row.id, nama: row.nama };
            this.deleteOpen = true;
        }
     }">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Manajemen Admin'])

        <main class="p-6">

            @if (session('success'))
                <div class="mb-4 flex items-center gap-3 bg-sky-50 border border-sky-100 text-sky-600 text-sm rounded-xl px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 flex items-center gap-3 bg-rose-50 border border-rose-100 text-rose-600 text-sm rounded-xl px-4 py-3">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Header Halaman --}}
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Manajemen Admin</h1>
                    <p class="text-sm text-gray-400 mt-0.5">Kelola akun Super Admin sistem absensi</p>
                </div>
                <button type="button" @click="createOpen = true"
                        class="inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition duration-150 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Admin
                </button>
            </div>

            {{-- Filter Bar --}}
            <form method="GET" action="{{ route('admin.manajemen.admin') }}"
                  class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4">
                <div class="flex flex-wrap items-end gap-3">

                    {{-- Cari --}}
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5">Cari</label>
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}"
                                   placeholder="Cari nama atau email..."
                                   class="w-72 border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="7"/>
                                <path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Terapkan --}}
                    <button type="submit"
                            class="bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold px-4 py-2 rounded-lg transition duration-150 shadow-sm">
                        Terapkan
                    </button>

                    {{-- Reset --}}
                    <a href="{{ route('admin.manajemen.admin') }}"
                       class="text-sm font-semibold text-gray-500 hover:text-gray-700 px-2 py-2">
                        Reset
                    </a>
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
                                <th class="text-center font-medium px-3 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($admins as $admin)
                                @php
                                    $editData = [
                                        'id'    => $admin->id,
                                        'nama'  => $admin->nama,
                                        'email' => $admin->email,
                                    ];
                                @endphp
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-3 py-2 text-gray-500">
                                        {{ $loop->iteration + ($admins->currentPage() - 1) * $admins->perPage() }}
                                    </td>
                                    <td class="px-3 py-2 font-medium text-gray-700">{{ $admin->nama }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $admin->email }}</td>
                                    <td class="px-3 py-2 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button type="button" @click="openEdit(@js($editData))"
                                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-sky-500 hover:bg-sky-600 text-white transition">
                                                Edit
                                            </button>
                                            <button type="button" @click="openDelete(@js($editData))"
                                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-rose-500 hover:bg-rose-600 text-white transition">
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada data admin.</td>
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

            {{-- ============ Modal Tambah Admin ============ --}}
            <div x-show="createOpen" x-transition.opacity style="display: none;"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
                <div x-show="createOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     @click.outside="createOpen = false"
                     class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 text-left max-h-[90vh] overflow-y-auto">

                    <h3 class="text-base font-bold text-gray-900 mb-4">Tambah Admin</h3>

                    <form action="{{ route('admin.manajemen.admin.store') }}" method="POST" class="space-y-4 text-sm">
                        @csrf
                        <input type="hidden" name="_form" value="create">

                        {{-- Nama --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Nama Admin <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama" value="{{ old('_form') === 'create' ? old('nama') : '' }}"
                                   placeholder="Masukkan nama lengkap admin"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500 @if(old('_form') === 'create') @error('nama') border-rose-400 @enderror @endif">
                            @if (old('_form') === 'create')
                                @error('nama') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Email <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" value="{{ old('_form') === 'create' ? old('email') : '' }}"
                                   placeholder="nama@kopjateng.go.id"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500 @if(old('_form') === 'create') @error('email') border-rose-400 @enderror @endif">
                            @if (old('_form') === 'create')
                                @error('email') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        {{-- Password --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Password <span class="text-rose-500">*</span></label>
                            <input type="password" name="password" placeholder="Minimal 8 karakter"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500 @if(old('_form') === 'create') @error('password') border-rose-400 @enderror @endif">
                            @if (old('_form') === 'create')
                                @error('password') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Konfirmasi Password <span class="text-rose-500">*</span></label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500">
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center gap-3 pt-2">
                            <button type="button" @click="createOpen = false"
                                    class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                Batal
                            </button>
                            <button type="submit"
                                    class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg bg-sky-500 hover:bg-sky-600 text-white transition">
                                Simpan Admin
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ============ Modal Edit Admin ============ --}}
            <div x-show="editOpen" x-transition.opacity style="display: none;"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
                <div x-show="editOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     @click.outside="editOpen = false"
                     class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 text-left max-h-[90vh] overflow-y-auto">

                    <h3 class="text-base font-bold text-gray-900 mb-4">Edit Admin</h3>

                    <form :action="'{{ route('admin.manajemen.admin.update', '__ID__') }}'.replace('__ID__', form.id)"
                          method="POST" class="space-y-4 text-sm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="_form" value="edit">
                        <input type="hidden" name="id" :value="form.id">

                        {{-- Nama --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Nama Admin <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama" x-model="form.nama"
                                   placeholder="Masukkan nama lengkap admin"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500 @if(old('_form') === 'edit') @error('nama') border-rose-400 @enderror @endif">
                            @if (old('_form') === 'edit')
                                @error('nama') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Email <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" x-model="form.email"
                                   placeholder="nama@kopjateng.go.id"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500 @if(old('_form') === 'edit') @error('email') border-rose-400 @enderror @endif">
                            @if (old('_form') === 'edit')
                                @error('email') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        {{-- Password Baru --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Password Baru</label>
                            <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500 @if(old('_form') === 'edit') @error('password') border-rose-400 @enderror @endif">
                            <p class="text-xs text-gray-400 mt-1">Biarkan kosong jika tidak ingin mengganti password.</p>
                            @if (old('_form') === 'edit')
                                @error('password') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        {{-- Konfirmasi Password Baru --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500">
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center gap-3 pt-2">
                            <button type="button" @click="editOpen = false"
                                    class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                Batal
                            </button>
                            <button type="submit"
                                    class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg bg-sky-500 hover:bg-sky-600 text-white transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ============ Modal Konfirmasi Hapus ============ --}}
            <div x-show="deleteOpen" x-transition.opacity style="display: none;"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
                <div x-show="deleteOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     @click.outside="deleteOpen = false"
                     class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-center">

                    <div class="w-14 h-14 mx-auto rounded-full bg-rose-50 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/>
                        </svg>
                    </div>

                    <h3 class="text-base font-bold text-gray-900 mb-1">Hapus Akun Admin?</h3>
                    <p class="text-sm text-gray-500 mb-6">
                        Akun <span class="font-semibold" x-text="deleteTarget.nama"></span> akan dihapus permanen dan tidak dapat dikembalikan.
                    </p>

                    <div class="flex items-center gap-3">
                        <button type="button" @click="deleteOpen = false"
                                class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <form :action="'{{ route('admin.manajemen.admin.destroy', '__ID__') }}'.replace('__ID__', deleteTarget.id)"
                              method="POST" class="flex-1">
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

        </main>
    @include('admin.components.footer')
    </div>

</div>

@endsection