{{-- resources/views/admin/unit/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Pegawai - ' . ($unit['nama'] ?? 'Unit'))

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Data Pegawai - ' . ($unit['nama'] ?? 'Unit')])

        <main class="p-6">

            @if (session('success'))
                <div class="mb-4 flex items-center gap-3 bg-emerald-50 border border-emerald-100 text-emerald-600 text-sm rounded-xl px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $statusStyle = [
                    'Aktif'    => 'bg-emerald-50 text-emerald-600',
                    'Nonaktif' => 'bg-rose-50 text-rose-500',
                ];
            @endphp

            {{-- Judul & Tombol Aksi Halaman --}}
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <h2 class="text-xl font-bold text-gray-900">Daftar Pegawai - {{ $unit['nama'] }}</h2>

                <div class="flex items-center gap-2">
                    {{-- Tambah Anggota --}}
                    <button type="button" onclick="document.getElementById('modalTambahAnggota').classList.remove('hidden')"
                            class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Tambah Anggota
                    </button>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 text-xs">
                                <th class="text-left font-medium px-4 py-3 w-10">No</th>
                                <th class="text-left font-medium px-4 py-3">Nama Pegawai</th>
                                <th class="text-left font-medium px-4 py-3">NIP</th>
                                <th class="text-left font-medium px-4 py-3">Jabatan</th>
                                <th class="text-left font-medium px-4 py-3">Status</th>
                                <th class="text-left font-medium px-4 py-3">Tanggal Bergabung</th>
                                <th class="text-center font-medium px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($pegawai as $row)
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-4 py-3 text-gray-500">{{ $row['no'] }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $row['nama'] }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $row['nip'] }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $row['jabatan'] }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full {{ $statusStyle[$row['status']] ?? 'bg-gray-100 text-gray-500' }}">
                                            {{ $row['status'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">{{ $row['tanggal_bergabung'] }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <form action="{{ route('admin.unit.anggota.destroy', $row['penempatan_id']) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('Keluarkan {{ $row['nama'] }} dari unit ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-xs font-semibold text-white bg-rose-500 hover:bg-rose-600 px-3 py-1.5 rounded-lg transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada pegawai di unit ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>

    </div>

</div>

{{-- Modal Tambah Anggota --}}
<div id="modalTambahAnggota" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-black/40" onclick="document.getElementById('modalTambahAnggota').classList.add('hidden')"></div>

    <div class="relative bg-white w-full max-w-lg rounded-2xl shadow-xl overflow-hidden">

        <form action="{{ route('admin.unit.anggota.store', $unit['id']) }}" method="POST">
            @csrf

            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-base font-bold text-gray-800">Tambah Anggota</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Pilih pegawai yang akan ditambahkan ke {{ $unit['nama'] }}</p>
                </div>
                <button type="button" onclick="document.getElementById('modalTambahAnggota').classList.add('hidden')"
                        class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            <div class="px-5 pt-4">
                <div class="relative">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="7"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" id="searchPegawaiModal" oninput="filterPegawaiModal()" placeholder="Cari nama atau NIP pegawai..."
                           class="w-full bg-gray-50 border border-gray-200 rounded-lg pl-9 pr-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div class="px-5 py-4 max-h-72 overflow-y-auto space-y-1" id="listPegawaiModal">
                @forelse ($pegawaiTersedia as $p)
                    <label class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-50 cursor-pointer transition pegawai-item">
                        <input type="checkbox" name="user_id[]" value="{{ $p->id }}" class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate pegawai-nama">{{ $p->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $p->nip ?? '-' }} &middot; {{ $p->jabatan ?? '-' }}</p>
                        </div>
                    </label>
                @empty
                    <p class="text-sm text-gray-400 text-center py-6">Tidak ada pegawai yang tersedia.</p>
                @endforelse
            </div>

            <div class="flex items-center justify-end gap-2 px-5 py-4 border-t border-gray-100 bg-gray-50">
                <button type="button" onclick="document.getElementById('modalTambahAnggota').classList.add('hidden')"
                        class="px-4 py-2.5 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit"
                        class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg shadow-sm transition">
                    Tambahkan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function filterPegawaiModal() {
        const keyword = document.getElementById('searchPegawaiModal').value.toLowerCase();
        document.querySelectorAll('#listPegawaiModal .pegawai-item').forEach(item => {
            const nama = item.textContent.toLowerCase();
            item.style.display = nama.includes(keyword) ? 'flex' : 'none';
        });
    }
</script>

@endsection