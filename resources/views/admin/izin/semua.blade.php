{{-- resources/views/admin/izin/semua.blade.php --}}
@extends('layouts.app')

@section('title', 'Semua Pengajuan')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Semua Pengajuan'])

        <main class="p-6">

            @php
                $statusStyle = [
                    'menunggu'  => 'bg-yellow-100 text-yellow-700',
                    'disetujui' => 'bg-emerald-100 text-emerald-700',
                    'ditolak'   => 'bg-red-100 text-red-700',
                ];
            @endphp

            {{-- Filter Bar --}}
            <form method="GET" action="{{ route('admin.izin.semua') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div class="flex flex-wrap items-end gap-3">

                        {{-- Tanggal --}}
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                                   class="w-40 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-500">
                        </div>

                        {{-- Unit --}}
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Unit</label>
                            <select name="unit" class="appearance-none w-40 border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
                                <option>Semua Unit</option>
                                @foreach($unitList as $unit)
                                    <option value="{{ $unit }}" @selected(request('unit') == $unit)>{{ $unit }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Izin --}}
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Izin</label>
                            <select name="jenis" class="appearance-none w-40 border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
                                <option value="Semua Jenis">Semua Izin</option>
                                @foreach($jenisIzinList as $jenis)
                                    <option value="{{ $jenis }}" @selected(request('jenis') == $jenis)>{{ $jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Status</label>
                            <select name="status" class="appearance-none w-40 border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
                                <option value="">Semua Status</option>
                                <option value="Disetujui" @selected(request('status') == 'Disetujui')>Disetujui</option>
                                <option value="Menunggu" @selected(request('status') == 'Menunggu')>Menunggu</option>
                                <option value="Ditolak" @selected(request('status') == 'Ditolak')>Ditolak</option>
                            </select>
                        </div>

                        {{-- Filter --}}
                        <button type="submit" class="flex items-center gap-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold px-4 py-2 rounded-lg transition duration-150 shadow-sm">
                            Terapkan
                        </button>

                        {{-- Reset --}}
                        <a href="{{ route('admin.izin.semua') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-700 px-2 py-2">
                            Reset
                        </a>
                    </div>

                    {{-- Export: hanya pengajuan yang disetujui, mengikuti filter tanggal, unit, dan izin --}}
                    <a href="{{ route('admin.izin.export', request()->only('tanggal', 'unit', 'jenis')) }}"
                       title="Hanya mengekspor pengajuan yang disetujui"
                       class="flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition duration-150 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M7 10l5 5 5-5M12 15V3"/>
                        </svg>
                        Export
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
                                <th class="text-left font-medium px-3 py-2">Tanggal Pengajuan</th>
                                <th class="text-left font-medium px-3 py-2">Nama Peserta</th>
                                <th class="text-left font-medium px-3 py-2">No. Peserta</th>
                                <th class="text-left font-medium px-3 py-2">Unit</th>
                                <th class="text-left font-medium px-3 py-2">Izin</th>
                                <th class="text-left font-medium px-3 py-2 max-w-[130px]">Tanggal Izin</th>
                                <th class="text-left font-medium px-3 py-2 w-14">Durasi</th>
                                <th class="text-left font-medium px-3 py-2 max-w-[140px]">Alasan</th>
                                <th class="text-left font-medium px-3 py-2">Status</th>
                                <th class="text-center font-medium px-3 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($pengajuan as $row)
                                @php
                                    $statusKey = strtolower($row->status ?? '');

                                    $tanggalIzin = $row->tanggal_mulai->format('d M Y');
                                    if ($row->tanggal_selesai && !$row->tanggal_mulai->equalTo($row->tanggal_selesai)) {
                                        $tanggalIzin .= ' - ' . $row->tanggal_selesai->format('d M Y');
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-3 py-2 text-gray-500">{{ $loop->iteration + ($pengajuan->currentPage() - 1) * $pengajuan->perPage() }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $row->tanggal_pengajuan->format('d M Y') }}</td>
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
                                    <td class="px-3 py-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusStyle[$statusKey] ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ ucfirst($statusKey ?: '-') }}
                                        </span>
                                    </td>

                                    {{-- Aksi: langsung ke halaman show --}}
                                    <td class="px-3 py-2 text-center">
                                        <a href="{{ route('admin.izin.show', $row->id) }}"
                                           class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-gray-500 hover:bg-gray-600 text-white transition">
                                            Lihat
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="px-3 py-6 text-center text-gray-400">Belum ada data pengajuan.</td>
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
                        {{ $pengajuan->withQueryString()->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>

        </main>
    @include('admin.components.footer')
    </div>

</div>

@endsection