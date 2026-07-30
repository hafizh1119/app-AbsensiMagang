{{-- resources/views/admin/izin/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Pengajuan Izin')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Detail Pengajuan Izin'])

        <main class="p-6">

            @php
                $statusStyle = [
                    'menunggu'  => 'bg-yellow-100 text-yellow-700',
                    'disetujui' => 'bg-emerald-100 text-emerald-700',
                    'ditolak'   => 'bg-red-100 text-red-700',
                ];

                $statusLabel = [
                    'menunggu'  => 'Menunggu Persetujuan',
                    'disetujui' => 'Disetujui',
                    'ditolak'   => 'Ditolak',
                ];

                $statusLower = strtolower($pengajuan->status);
            @endphp

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl px-4 py-3 mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tombol Kembali --}}
            <a href="{{ route('admin.izin.semua') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Semua Pengajuan
            </a>

            {{-- Card full width --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="shrink-0 w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <path d="M14 2v6h6"></path>
                            </svg>
                        </div>
                        <h2 class="text-base font-semibold text-gray-900">{{ $pengajuan->jenisIzin->nama_izin ?? '-' }}</h2>
                    </div>

                    <span class="px-3 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap {{ $statusStyle[$statusLower] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $statusLabel[$statusLower] ?? $pengajuan->status }}
                    </span>
                </div>

                {{-- Detail 2 kolom --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 px-6 py-5">

                    <div class="flex items-center justify-between py-3 border-b border-gray-50">
                        <span class="text-sm text-gray-400">Diajukan oleh</span>
                        <span class="text-sm text-gray-800 font-medium text-right">
                            {{ $pengajuan->user->nama ?? '-' }}
                            <span class="text-gray-400 font-normal">({{ $pengajuan->user->jabatan ?? '-' }})</span>
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-3 border-b border-gray-50">
                        <span class="text-sm text-gray-400">NIP</span>
                        <span class="text-sm text-gray-800 text-right">{{ $pengajuan->user->nip ?? '-' }}</span>
                    </div>

                    <div class="flex items-center justify-between py-3 border-b border-gray-50">
                        <span class="text-sm text-gray-400">Unit</span>
                        <span class="text-sm text-gray-800 text-right">{{ $pengajuan->user->unit->nama_unit ?? '-' }}</span>
                    </div>

                    <div class="flex items-center justify-between py-3 border-b border-gray-50">
                        <span class="text-sm text-gray-400">Tanggal Pengajuan</span>
                        <span class="text-sm text-gray-800 text-right">
                            {{ $pengajuan->tanggal_pengajuan->translatedFormat('d M Y') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-3 border-b border-gray-50">
                        <span class="text-sm text-gray-400">Tanggal Izin</span>
                        <span class="text-sm text-gray-800 text-right">
                            {{ $pengajuan->tanggal_mulai->translatedFormat('d M Y') }}
                            @if($pengajuan->tanggal_selesai && !$pengajuan->tanggal_mulai->equalTo($pengajuan->tanggal_selesai))
                                - {{ $pengajuan->tanggal_selesai->translatedFormat('d M Y') }}
                            @endif
                            <span class="text-gray-400"> ({{ $pengajuan->durasi }} hari)</span>
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-3 border-b border-gray-50">
                        <span class="text-sm text-gray-400">Lampiran</span>
                        <span class="text-sm text-right">
                            @if($pengajuan->lampiran)
                                <a href="{{ asset($pengajuan->lampiran) }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 text-emerald-600 hover:underline">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <path d="M14 2v6h6"></path>
                                    </svg>
                                    {{ basename($pengajuan->lampiran) }}
                                </a>
                            @else
                                <span class="text-gray-400">Tidak ada</span>
                            @endif
                        </span>
                    </div>

                    {{-- Keterangan full width 2 kolom --}}
                    <div class="md:col-span-2 flex items-start justify-between py-3 border-b border-gray-50 gap-6">
                        <span class="text-sm text-gray-400 shrink-0">Keterangan</span>
                        <span class="text-sm text-gray-800 text-right leading-relaxed">{{ $pengajuan->alasan }}</span>
                    </div>

                    {{-- Info tambahan jika sudah diproses --}}
                    @if($statusLower !== 'menunggu')
                        <div class="flex items-center justify-between py-3 border-b border-gray-50">
                            <span class="text-sm text-gray-400">Tanggal Persetujuan</span>
                            <span class="text-sm text-gray-800 text-right">
                                {{ $pengajuan->tanggal_persetujuan ? \Carbon\Carbon::parse($pengajuan->tanggal_persetujuan)->translatedFormat('d M Y, H:i') : '-' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between py-3 border-b border-gray-50">
                            <span class="text-sm text-gray-400">Diproses oleh</span>
                            <span class="text-sm text-gray-800 text-right">{{ $pengajuan->penyetuju->nama ?? '-' }}</span>
                        </div>

                        @if($statusLower === 'ditolak')
                            <div class="md:col-span-2 flex items-start justify-between py-3 border-b border-gray-50 gap-6">
                                <span class="text-sm text-gray-400 shrink-0">Catatan Penolakan</span>
                                <span class="text-sm text-gray-800 text-right">{{ $pengajuan->catatan ?? '-' }}</span>
                            </div>
                        @endif
                    @endif

                </div>

                {{-- Aksi (di dalam card, hanya tampil kalau masih menunggu) --}}
                @if($statusLower === 'menunggu')
                    <div class="border-t border-gray-100 px-6 py-5">

                        <div class="max-w-xs mb-4">
                            <label class="block text-xs text-gray-400 mb-1.5">Tanggal Persetujuan</label>
                            <input type="date" id="tanggal_persetujuan_input" value="{{ now()->format('Y-m-d') }}"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <p class="text-xs text-gray-400 mt-1.5">Isi sesuai tanggal keputusan yang sebenarnya jika berbeda dari hari ini.</p>
                        </div>

                        <div class="flex items-center justify-end gap-3">

                            {{-- Tombol Tolak --}}
                            <button type="button" onclick="document.getElementById('modal-tolak').classList.remove('hidden')"
                                    class="px-6 text-center border-2 border-red-200 text-red-500 font-semibold rounded-xl py-2.5 hover:bg-red-50 transition">
                                Tolak
                            </button>

                            {{-- Tombol Setuju --}}
                            <form action="{{ route('admin.izin.approve', $pengajuan->id) }}" method="POST" id="form-approve">
                                @csrf
                                <input type="hidden" name="tanggal_persetujuan" id="tanggal_persetujuan_approve">
                                <button type="submit"
                                        class="px-6 text-center bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl py-2.5 shadow-sm transition">
                                    Setujui
                                </button>
                            </form>
                        </div>

                    </div>
                @endif

            </div>

        </main>
    @include('admin.components.footer')
    </div>

</div>

{{-- Modal Tolak --}}
@if($statusLower === 'menunggu')
    <div id="modal-tolak" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center px-5 z-50">
        <div class="bg-white rounded-2xl w-full max-w-sm p-5">
            <h3 class="text-base font-semibold text-gray-900 mb-1">Tolak Pengajuan</h3>
            <p class="text-sm text-gray-500 mb-4">Berikan alasan penolakan untuk pegawai.</p>

            <form action="{{ route('admin.izin.reject', $pengajuan->id) }}" method="POST" id="form-reject">
                @csrf
                <input type="hidden" name="tanggal_persetujuan" id="tanggal_persetujuan_reject">

                <textarea name="catatan" rows="3" required placeholder="Tulis alasan penolakan..."
                          class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 resize-none focus:outline-none focus:ring-2 focus:ring-red-400"></textarea>

                <div class="flex items-center gap-3 mt-4">
                    <button type="button" onclick="document.getElementById('modal-tolak').classList.add('hidden')"
                            class="flex-1 text-center border-2 border-gray-200 text-gray-600 font-semibold rounded-xl py-2.5">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 text-center bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl py-2.5">
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Sinkronkan tanggal persetujuan manual ke kedua form (approve & reject)
        const tglInput = document.getElementById('tanggal_persetujuan_input');
        const formApprove = document.getElementById('form-approve');
        const formReject = document.getElementById('form-reject');

        formApprove.addEventListener('submit', function () {
            document.getElementById('tanggal_persetujuan_approve').value = tglInput.value;
        });

        formReject.addEventListener('submit', function () {
            document.getElementById('tanggal_persetujuan_reject').value = tglInput.value;
        });
    </script>
@endif

@endsection