{{-- resources/views/admin/data-master/jam-kerja/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Jam Kerja')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Tambah Jam Kerja'])

        <main class="p-6">

            @if ($errors->any())
                <div class="mb-4 bg-rose-50 border border-rose-100 text-rose-600 text-sm rounded-xl px-4 py-3">
                    <p class="font-semibold mb-1">Periksa kembali isian berikut:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Judul Halaman --}}
            <div class="flex flex-wrap items-start justify-between gap-4 mb-5">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Tambah Jam Kerja</h1>
                    <p class="text-sm text-gray-400 mt-0.5">Atur jadwal jam kerja untuk salah satu hari.</p>
                </div>
                <p class="text-xs text-gray-400">
                    Dashboard / <a href="{{ route('admin.data-master.jam-kerja') }}" class="hover:text-emerald-600">Jam Kerja</a> /
                    <span class="text-gray-600 font-medium">Tambah</span>
                </p>
            </div>

            {{-- Form Card --}}
            <div class="max-w-2xl bg-white rounded-2xl border border-gray-100 shadow-sm p-6"
                 x-data="{ status: '{{ old('status', 'aktif') }}' }">

                <form action="{{ route('admin.data-master.jam-kerja.store') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Hari --}}
                    <div class="flex items-center gap-6">
                        <label class="w-40 shrink-0 text-sm font-medium text-gray-700">Hari</label>
                        <div class="relative flex-1">
                            <select name="hari"
                                    class="w-full appearance-none border border-gray-200 rounded-lg pl-3 pr-9 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                @forelse ($hariTersedia as $hari)
                                    <option value="{{ $hari }}" @selected(old('hari') === $hari)>{{ $hari }}</option>
                                @empty
                                    <option value="" disabled selected>Semua hari sudah terdaftar</option>
                                @endforelse
                            </select>
                            <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="flex items-center gap-6">
                        <label class="w-40 shrink-0 text-sm font-medium text-gray-700">Status</label>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                <input type="radio" name="status" value="aktif" x-model="status"
                                       class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                                Aktif
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                <input type="radio" name="status" value="libur" x-model="status"
                                       class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                                Libur
                            </label>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    {{-- Jam Masuk, Jam Pulang, Toleransi --}}
                    <div x-show="status === 'aktif'" x-cloak class="space-y-5">

                        {{-- Jam Masuk --}}
                        <div class="flex items-center gap-6">
                            <label class="w-40 shrink-0 text-sm font-medium text-gray-700">Jam Masuk</label>
                            <input type="time" name="jam_masuk" value="{{ old('jam_masuk', '08:00') }}"
                                   class="flex-1 border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>

                        {{-- Jam Pulang --}}
                        <div class="flex items-center gap-6">
                            <label class="w-40 shrink-0 text-sm font-medium text-gray-700">Jam Pulang</label>
                            <input type="time" name="jam_pulang" value="{{ old('jam_pulang', '16:00') }}"
                                   class="flex-1 border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>

                        {{-- Toleransi Terlambat --}}
                        <div class="flex items-center gap-6">
                            <label class="w-40 shrink-0 text-sm font-medium text-gray-700">Toleransi Terlambat<br>(Menit)</label>
                            <input type="number" name="toleransi_terlambat" min="0" value="{{ old('toleransi_terlambat', 15) }}"
                                   class="flex-1 border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>

                        {{-- Info Box --}}
                        <div class="flex items-start gap-2.5 bg-blue-50 text-blue-700 text-sm rounded-xl px-4 py-3">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4m0-4h.01"/>
                            </svg>
                            <p>Toleransi keterlambatan digunakan untuk menentukan batas maksimal keterlambatan saat absensi masuk.</p>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('admin.data-master.jam-kerja') }}"
                           class="text-sm font-semibold px-5 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit"
                                class="text-sm font-semibold px-5 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition">
                            Simpan
                        </button>
                    </div>

                </form>
            </div>

        </main>
    @include('admin.components.footer')
    </div>

</div>

@endsection