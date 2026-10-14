{{-- resources/views/admin/data-master/jam-kerja/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Jam Kerja')

@section('content')

<div class="flex h-screen overflow-hidden"
     x-data="{
        createOpen: {{ $errors->any() && old('_form') === 'create' ? 'true' : 'false' }},
        editOpen: {{ $errors->any() && old('_form') === 'edit' ? 'true' : 'false' }},
        semuaHari: @js($semuaHari),
        hariTersedia: @js($hariTersedia),
        form: {
            id: {{ old('_form') === 'edit' ? (int) old('id') : 'null' }},
            hari: @js(old('hari', '')),
            status: @js(old('status', 'aktif')),
            jam_masuk: @js(old('jam_masuk', '08:00')),
            jam_pulang: @js(old('jam_pulang', '16:00')),
            toleransi_terlambat: @js(old('toleransi_terlambat', 15))
        },
        hariEdit() {
            return this.semuaHari.filter(h => h === this.form.hari || this.hariTersedia.includes(h));
        },
        openCreate() {
            this.form = {
                id: null,
                hari: this.hariTersedia[0] ?? '',
                status: 'aktif',
                jam_masuk: '08:00',
                jam_pulang: '16:00',
                toleransi_terlambat: 15
            };
            this.createOpen = true;
        },
        openEdit(row) {
            this.form = {
                id: row.id,
                hari: row.hari,
                status: row.status_val,
                jam_masuk: row.jam_masuk_val ?? '08:00',
                jam_pulang: row.jam_pulang_val ?? '16:00',
                toleransi_terlambat: row.toleransi_val ?? 15
            };
            this.editOpen = true;
        }
     }">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Jam Kerja'])

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

            @php
                $iconColor = [
                    'sky'     => 'bg-sky-50 text-sky-600',
                    'rose'    => 'bg-rose-50 text-rose-500',
                    'amber'   => 'bg-amber-50 text-amber-500',
                ];

                $statusStyle = [
                    'Aktif' => 'bg-sky-50 text-sky-600',
                    'Libur' => 'bg-rose-50 text-rose-500',
                ];
            @endphp

            {{-- Judul Halaman --}}
            <div class="flex flex-wrap items-start justify-between gap-4 mb-5">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Jam Kerja</h1>
                    <p class="text-sm text-gray-400 mt-0.5">Kelola jadwal jam kerja setiap hari.</p>
                </div>

                <div class="flex flex-col items-end gap-2">
                    <button type="button" @click="openCreate()"
                            class="flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Tambah Jam Kerja
                    </button>
                </div>
            </div>

            {{-- Kartu Statistik --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
                @foreach ($stats as $stat)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 {{ $iconColor[$stat['color']] ?? 'bg-gray-50 text-gray-500' }}">
                                @if ($stat['icon'] === 'clock')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                @endif
                            </div>
                            <p class="text-sm font-semibold text-gray-700">{{ $stat['label'] }}</p>
                        </div>
                        <p class="text-2xl font-extrabold text-gray-800">{{ $stat['value'] }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $stat['suffix'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Tabel --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 text-xs">
                                <th class="text-left font-medium px-5 py-3 w-12">No</th>
                                <th class="text-left font-medium px-5 py-3">Hari</th>
                                <th class="text-left font-medium px-5 py-3">Jam Masuk</th>
                                <th class="text-left font-medium px-5 py-3">Jam Pulang</th>
                                <th class="text-left font-medium px-5 py-3">Toleransi Terlambat</th>
                                <th class="text-left font-medium px-5 py-3">Status</th>
                                <th class="text-center font-medium px-5 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($jamKerja as $row)
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-5 py-3 text-gray-500">{{ $row['no'] }}</td>
                                    <td class="px-5 py-3 font-medium text-gray-700">{{ $row['hari'] }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ $row['jam_masuk'] ?? '-' }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ $row['jam_pulang'] ?? '-' }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ $row['toleransi'] ?? '-' }}</td>
                                    <td class="px-5 py-3">
                                        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full {{ $statusStyle[$row['status']] ?? 'bg-gray-100 text-gray-500' }}">
                                            {{ $row['status'] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="flex items-center justify-center gap-1" x-data="{ confirmOpen: false }">

                                            {{-- Tombol Edit --}}
                                            <button type="button" @click="openEdit(@js($row))"
                                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-sky-500 hover:bg-sky-600 text-white transition">
                                                Edit
                                            </button>

                                            @if ($row['status'] === 'Aktif')
                                                {{-- Tombol Hapus --}}
                                                <button type="button" @click="confirmOpen = true"
                                                        class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-rose-500 hover:bg-rose-600 text-white transition">
                                                    Hapus
                                                </button>

                                                {{-- Overlay --}}
                                                <div x-show="confirmOpen"
                                                    x-transition.opacity
                                                    style="display: none;"
                                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">

                                                    {{-- Card konfirmasi --}}
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

                                                        <h3 class="text-base font-bold text-gray-900 mb-1">Hapus Jam Kerja?</h3>
                                                        <p class="text-sm text-gray-500 mb-6">
                                                            Jadwal jam kerja hari {{ $row['hari'] }} akan dihapus dan tidak dapat dikembalikan. Pastikan kamu yakin sebelum melanjutkan.
                                                        </p>

                                                        <div class="flex items-center gap-3">
                                                            <button type="button" @click="confirmOpen = false"
                                                                    class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                                                Batal
                                                            </button>
                                                            <form action="{{ route('admin.data-master.jam-kerja.destroy', $row['id']) }}" method="POST" class="flex-1">
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
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-8 text-center text-gray-400">Belum ada data jam kerja.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Modal tambah & edit (file terpisah) --}}
            @include('admin.data-master.jam-kerja.create')
            @include('admin.data-master.jam-kerja.edit')

        </main>
    @include('admin.components.footer')
    </div>

</div>

@endsection