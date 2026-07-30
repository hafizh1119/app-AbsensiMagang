{{-- resources/views/admin/data-master/jenis-izin/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Jenis Izin')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto" x-data="{ createOpen: false }">

        @include('admin.components.header', ['title' => 'Jenis Izin'])

        <main class="p-6">

            {{-- Flash message --}}
            @if (session('success'))
                <div class="mb-4 flex items-center gap-2 bg-emerald-50 text-emerald-700 text-sm font-medium px-4 py-3 rounded-xl border border-emerald-100">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 flex items-center gap-2 bg-rose-50 text-rose-600 text-sm font-medium px-4 py-3 rounded-xl border border-rose-100">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Judul & Tombol Tambah --}}
            <div class="flex items-center justify-between mb-1">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Jenis Izin</h2>
                    <p class="text-sm text-gray-400 mt-1">Kelola data jenis izin yang tersedia</p>
                </div>
                <button type="button" @click="createOpen = true"
                   class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Tambah Jenis Izin
                </button>
            </div>

            {{-- Kartu Statistik --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 my-5">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 bg-emerald-50 text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total Jenis Izin</p>
                        <p class="text-2xl font-bold text-gray-900 leading-tight">{{ $stats['total_jenis'] }}</p>
                        <p class="text-xs text-gray-400">Jenis</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 bg-blue-50 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total Pengajuan</p>
                        <p class="text-2xl font-bold text-gray-900 leading-tight">{{ $stats['total_pengajuan'] }}</p>
                        <p class="text-xs text-gray-400">Pengajuan</p>
                    </div>
                </div>
            </div>

            {{-- Card utama: filter + tabel --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">

                {{-- Filter --}}
                <div class="flex flex-wrap items-end gap-3 p-5 border-b border-gray-100">
                    <div class="flex-1 min-w-[220px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Cari</label>
                        <div class="relative">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="7"/>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                            <input type="text" placeholder="Cari nama jenis izin..."
                                   class="w-full bg-white border border-gray-200 rounded-lg pl-9 pr-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button"
                                class="flex items-center gap-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-semibold px-4 py-2.5 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5M4 9a8 8 0 0 1 14.5-4.5M20 15a8 8 0 0 1-14.5 4.5"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Tabel --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 text-xs">
                                <th class="text-left font-medium px-4 py-3 w-10">No</th>
                                <th class="text-left font-medium px-4 py-3">Nama Jenis Izin</th>
                                <th class="text-left font-medium px-4 py-3">Deskripsi</th>
                                <th class="text-left font-medium px-4 py-3">Kuota / Tahun</th>
                                <th class="text-left font-medium px-4 py-3">Kategori Absensi</th>
                                <th class="text-left font-medium px-4 py-3">Wajib Absen</th>
                                <th class="text-center font-medium px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($jenisIzin as $row)
                                @php
                                    $kategoriLabel = [
                                        'izin'           => 'Izin',
                                        'sakit'          => 'Sakit',
                                        'cuti'           => 'Cuti',
                                        'izin_terlambat' => 'Izin Terlambat',
                                    ];
                                @endphp
                                <tr class="hover:bg-gray-50/60 transition-colors" x-data="{ confirmOpen: false, editOpen: false }">
                                    <td class="px-4 py-3 text-gray-500">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 text-gray-700 font-medium">{{ $row->nama_izin }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $row->deskripsi ?: '-' }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $row->kuota }} Hari</td>
                                    <td class="px-4 py-3 text-gray-500">
                                        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full bg-blue-50 text-blue-600">
                                            {{ $kategoriLabel[$row->kategori] ?? $row->kategori }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">
                                        @if ($row->wajib_absen)
                                            <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full bg-emerald-50 text-emerald-600">Ya</span>
                                        @else
                                            <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full bg-gray-100 text-gray-500">Tidak</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">

                                            {{-- Tombol Edit --}}
                                            <button type="button" @click="editOpen = true"
                                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white transition">
                                                Edit
                                            </button>

                                            {{-- Tombol Hapus --}}
                                            <button type="button" @click="confirmOpen = true"
                                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-rose-500 hover:bg-rose-600 text-white transition">
                                                Hapus
                                            </button>

                                            {{-- Modal Edit --}}
                                            <div x-show="editOpen" x-transition.opacity style="display: none;"
                                                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
                                                <div x-show="editOpen"
                                                     x-transition:enter="transition ease-out duration-200"
                                                     x-transition:enter-start="opacity-0 scale-95"
                                                     x-transition:enter-end="opacity-100 scale-100"
                                                     @click.outside="editOpen = false"
                                                     class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 text-left">

                                                    <h3 class="text-base font-bold text-gray-900 mb-4">Edit Jenis Izin</h3>

                                                    <form action="{{ route('admin.data-master.jenis-izin.update', $row->id) }}" method="POST" class="space-y-4">
                                                        @csrf
                                                        @method('PUT')

                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Nama Jenis Izin</label>
                                                            <input type="text" name="nama_izin" value="{{ $row->nama_izin }}" required
                                                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                                        </div>

                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Deskripsi</label>
                                                            <textarea name="deskripsi" rows="2"
                                                                      class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ $row->deskripsi }}</textarea>
                                                        </div>

                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Kuota per Tahun (Hari)</label>
                                                            <input type="number" name="kuota" value="{{ $row->kuota }}" min="0" max="365" required
                                                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                                        </div>

                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Kategori Absensi</label>
                                                            <select name="kategori" required
                                                                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                                                <option value="izin" @selected($row->kategori === 'izin')>Izin</option>
                                                                <option value="sakit" @selected($row->kategori === 'sakit')>Sakit</option>
                                                                <option value="cuti" @selected($row->kategori === 'cuti')>Cuti</option>
                                                                <option value="izin_terlambat" @selected($row->kategori === 'izin_terlambat')>Izin Terlambat</option>
                                                            </select>
                                                            <p class="text-xs text-gray-400 mt-1">Menentukan status apa yang tercatat di absensi saat pengajuan jenis izin ini disetujui.</p>
                                                        </div>

                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Wajib Absen Fisik?</label>
                                                            <select name="wajib_absen" required
                                                                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                                                <option value="1" @selected($row->wajib_absen)>Ya, tetap wajib absen masuk/pulang</option>
                                                                <option value="0" @selected(!$row->wajib_absen)>Tidak, langsung dianggap izin penuh hari</option>
                                                            </select>
                                                        </div>

                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Status</label>
                                                            <select name="status" required
                                                                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                                                <option value="1" @selected($row->status)>Aktif</option>
                                                                <option value="0" @selected(!$row->status)>Tidak Aktif</option>
                                                            </select>
                                                        </div>

                                                        <div class="flex items-center gap-3 pt-2">
                                                            <button type="button" @click="editOpen = false"
                                                                    class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                                                Batal
                                                            </button>
                                                            <button type="submit"
                                                                    class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white transition">
                                                                Simpan
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            {{-- Modal Hapus --}}
                                            <div x-show="confirmOpen" x-transition.opacity style="display: none;"
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

                                                    <h3 class="text-base font-bold text-gray-900 mb-1">Hapus Jenis Izin?</h3>
                                                    <p class="text-sm text-gray-500 mb-6">
                                                        Data yang sudah dihapus tidak dapat dikembalikan. Pastikan kamu yakin sebelum melanjutkan.
                                                    </p>

                                                    <div class="flex items-center gap-3">
                                                        <button type="button" @click="confirmOpen = false"
                                                                class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                                            Batal
                                                        </button>
                                                        <form action="{{ route('admin.data-master.jenis-izin.destroy', $row->id) }}" method="POST" class="flex-1">
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
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-400">
                                        Belum ada data jenis izin.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-between px-5 py-4 border-t border-gray-100">
                    <p class="text-sm text-gray-400">Menampilkan {{ $jenisIzin->count() }} dari {{ $jenisIzin->count() }} data</p>
                </div>

            </div>

        </main>
    @include('admin.components.footer')
        {{-- Modal Tambah Jenis Izin --}}
        <div x-show="createOpen" x-transition.opacity style="display: none;"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
            <div x-show="createOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 @click.outside="createOpen = false"
                 class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 text-left">

                <h3 class="text-base font-bold text-gray-900 mb-4">Tambah Jenis Izin</h3>

                <form action="{{ route('admin.data-master.jenis-izin.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Nama Jenis Izin</label>
                        <input type="text" name="nama_izin" placeholder="Contoh: Cuti Tahunan" required
                               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Deskripsi</label>
                        <textarea name="deskripsi" rows="2" placeholder="Deskripsi singkat jenis izin"
                                  class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Kuota per Tahun (Hari)</label>
                        <input type="number" name="kuota" placeholder="Contoh: 12" min="0" max="365" required
                               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <p class="text-xs text-gray-400 mt-1">Jatah hari yang didapat tiap pegawai per tahun untuk jenis izin ini.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Kategori Absensi</label>
                        <select name="kategori" required
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="cuti">Cuti</option>
                            <option value="izin_terlambat">Izin Terlambat</option>
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Menentukan status apa yang tercatat di absensi saat pengajuan jenis izin ini disetujui.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Wajib Absen Fisik?</label>
                        <select name="wajib_absen" required
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="1">Ya, tetap wajib absen masuk/pulang</option>
                            <option value="0" selected>Tidak, langsung dianggap izin penuh hari</option>
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Contoh "Izin Terlambat" pilih Ya, contoh "Cuti"/"Sakit" pilih Tidak.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Status</label>
                        <select name="status" required
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="1" selected>Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="button" @click="createOpen = false"
                                class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white transition">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>

@endsection