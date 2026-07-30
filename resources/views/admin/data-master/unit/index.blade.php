{{-- resources/views/admin/data-master/unit/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Unit')

@section('content')

<div class="flex h-screen overflow-hidden"
     x-data="{
        createOpen: {{ $errors->any() && old('_form') === 'create' ? 'true' : 'false' }},
        editOpen: {{ $errors->any() && old('_form') === 'edit' ? 'true' : 'false' }},
        form: {
            id: {{ old('_form') === 'edit' ? (int) old('id') : 'null' }},
            nama_unit: @js(old('nama_unit', '')),
            deskripsi: @js(old('deskripsi', '')),
            status: @js(old('status', 'Aktif'))
        },
        openCreate() {
            this.form = { id: null, nama_unit: '', deskripsi: '', status: 'Aktif' };
            this.createOpen = true;
        },
        openEdit(row) {
            this.form = { id: row.id, nama_unit: row.nama, deskripsi: row.deskripsi ?? '', status: row.status };
            this.editOpen = true;
        }
     }">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Unit'])

        <main class="p-6">

            @if (session('success'))
                <div id="toastSuccess"
                     class="fixed top-5 right-5 z-[9999] flex items-center gap-3 bg-white border border-emerald-100 shadow-lg rounded-xl px-4 py-3 pr-5">
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Berhasil</p>
                        <p class="text-xs text-gray-500">{{ session('success') }}</p>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var toast = document.getElementById('toastSuccess');
                        if (!toast) return;
                        setTimeout(function () {
                            toast.style.transition = 'all .3s';
                            toast.style.opacity = '0';
                            toast.style.transform = 'translateX(120%)';
                            setTimeout(function () { toast.remove(); }, 300);
                        }, 3000);
                    });
                </script>
            @endif

            @if (session('error'))
                <div class="mb-4 flex items-center gap-3 bg-rose-50 border border-rose-100 text-rose-600 text-sm rounded-xl px-4 py-3">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4M12 16h.01"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Judul & Tombol Tambah --}}
            <div class="flex items-center justify-between mb-1">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Unit</h2>
                    <p class="text-sm text-gray-400 mt-1">Kelola data jenis unit atau divisi di lingkungan dinas</p>
                </div>
                <button type="button" @click="openCreate()"
                        class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Tambah Unit
                </button>
            </div>

            {{-- Kartu Statistik --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 my-5">
                @foreach ($stats as $stat)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0
                            {{ match($stat['color']) {
                                'emerald' => 'bg-emerald-50 text-emerald-600',
                                'blue'    => 'bg-blue-50 text-blue-600',
                                'amber'   => 'bg-amber-50 text-amber-600',
                                'rose'    => 'bg-rose-50 text-rose-500',
                                default   => 'bg-gray-50 text-gray-500',
                            } }}">
                            @switch($stat['icon'])
                                @case('building')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="4" y="3" width="16" height="18" rx="1"/>
                                        <path stroke-linecap="round" d="M9 7h1M14 7h1M9 11h1M14 11h1M9 15h1M14 15h1"/>
                                    </svg>
                                    @break
                                @case('users')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/>
                                        <circle cx="10" cy="7" r="4"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                                    </svg>
                                    @break
                            @endswitch
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">{{ $stat['label'] }}</p>
                            <p class="text-2xl font-bold text-gray-900 leading-tight">{{ $stat['value'] }}</p>
                            <p class="text-xs text-gray-400">{{ $stat['suffix'] }}</p>
                        </div>
                    </div>
                @endforeach
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
                            <input type="text" placeholder="Cari nama unit atau kode..."
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
                                <th class="text-left font-medium px-4 py-3">Nama Unit</th>
                                <th class="text-left font-medium px-4 py-3">Deskripsi</th>
                                <th class="text-center font-medium px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($unit as $row)
                                <tr class="hover:bg-gray-50/60 transition-colors" x-data="{ confirmOpen: false }">
                                    <td class="px-4 py-3 text-gray-500">{{ $row['no'] }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $row['nama'] }}</td>
                                    <td class="px-4 py-3 text-gray-500 max-w-[280px] truncate" title="{{ $row['deskripsi'] }}">
                                        {{ $row['deskripsi'] ?: '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button"
                                                    @click="openEdit(@js($row))"
                                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white transition">
                                                Edit
                                            </button>

                                            <button type="button" @click="confirmOpen = true"
                                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-rose-500 hover:bg-rose-600 text-white transition">
                                                Hapus
                                            </button>

                                            {{-- Modal Konfirmasi Hapus --}}
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

                                                    <h3 class="text-base font-bold text-gray-900 mb-1">Hapus Data Unit?</h3>
                                                    <p class="text-sm text-gray-500 mb-6">
                                                        Data yang sudah dihapus tidak dapat dikembalikan. Pastikan kamu yakin sebelum melanjutkan.
                                                    </p>

                                                    <div class="flex items-center gap-3">
                                                        <button type="button" @click="confirmOpen = false"
                                                                class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                                            Batal
                                                        </button>
                                                        <form action="{{ route('admin.data-master.unit.destroy', $row['id']) }}" method="POST" class="flex-1">
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
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada data jenis unit.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-between px-5 py-4 border-t border-gray-100">
                    <p class="text-sm text-gray-400">Menampilkan 1 - {{ $unit->count() }} dari {{ $unit->count() }} data</p>
                    <div class="flex items-center gap-1.5">
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 disabled:opacity-40" disabled>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-600 text-white text-sm font-semibold">1</button>
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100" disabled>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>

            </div>

            {{-- ============ MODAL TAMBAH UNIT ============ --}}
            <div x-show="createOpen"
                 x-transition.opacity
                 style="display: none;"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">

                <div x-show="createOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     @click.outside="createOpen = false"
                     class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">

                    <h3 class="text-base font-bold text-gray-900 mb-4">Tambah Unit</h3>

                    <form action="{{ route('admin.data-master.unit.store') }}" method="POST" class="space-y-4 text-sm text-left">
                        @csrf
                        <input type="hidden" name="_form" value="create">

                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Nama Unit <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_unit" x-model="form.nama_unit" placeholder="Contoh: Struktural"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('nama_unit') border-rose-400 @enderror">
                            @error('nama_unit')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Deskripsi</label>
                            <textarea name="deskripsi" x-model="form.deskripsi" rows="3" placeholder="Deskripsi singkat jenis unit ini"
                                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none @error('deskripsi') border-rose-400 @enderror"></textarea>
                            @error('deskripsi')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
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

            {{-- ============ MODAL EDIT UNIT ============ --}}
            <div x-show="editOpen"
                 x-transition.opacity
                 style="display: none;"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">

                <div x-show="editOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     @click.outside="editOpen = false"
                     class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">

                    <h3 class="text-base font-bold text-gray-900 mb-4">Edit Unit</h3>

                    <form :action="'{{ url('/admin/data-master/unit') }}/' + form.id" method="POST" class="space-y-4 text-sm text-left">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="_form" value="edit">
                        <input type="hidden" name="id" :value="form.id">

                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Nama Unit <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_unit" x-model="form.nama_unit" placeholder="Contoh: Struktural"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('nama_unit') border-rose-400 @enderror">
                            @error('nama_unit')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Deskripsi</label>
                            <textarea name="deskripsi" x-model="form.deskripsi" rows="3" placeholder="Deskripsi singkat jenis unit ini"
                                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none @error('deskripsi') border-rose-400 @enderror"></textarea>
                            @error('deskripsi')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <button type="button" @click="editOpen = false"
                                    class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                Batal
                            </button>
                            <button type="submit"
                                    class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white transition">
                                Simpan Perubahan
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