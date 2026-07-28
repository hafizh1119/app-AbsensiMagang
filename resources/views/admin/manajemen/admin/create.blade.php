{{-- resources/views/admin/admin/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Admin')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Tambah Admin'])

        <main class="p-6">

            @php
                // Daftar unit/divisi contoh, ganti dengan data asli dari controller
                $unitList = $unitList ?? [
                    'Sekretariat',
                    'Bidang Koperasi',
                    'Bidang UMKM',
                    'Bidang Pembiayaan',
                    'Bidang Pengawasan',
                ];
            @endphp

            {{-- Header Halaman + Breadcrumb --}}
            <div class="flex items-center justify-between mb-5">
                <div>
                    <div class="flex items-center gap-1.5 text-xs text-gray-400 mb-1.5">
                        <a href="#" class="hover:text-emerald-600">Manajemen Admin</a>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="text-gray-500 font-medium">Tambah Admin</span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">Tambah Admin</h1>
                    <p class="text-sm text-gray-400 mt-0.5">Buat akun administrator baru untuk sistem absensi</p>
                </div>

                <a href="#"
                   class="flex items-center gap-2 border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-semibold px-4 py-2.5 rounded-lg transition duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>

            {{-- Form --}}
            <form action="#" method="POST" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Nama Admin --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Admin <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap admin"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('nama') border-rose-400 @enderror">
                        @error('nama')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@kopjateng.go.id"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('email') border-rose-400 @enderror">
                        @error('email')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Role <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select name="role" id="role" onchange="toggleUnitField()"
                                    class="appearance-none w-full border border-gray-200 rounded-lg pl-3 pr-8 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('role') border-rose-400 @enderror">
                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Role</option>
                                <option value="Super Admin" {{ old('role') == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                                <option value="Admin Unit" {{ old('role') == 'Admin Unit' ? 'selected' : '' }}>Admin Unit</option>
                            </select>
                            <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                        <p class="text-xs text-gray-400 mt-1.5">
                            <span class="font-medium text-violet-600">Super Admin</span> punya akses penuh ke seluruh unit.
                            <span class="font-medium text-sky-600">Admin Unit</span> hanya mengelola satu unit/divisi tertentu.
                        </p>
                        @error('role')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Unit / Divisi — hanya muncul jika role = Admin Unit --}}
                    <div id="unit-field" class="md:col-span-2 {{ old('role') == 'Admin Unit' ? '' : 'hidden' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Unit / Divisi <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select name="unit" id="unit"
                                    class="appearance-none w-full md:w-1/2 border border-gray-200 rounded-lg pl-3 pr-8 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('unit') border-rose-400 @enderror">
                                <option value="" disabled {{ old('unit') ? '' : 'selected' }}>Pilih Unit / Divisi</option>
                                @foreach($unitList as $unit)
                                    <option value="{{ $unit }}" {{ old('unit') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                                @endforeach
                            </select>
                            <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none md:right-[calc(50%+0.75rem)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                        @error('unit')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('password') border-rose-400 @enderror">
                        @error('password')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    {{-- Status --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status Akun</label>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="status" value="1" class="sr-only peer" checked>
                            <div class="w-10 h-5 bg-gray-200 rounded-full peer
                                        peer-checked:bg-emerald-600
                                        after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                        after:bg-white after:rounded-full after:h-4 after:w-4
                                        after:transition-all after:shadow-sm
                                        peer-checked:after:translate-x-5"></div>
                            <span class="ml-2.5 text-sm text-gray-600">Aktifkan akun setelah dibuat</span>
                        </label>
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
                        Simpan Admin
                    </button>
                </div>

            </form>

        </main>

    </div>

</div>

@push('scripts')
<script>
    function toggleUnitField() {
        const role = document.getElementById('role').value;
        const unitField = document.getElementById('unit-field');
        const unitSelect = document.getElementById('unit');

        if (role === 'Admin Unit') {
            unitField.classList.remove('hidden');
            unitSelect.setAttribute('required', 'required');
        } else {
            unitField.classList.add('hidden');
            unitSelect.removeAttribute('required');
            unitSelect.value = '';
        }
    }

    // Jalankan sekali saat halaman dimuat (mis. setelah validasi gagal & old() terisi)
    document.addEventListener('DOMContentLoaded', toggleUnitField);
</script>
@endpush

@endsection