{{-- resources/views/admin/manajemen/user/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Peserta')

@section('content')

<div class="flex h-screen overflow-hidden"
     x-data="{
        editOpen: {{ $errors->any() && old('_form') === 'edit' ? 'true' : 'false' }},
        form: {
            id: {{ old('_form') === 'edit' ? (int) old('id') : 'null' }},
            nama: @js(old('info_nama', '')),
            nip: @js(old('info_nip', '')),
            email: @js(old('info_email', '')),
            unit: @js(old('info_unit', '')),
            role: @js(old('role', 'pegawai'))
        },
        openEdit(row) {
            this.form = {
                id: row.id,
                nama: row.nama,
                nip: row.nip,
                email: row.email,
                unit: row.unit,
                role: row.role
            };
            this.editOpen = true;
        }
     }">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Peserta'])

        <main class="p-6">

            @if (session('success'))
                <div class="mb-4 flex items-center gap-3 bg-sky-50 border border-sky-100 text-sky-600 text-sm rounded-xl px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header Halaman --}}
            <div class="mb-5">
                <h1 class="text-2xl font-bold text-gray-800">Peserta</h1>
                <p class="text-sm text-gray-400 mt-0.5">Kelola akun peserta yang terdaftar di sistem absensi</p>
            </div>

            {{-- Filter Bar --}}
            <form method="GET" action="{{ route('admin.manajemen.user') }}"
                  class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4">
                <div class="flex flex-wrap items-end gap-3">

                    {{-- Cari --}}
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5">Cari</label>
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}"
                                   placeholder="Cari nama, no. peserta, atau email..."
                                   class="w-72 border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500">
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
                                    <option value="{{ $unit->id }}" @selected(request('unit_id') == $unit->id)>
                                        {{ $unit->nama_unit }}
                                    </option>
                                @endforeach
                            </select>
                            <svg class="w-4 h-4 text-gray-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Terapkan --}}
                    <button type="submit"
                            class="bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold px-4 py-2 rounded-lg transition duration-150 shadow-sm">
                        Terapkan
                    </button>

                    {{-- Reset --}}
                    <a href="{{ route('admin.manajemen.user') }}"
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
                                <th class="text-left font-medium px-3 py-2">Nama Lengkap</th>
                                <th class="text-left font-medium px-3 py-2">No. Peserta</th>
                                <th class="text-left font-medium px-3 py-2">Email</th>
                                <th class="text-left font-medium px-3 py-2">Unit</th>
                                <th class="text-center font-medium px-3 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($users as $user)
                                @php
                                    // Data untuk mengisi modal edit
                                    $editData = [
                                        'id'    => $user->id,
                                        'nama'  => $user->nama,
                                        'nip'   => $user->nip ?? '-',
                                        'email' => $user->email,
                                        'unit'  => $user->unit->nama_unit ?? '-',
                                        'role'  => $user->role,
                                    ];
                                @endphp
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-3 py-2 text-gray-500">
                                        {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                                    </td>
                                    <td class="px-3 py-2 font-medium text-gray-700">{{ $user->nama }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $user->nip ?? '-' }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $user->email }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $user->unit->nama_unit ?? '-' }}</td>
                                    <td class="px-3 py-2 text-center">
                                        <button type="button" @click="openEdit(@js($editData))"
                                                class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-sky-500 hover:bg-sky-600 text-white transition">
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada data peserta.</td>
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

            {{-- Modal Edit Role --}}
            <div x-show="editOpen" x-transition.opacity style="display: none;"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
                <div x-show="editOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     @click.outside="editOpen = false"
                     class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 text-left max-h-[90vh] overflow-y-auto">

                    <h3 class="text-base font-bold text-gray-900 mb-4">Edit Role</h3>

                    <form :action="'{{ route('admin.manajemen.user.update', ['id' => '__ID__']) }}'.replace('__ID__', form.id)"
                          method="POST" class="space-y-4 text-sm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="_form" value="edit">
                        <input type="hidden" name="id" :value="form.id">
                        <input type="hidden" name="info_nama" :value="form.nama">
                        <input type="hidden" name="info_nip" :value="form.nip">
                        <input type="hidden" name="info_email" :value="form.email">
                        <input type="hidden" name="info_unit" :value="form.unit">

                        {{-- Info pengguna (hanya tampil) --}}
                        <div class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-3 space-y-2">
                            <div class="flex items-start justify-between gap-3">
                                <span class="text-xs text-gray-400">Nama</span>
                                <span class="text-sm font-medium text-gray-700 text-right" x-text="form.nama"></span>
                            </div>
                            <div class="flex items-start justify-between gap-3">
                                <span class="text-xs text-gray-400">No. Peserta</span>
                                <span class="text-sm text-gray-600 text-right" x-text="form.nip"></span>
                            </div>
                            <div class="flex items-start justify-between gap-3">
                                <span class="text-xs text-gray-400">Email</span>
                                <span class="text-sm text-gray-600 text-right break-all" x-text="form.email"></span>
                            </div>
                            <div class="flex items-start justify-between gap-3">
                                <span class="text-xs text-gray-400">Unit</span>
                                <span class="text-sm text-gray-600 text-right" x-text="form.unit"></span>
                            </div>
                        </div>

                        {{-- Role (satu-satunya field yang bisa diubah) --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Role</label>
                            <div class="relative">
                                <select name="role" x-model="form.role"
                                        class="appearance-none w-full bg-white border border-gray-200 rounded-lg pl-3 pr-8 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-500 @if(old('_form') === 'edit') @error('role') border-rose-400 @enderror @endif">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}">{{ $roleLabels[$role] ?? ucfirst(str_replace('_', ' ', $role)) }}</option>
                                    @endforeach
                                </select>
                                <svg class="w-4 h-4 text-gray-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            @if (old('_form') === 'edit')
                                @error('role') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            @endif
                            <p class="text-xs text-gray-400 mt-1.5">
                                Jika diubah menjadi Admin Ruangan, akun ini akan memverifikasi izin peserta
                                dan tidak lagi muncul di daftar Peserta.
                            </p>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center gap-3 pt-2">
                            <button type="button" @click="editOpen = false"
                                    class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                Batal
                            </button>
                            <button type="submit"
                                    class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg bg-sky-500 hover:bg-sky-600 text-white transition">
                                Simpan Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    @include('admin.components.footer')
    </div>

</div>

@endsection