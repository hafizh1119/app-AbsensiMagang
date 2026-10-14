{{-- resources/views/admin/izin/menunggu.blade.php --}}
@extends('layouts.app')

@section('title', 'Menunggu Persetujuan')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Menunggu Persetujuan'])

        <main class="p-6">

            @if (session('success'))
                <div class="mb-4 flex items-center gap-3 bg-sky-50 border border-sky-100 text-sky-600 text-sm rounded-xl px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 bg-rose-50 border border-rose-100 text-rose-600 text-sm rounded-xl px-4 py-3">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Ringkasan --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">

                {{-- Total Menunggu --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs text-gray-400 mb-1">Total Menunggu</p>
                    <p class="text-3xl font-extrabold text-amber-500">{{ $totalMenunggu }}</p>
                    <p class="text-xs text-gray-400 mt-1">Pengajuan menunggu persetujuan</p>
                </div>

                {{-- Total Izin --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs text-gray-400 mb-1">Total Izin</p>
                    <p class="text-3xl font-extrabold text-sky-500">{{ $totalIzin }}</p>
                    <p class="text-xs text-gray-400 mt-1">Seluruh pengajuan izin</p>
                </div>

            </div>

            {{-- Tabel --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 text-xs">
                                <th class="text-left font-medium px-3 py-2 w-8">No</th>
                                <th class="text-left font-medium px-3 py-2">Tanggal Pengajuan</th>
                                <th class="text-left font-medium px-3 py-2">Nama Peserta</th>
                                <th class="text-left font-medium px-3 py-2">No. Peserta</th>
                                <th class="text-left font-medium px-3 py-2">Unit</th>
                                <th class="text-left font-medium px-3 py-2">Izin</th>
                                <th class="text-left font-medium px-3 py-2 max-w-[130px]">Tanggal Izin</th>
                                <th class="text-left font-medium px-3 py-2 w-14">Durasi</th>
                                <th class="text-left font-medium px-3 py-2 max-w-[140px]">Alasan</th>
                                <th class="text-left font-medium px-3 py-2">Diajukan Kepada</th>
                                <th class="text-center font-medium px-3 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($pengajuan as $row)
                                @php
                                    $tanggalIzin = $row->tanggal_mulai->format('d M Y');
                                    if ($row->tanggal_selesai && !$row->tanggal_mulai->equalTo($row->tanggal_selesai)) {
                                        $tanggalIzin .= ' - ' . $row->tanggal_selesai->format('d M Y');
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50/60 transition-colors"
                                    x-data="{ approveOpen: false, rejectOpen: false }">
                                    <td class="px-3 py-2 text-gray-500">{{ $loop->iteration + ($pengajuan->currentPage() - 1) * $pengajuan->perPage() }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $row->tanggal_pengajuan->format('d M Y H:i') }}</td>
                                    <td class="px-3 py-2 font-medium text-gray-700">{{ $row->user->nama ?? '-' }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $row->user->nip ?? '-' }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $row->user->unit->nama_unit ?? '-' }}</td>

                                    {{-- Izin (card) --}}
                                    <td class="px-3 py-2">
                                        <span class="inline-block bg-sky-50 text-sky-600 border border-sky-100 rounded-lg px-3 py-1 text-xs font-semibold">
                                            {{ $row->jenisIzin->nama_izin ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="px-3 py-2 text-gray-500 max-w-[130px] whitespace-normal">{{ $tanggalIzin }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $row->durasi }} Hari</td>
                                    <td class="px-3 py-2 text-gray-500 max-w-[140px] whitespace-normal">{{ $row->alasan }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $row->penyetuju->nama ?? '-' }}</td>

                                    {{-- Aksi (tombol teks + card popup, tanpa icon) --}}
                                    <td class="px-3 py-2 text-center">
                                        <div class="flex items-center justify-center gap-2">

                                            <a href="{{ route('admin.izin.show', $row->id) }}"
                                               class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-gray-500 hover:bg-gray-600 text-white transition">
                                                Detail
                                            </a>

                                            <button type="button" @click="approveOpen = true"
                                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-sky-500 hover:bg-sky-600 text-white transition">
                                                Setujui
                                            </button>

                                            <button type="button" @click="rejectOpen = true"
                                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-rose-500 hover:bg-rose-600 text-white transition">
                                                Tolak
                                            </button>

                                            {{-- Card konfirmasi Setujui --}}
                                            <div x-show="approveOpen"
                                                 x-transition.opacity
                                                 style="display: none;"
                                                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">

                                                <div x-show="approveOpen"
                                                     x-transition:enter="transition ease-out duration-200"
                                                     x-transition:enter-start="opacity-0 scale-95"
                                                     x-transition:enter-end="opacity-100 scale-100"
                                                     @click.outside="approveOpen = false"
                                                     class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-left">

                                                    <h3 class="text-base font-bold text-gray-900 mb-1">Setujui Pengajuan?</h3>
                                                    <p class="text-sm text-gray-500 mb-4 whitespace-normal">
                                                        Pengajuan {{ $row->jenisIzin->nama_izin ?? 'izin' }} dari
                                                        <span class="font-semibold text-gray-700">{{ $row->user->nama ?? '-' }}</span>
                                                        untuk tanggal {{ $tanggalIzin }} ({{ $row->durasi }} Hari) akan disetujui.
                                                    </p>

                                                    <div class="flex items-center gap-3">
                                                        <button type="button" @click="approveOpen = false"
                                                                class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                                            Batal
                                                        </button>
                                                        <form action="{{ route('admin.izin.approve', $row->id) }}" method="POST" class="flex-1">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="w-full text-sm font-semibold px-4 py-2.5 rounded-lg bg-sky-500 hover:bg-sky-600 text-white transition">
                                                                Ya, Setujui
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Card Tolak (wajib isi catatan) --}}
                                            <div x-show="rejectOpen"
                                                 x-transition.opacity
                                                 style="display: none;"
                                                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">

                                                <div x-show="rejectOpen"
                                                     x-transition:enter="transition ease-out duration-200"
                                                     x-transition:enter-start="opacity-0 scale-95"
                                                     x-transition:enter-end="opacity-100 scale-100"
                                                     @click.outside="rejectOpen = false"
                                                     class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-left">

                                                    <h3 class="text-base font-bold text-gray-900 mb-1">Tolak Pengajuan?</h3>
                                                    <p class="text-sm text-gray-500 mb-4 whitespace-normal">
                                                        Pengajuan dari
                                                        <span class="font-semibold text-gray-700">{{ $row->user->nama ?? '-' }}</span>
                                                        akan ditolak. Tulis alasan penolakan di bawah.
                                                    </p>

                                                    <form action="{{ route('admin.izin.reject', $row->id) }}" method="POST">
                                                        @csrf
                                                        <textarea name="catatan" rows="3" required
                                                                  placeholder="Alasan penolakan"
                                                                  class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-rose-400 resize-none mb-4"></textarea>

                                                        <div class="flex items-center gap-3">
                                                            <button type="button" @click="rejectOpen = false"
                                                                    class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                                                Batal
                                                            </button>
                                                            <button type="submit"
                                                                    class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg bg-rose-500 hover:bg-rose-600 text-white transition">
                                                                Tolak
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="px-3 py-6 text-center text-gray-400">Tidak ada pengajuan yang menunggu persetujuan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-between px-5 py-4 border-t border-gray-100">
                    <p class="text-sm text-gray-400">
                        Menampilkan {{ $pengajuan->firstItem() ?? 0 }} - {{ $pengajuan->lastItem() ?? 0 }} dari {{ $pengajuan->total() }} data
                    </p>
                    <div class="flex items-center gap-1.5">
                        {{ $pengajuan->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>

        </main>
    @include('admin.components.footer')
    </div>

</div>

@endsection