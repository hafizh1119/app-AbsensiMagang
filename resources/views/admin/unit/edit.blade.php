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

            @php
                $unit = $unit ?? ['nama' => 'Sekretariat'];

                $pegawai = $pegawai ?? collect([
                    ['no' => 1, 'nama' => 'Budi Santoso',   'nip' => '198701152010011001', 'jabatan' => 'Sekretaris Dinas',        'status' => 'Aktif',    'tanggal_bergabung' => '10 Januari 2020'],
                    ['no' => 2, 'nama' => 'Siti Aminah',    'nip' => '198903252015032002', 'jabatan' => 'Kepala Subbag Umum',      'status' => 'Aktif',    'tanggal_bergabung' => '15 Maret 2019'],
                    ['no' => 3, 'nama' => 'Dwi Prasetyo',   'nip' => '199205102017041003', 'jabatan' => 'Analis Tata Usaha',       'status' => 'Aktif',    'tanggal_bergabung' => '1 April 2021'],
                    ['no' => 4, 'nama' => 'Rina Wulandari', 'nip' => '199001202016042004', 'jabatan' => 'Pengelola Kepegawaian',   'status' => 'Aktif',    'tanggal_bergabung' => '5 Mei 2020'],
                    ['no' => 5, 'nama' => 'Agus Setiawan',  'nip' => '199104182018031005', 'jabatan' => 'Pengadministrasi Umum',   'status' => 'Aktif',    'tanggal_bergabung' => '2 Juli 2021'],
                    ['no' => 6, 'nama' => 'Lestari Putri',  'nip' => '199303052019032006', 'jabatan' => 'Arsiparis Pelaksana',     'status' => 'Aktif',    'tanggal_bergabung' => '20 Agustus 2022'],
                    ['no' => 7, 'nama' => 'Fajar Nugroho',  'nip' => '199406152020121007', 'jabatan' => 'Pranata Komputer',        'status' => 'Nonaktif', 'tanggal_bergabung' => '1 November 2022'],
                ]);

                $statusStyle = [
                    'Aktif'    => 'bg-emerald-50 text-emerald-600',
                    'Nonaktif' => 'bg-rose-50 text-rose-500',
                ];

                // Daftar pegawai yang belum tergabung di unit ini, buat modal "Tambah Anggota"
                $pegawaiTersedia = $pegawaiTersedia ?? collect([
                    ['nip' => '199508202021012008', 'nama' => 'Nur Hidayah',    'jabatan' => 'Staf Umum'],
                    ['nip' => '199602142021032009', 'nama' => 'Bayu Kurniawan', 'jabatan' => 'Staf Kepegawaian'],
                    ['nip' => '199709102022041010', 'nama' => 'Wahyu Ramadhan', 'jabatan' => 'Staf Arsip'],
                    ['nip' => '199801052022042011', 'nama' => 'Citra Ayu',     'jabatan' => 'Staf Umum'],
                ]);
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

                    {{-- Export --}}
                    <div class="relative">
                        <button type="button" onclick="document.getElementById('dropdownExport').classList.toggle('hidden')"
                                class="flex items-center gap-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-lg shadow-sm transition">
                            Export
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="dropdownExport" class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-100 rounded-lg shadow-lg overflow-hidden z-10">
                            <a href="#" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50">Export PDF</a>
                            <a href="#" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50">Export Excel</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="flex flex-wrap items-end gap-3 mb-4">

                {{-- Cari --}}
                <div class="flex-1 min-w-[220px]">
                    <label class="block text-xs text-gray-400 mb-1.5">&nbsp;</label>
                    <div class="relative">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="7"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="text" placeholder="Cari nama atau NIP pegawai..."
                            class="w-full bg-white border border-gray-200 rounded-lg pl-9 pr-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5">Status</label>
                    <div class="relative">
                        <select class="appearance-none w-40 bg-white border border-gray-200 rounded-lg pl-3 pr-8 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option>Semua Status</option>
                            <option>Aktif</option>
                            <option>Nonaktif</option>
                        </select>
                        <svg class="w-4 h-4 text-gray-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="flex items-center gap-2">
                    <button type="button" class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18l-7 8v6l-4 2v-8L3 4z"/>
                        </svg>
                        Filter
                    </button>
                    <button type="button" class="flex items-center gap-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
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
                            @foreach ($pegawai as $row)
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
                                        <form action="#" method="POST" class="inline-block" onsubmit="return confirm('Hapus {{ $row['nama'] }} dari unit ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-xs font-semibold text-white bg-rose-500 hover:bg-rose-600 px-3 py-1.5 rounded-lg transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-between px-5 py-4 border-t border-gray-100">
                    <p class="text-sm text-gray-400">Menampilkan 1 - {{ $pegawai->count() }} dari 15 data</p>
                    <div class="flex items-center gap-1.5">
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 disabled:opacity-40" disabled>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-600 text-white text-sm font-semibold">1</button>
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 text-sm font-medium">2</button>
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 text-sm font-medium">3</button>
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

        </main>

    </div>

</div>

{{-- Modal Tambah Anggota --}}
<div id="modalTambahAnggota" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-black/40" onclick="document.getElementById('modalTambahAnggota').classList.add('hidden')"></div>

    {{-- Konten Modal --}}
    <div class="relative bg-white w-full max-w-lg rounded-2xl shadow-xl overflow-hidden">

        {{-- Header Modal --}}
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

        {{-- Search dalam modal --}}
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

        {{-- List Pegawai (checkbox) --}}
        <div class="px-5 py-4 max-h-72 overflow-y-auto space-y-1" id="listPegawaiModal">
            @forelse ($pegawaiTersedia as $p)
                <label class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-50 cursor-pointer transition pegawai-item">
                    <input type="checkbox" value="{{ $p['nip'] }}" class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate pegawai-nama">{{ $p['nama'] }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $p['nip'] }} &middot; {{ $p['jabatan'] }}</p>
                    </div>
                </label>
            @empty
                <p class="text-sm text-gray-400 text-center py-6">Tidak ada pegawai yang tersedia.</p>
            @endforelse
        </div>

        {{-- Footer Modal --}}
        <div class="flex items-center justify-end gap-2 px-5 py-4 border-t border-gray-100 bg-gray-50">
            <button type="button" onclick="document.getElementById('modalTambahAnggota').classList.add('hidden')"
                    class="px-4 py-2.5 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-100 transition">
                Batal
            </button>
            <button type="button" onclick="simpanAnggotaTerpilih()"
                    class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg shadow-sm transition">
                Tambahkan
            </button>
        </div>
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

    function simpanAnggotaTerpilih() {
        // Ambil semua checkbox yang dicentang (contoh tanpa backend, tinggal sambungkan ke route/controller)
        const terpilih = Array.from(document.querySelectorAll('#listPegawaiModal input[type="checkbox"]:checked'))
            .map(cb => cb.value);

        if (terpilih.length === 0) {
            alert('Pilih minimal satu pegawai terlebih dahulu.');
            return;
        }

        // TODO: ganti dengan request ke backend, misal:
        // fetch('/admin/unit/{{ $unit['nama'] }}/anggota', { method: 'POST', body: JSON.stringify({ nip: terpilih }) })

        alert(terpilih.length + ' pegawai berhasil ditambahkan (contoh, belum tersambung ke backend).');
        document.getElementById('modalTambahAnggota').classList.add('hidden');
    }
</script>

@endsection