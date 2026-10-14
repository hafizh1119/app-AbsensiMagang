{{-- resources/views/admin/absensi/semua.blade.php --}}
@extends('layouts.app')

@section('title', 'Semua Absensi')

@section('content')

<div class="flex h-screen overflow-hidden"
     x-data="{
        editOpen: {{ $errors->any() && old('_form') === 'edit' ? 'true' : 'false' }},
        form: {
            id: {{ old('_form') === 'edit' ? (int) old('id') : 'null' }},
            nama: @js(old('info_nama', '')),
            nip: @js(old('info_nip', '')),
            tanggal: @js(old('tanggal', '')),
            jam_masuk: @js(old('jam_masuk', '')),
            jam_pulang: @js(old('jam_pulang', '')),
            status: @js(old('status', 'hadir')),
            keterangan: @js(old('keterangan', ''))
        },
        openEdit(row) {
            this.form = {
                id: row.id,
                nama: row.nama,
                nip: row.nip,
                tanggal: row.tanggal,
                jam_masuk: row.jam_masuk,
                jam_pulang: row.jam_pulang,
                status: row.status,
                keterangan: row.keterangan
            };
            this.editOpen = true;
        }
     }">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Semua Absensi'])

        <main class="p-6">

            @if (session('success'))
                <div class="mb-4 flex items-center gap-3 bg-sky-50 border border-sky-100 text-sky-600 text-sm rounded-xl px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $statusStyle = [
                    'hadir'          => 'bg-emerald-50 text-emerald-600',
                    'terlambat'      => 'bg-amber-50 text-amber-600',
                    'izin'           => 'bg-blue-50 text-blue-600',
                    'sakit'          => 'bg-blue-50 text-blue-600',
                    'cuti'           => 'bg-purple-50 text-purple-600',
                    'izin_terlambat' => 'bg-orange-50 text-orange-600',
                    'tidak_hadir'    => 'bg-rose-50 text-rose-500',
                    'alpha'          => 'bg-rose-50 text-rose-500',
                ];

                // ---- Filter ----
                $dari   = request('dari');
                $sampai = request('sampai');

                $presets = [
                    'semua'      => 'Semua',
                    'hari_ini'   => 'Hari Ini',
                    'minggu_ini' => 'Minggu Ini',
                    'bulan_ini'  => 'Bulan Ini',
                ];

                $btnBase   = 'text-sm font-semibold px-4 py-2 rounded-lg transition duration-150';
                $btnActive = 'bg-sky-500 text-white shadow-sm';
                $btnIdle   = 'bg-gray-100 text-gray-600 hover:bg-gray-200';
                $inputCls  = 'border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent';

                // Parameter untuk link export (mengikuti filter yang sedang aktif)
                $exportParams = array_filter([
                    'filter' => $filter,
                    'dari'   => $filter === 'custom' ? $dari : null,
                    'sampai' => $filter === 'custom' ? $sampai : null,
                    'search' => $search,
                ]);
            @endphp

            {{-- Filter Bar --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4"
                 x-data="{ customOpen: {{ $filter === 'custom' ? 'true' : 'false' }} }">

                {{-- Baris 1: tombol periode --}}
                <div class="flex flex-wrap items-center gap-2">
                    @foreach ($presets as $key => $label)
                        <a href="{{ route('admin.absensi.semua', array_filter(['filter' => $key, 'search' => $search])) }}"
                           class="{{ $btnBase }} {{ $filter === $key ? $btnActive : $btnIdle }}">
                            {{ $label }}
                        </a>
                    @endforeach

                    <button type="button" @click="customOpen = !customOpen"
                            class="{{ $btnBase }} inline-flex items-center gap-1.5"
                            :class="customOpen ? '{{ $btnActive }}' : '{{ $btnIdle }}'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                        </svg>
                        Custom
                    </button>
                </div>

                {{-- Panel Custom: dari tanggal - sampai tanggal --}}
                <form action="{{ route('admin.absensi.semua') }}" method="GET"
                      x-show="customOpen" x-transition style="display: none;"
                      x-data="{ dari: @js($dari ?? ''), sampai: @js($sampai ?? '') }"
                      class="mt-3 flex flex-wrap items-end gap-3 bg-gray-50 border border-gray-100 rounded-xl p-3">
                    <input type="hidden" name="filter" value="custom">
                    @if ($search)
                        <input type="hidden" name="search" value="{{ $search }}">
                    @endif

                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5">Dari tanggal</label>
                        <input type="date" name="dari" x-model="dari" :max="sampai || null" required
                               class="{{ $inputCls }}">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5">Sampai tanggal</label>
                        <input type="date" name="sampai" x-model="sampai" :min="dari || null" required
                               class="{{ $inputCls }}">
                    </div>
                    <button type="submit"
                            class="bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold px-4 py-2 rounded-lg transition duration-150 shadow-sm">
                        Terapkan
                    </button>
                </form>

                {{-- Baris 2: pencarian + export --}}
                <form action="{{ route('admin.absensi.semua') }}" method="GET" class="mt-3 flex flex-wrap items-center gap-3">
                    <input type="hidden" name="filter" value="{{ $filter }}">
                    @if ($filter === 'custom')
                        <input type="hidden" name="dari" value="{{ $dari }}">
                        <input type="hidden" name="sampai" value="{{ $sampai }}">
                    @endif

                    <div class="relative flex-1 min-w-[200px]">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                        </svg>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau no. peserta..."
                               class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                    </div>

                    <button type="submit"
                            class="bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold px-4 py-2 rounded-lg transition duration-150 shadow-sm">
                        Cari
                    </button>

                    @if ($filter !== 'semua' || $search)
                        <a href="{{ route('admin.absensi.semua') }}"
                           class="text-sm font-semibold text-gray-500 hover:text-gray-700 px-2 py-2">
                            Reset
                        </a>
                    @endif

                    {{-- Export (mengikuti filter yang sedang diterapkan) --}}
                    <a href="{{ route('admin.absensi.export', $exportParams) }}"
                       class="flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition duration-150 shadow-sm ml-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M7 10l5 5 5-5M12 15V3"/>
                        </svg>
                        Export
                    </a>
                </form>

                {{-- Info periode yang sedang ditampilkan --}}
                @if ($filter !== 'semua')
                    <p class="mt-3 text-xs text-gray-400">
                        Periode: <span class="font-semibold text-gray-600">{{ $periodeLabel }}</span>
                    </p>
                @endif
            </div>

            {{-- Tabel --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 text-xs">
                                <th class="text-left font-medium px-5 py-3 w-12">No.</th>
                                <th class="text-left font-medium px-5 py-3">Tanggal</th>
                                <th class="text-left font-medium px-5 py-3">No. Peserta</th>
                                <th class="text-left font-medium px-5 py-3">Nama Peserta</th>
                                <th class="text-left font-medium px-5 py-3">Jam Masuk</th>
                                <th class="text-left font-medium px-5 py-3">Jam Pulang</th>
                                <th class="text-left font-medium px-5 py-3">Status</th>
                                <th class="text-left font-medium px-5 py-3">Keterangan</th>
                                <th class="text-center font-medium px-5 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($absensis as $absensi)
                                @php
                                    $no = ($absensis->currentPage() - 1) * $absensis->perPage() + $loop->iteration;

                                    $jamMasuk  = $absensi->jam_masuk  ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i')  : '';
                                    $jamPulang = $absensi->jam_pulang ? \Carbon\Carbon::parse($absensi->jam_pulang)->format('H:i') : '';

                                    // Data untuk mengisi modal edit
                                    $editData = [
                                        'id'         => $absensi->id,
                                        'tanggal'    => optional($absensi->tanggal)->format('Y-m-d'),
                                        'nama'       => $absensi->user->nama ?? '-',
                                        'nip'        => $absensi->user->nip ?? '-',
                                        'jam_masuk'  => $jamMasuk,
                                        'jam_pulang' => $jamPulang,
                                        'status'     => $absensi->status,
                                        'keterangan' => $absensi->keterangan ?? '',
                                    ];
                                @endphp
                                <tr class="hover:bg-gray-50/60 transition-colors" x-data="{ confirmOpen: false }">
                                    <td class="px-5 py-3 text-gray-500">{{ $no }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ optional($absensi->tanggal)->format('d/m/Y') }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ $absensi->user->nip ?? '-' }}</td>
                                    <td class="px-5 py-3 font-medium text-gray-700">{{ $absensi->user->nama ?? '-' }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ $jamMasuk ?: '-' }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ $jamPulang ?: '-' }}</td>
                                    <td class="px-5 py-3">
                                        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full {{ $statusStyle[$absensi->status] ?? 'bg-gray-100 text-gray-500' }}">
                                            {{ ucwords(str_replace('_', ' ', $absensi->status ?? '-')) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-gray-500">{{ $absensi->keterangan ?? '-' }}</td>
                                    <td class="px-5 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">

                                            {{-- Tombol Edit --}}
                                            <button type="button" @click="openEdit(@js($editData))"
                                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-sky-500 hover:bg-sky-600 text-white transition">
                                                Edit
                                            </button>

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

                                                    <h3 class="text-base font-bold text-gray-900 mb-1">Hapus Data Absensi?</h3>
                                                    <p class="text-sm text-gray-500 mb-6">
                                                        Data yang sudah dihapus tidak dapat dikembalikan. Pastikan kamu yakin sebelum melanjutkan.
                                                    </p>

                                                    <div class="flex items-center gap-3">
                                                        <button type="button" @click="confirmOpen = false"
                                                                class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                                            Batal
                                                        </button>
                                                        <form action="{{ route('admin.absensi.destroy', $absensi->id) }}" method="POST" class="flex-1">
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
                                    <td colspan="9" class="px-5 py-8 text-center text-gray-400">Belum ada data absensi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-between px-5 py-4 border-t border-gray-100">
                    <p class="text-sm text-gray-400">
                        Menampilkan {{ $absensis->firstItem() ?? 0 }}-{{ $absensis->lastItem() ?? 0 }} dari {{ $absensis->total() }} data
                    </p>
                    {{ $absensis->onEachSide(1)->links() }}
                </div>
            </div>

            {{-- Modal edit (file terpisah) --}}
            @include('admin.absensi.edit')

        </main>
    @include('admin.components.footer')
    </div>

</div>

@endsection