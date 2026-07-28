{{-- resources/views/admin/admin/manajemen.blade.php --}}
@extends('layouts.app')

@section('title', 'Manajemen Admin')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Manajemen Admin'])

        <main class="p-6">

            @php
                // Data contoh, ganti dengan data asli dari controller (mis. $admins dari paginate())
                $admins = $admins ?? collect([
                    ['no' => 1, 'nama' => 'Super Admin',       'email' => 'superadmin@kopjateng.go.id',           'role' => 'Super Admin', 'status' => true],
                    ['no' => 2, 'nama' => 'Admin Sekretariat', 'email' => 'admin.sekretariat@kopjateng.go.id',    'role' => 'Admin Unit',  'status' => true],
                    ['no' => 3, 'nama' => 'Admin Koperasi',    'email' => 'admin.koperasi@kopjateng.go.id',       'role' => 'Admin Unit',  'status' => true],
                    ['no' => 4, 'nama' => 'Admin UMKM',        'email' => 'admin.umkm@kopjateng.go.id',           'role' => 'Admin Unit',  'status' => true],
                    ['no' => 5, 'nama' => 'Admin Pengawasan',  'email' => 'admin.pengawasan@kopjateng.go.id',     'role' => 'Admin Unit',  'status' => false],
                ]);

                $totalAdmin = $totalAdmin ?? 8;
                $totalSuperAdmin = $totalSuperAdmin ?? 2;
                $totalAdminUnit = $totalAdminUnit ?? 6;

                $roleStyle = [
                    'Super Admin' => 'bg-violet-50 text-violet-600',
                    'Admin Unit'  => 'bg-sky-50 text-sky-600',
                ];
            @endphp

            {{-- Header Halaman --}}
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Manajemen Admin</h1>
                    <p class="text-sm text-gray-400 mt-0.5">Kelola akun administrator sistem absensi</p>
                </div>
                <a href="{{ route('admin.manajemen.admin.create') }}"
                class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition duration-150 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Admin
                </a>
            </div>

            {{-- Cards Ringkasan --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">

                {{-- Total Admin --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400 mb-2">Total Admin</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalAdmin }}</p>
                        <p class="text-xs text-gray-400 mt-1">Akun</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2l7 3v6c0 5-3.5 8.5-7 10-3.5-1.5-7-5-7-10V5l7-3z"/>
                        </svg>
                    </div>
                </div>

                {{-- Super Admin --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400 mb-2">Super Admin</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalSuperAdmin }}</p>
                        <p class="text-xs text-gray-400 mt-1">Akun</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-violet-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 18l-1.5-9L8 12l4-7 4 7 4.5-3L19 18H5z"/>
                        </svg>
                    </div>
                </div>

                {{-- Admin Unit --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400 mb-2">Admin Unit</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalAdminUnit }}</p>
                        <p class="text-xs text-gray-400 mt-1">Akun</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-sky-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-8a4 4 0 11-8 0 4 4 0 018 0zm6 3a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>

            </div>

            {{-- Filter Bar --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4">
                <div class="flex flex-wrap items-end justify-between gap-3">

                    <div class="flex flex-wrap items-end gap-3">

                        {{-- Cari --}}
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5 opacity-0 select-none">Cari</label>
                            <div class="relative">
                                <input type="text" placeholder="Cari nama, email, atau role..."
                                       class="w-64 border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="7"/>
                                    <path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Status</label>
                            <div class="relative">
                                <select class="appearance-none w-36 border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <option>Semua Status</option>
                                    <option>Aktif</option>
                                    <option>Nonaktif</option>
                                </select>
                                <svg class="w-4 h-4 text-gray-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Role --}}
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Role</label>
                            <div class="relative">
                                <select class="appearance-none w-36 border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <option>Semua Role</option>
                                    <option>Super Admin</option>
                                    <option>Admin Unit</option>
                                </select>
                                <svg class="w-4 h-4 text-gray-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Filter --}}
                        <button type="button" class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition duration-150 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18l-7 9v6l-4 2v-8L3 4z"/>
                            </svg>
                            Filter
                        </button>

                        {{-- Reset --}}
                        <button type="button" class="flex items-center gap-2 border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-semibold px-4 py-2 rounded-lg transition duration-150">
                            Reset
                        </button>
                    </div>

                </div>
            </div>

            {{-- Tabel --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 text-xs">
                                <th class="text-left font-medium px-3 py-2 w-8">No</th>
                                <th class="text-left font-medium px-3 py-2">Nama Admin</th>
                                <th class="text-left font-medium px-3 py-2">Email</th>
                                <th class="text-left font-medium px-3 py-2">Role</th>
                                <th class="text-left font-medium px-3 py-2">Status</th>
                                <th class="text-center font-medium px-3 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($admins as $row)
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-3 py-2 text-gray-500">{{ $row['no'] }}</td>
                                    <td class="px-3 py-2 font-medium text-gray-700">{{ $row['nama'] }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $row['email'] }}</td>
                                    <td class="px-3 py-2">
                                        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full {{ $roleStyle[$row['role']] ?? 'bg-gray-100 text-gray-500' }}">
                                            {{ $row['role'] }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2">
                                        {{-- Toggle switch status aktif / nonaktif --}}
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox"
                                                   class="sr-only peer status-toggle"
                                                   data-id="{{ $row['no'] }}"
                                                   @checked($row['status'])>
                                            <div class="w-10 h-5 bg-gray-200 rounded-full peer
                                                        peer-checked:bg-emerald-600
                                                        after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                                        after:bg-white after:rounded-full after:h-4 after:w-4
                                                        after:transition-all after:shadow-sm
                                                        peer-checked:after:translate-x-5"></div>
                                        </label>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('admin.manajemen.admin.edit') }}"
                                            title="Edit"
                                            class="w-7 h-7 flex items-center justify-center rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                            </a>
                                            <a href="#"
                                               title="Hapus"
                                               class="w-7 h-7 flex items-center justify-center rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-500 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-between px-5 py-4 border-t border-gray-100">
                    <p class="text-sm text-gray-400">Menampilkan 1 - 5 dari {{ $totalAdmin }} data</p>
                    <div class="flex items-center gap-1.5">
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 disabled:opacity-40" disabled>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-600 text-white text-sm font-semibold">1</button>
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 text-sm font-medium">2</button>
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

@push('scripts')
<script>
    // Contoh handler toggle status aktif/nonaktif -> sesuaikan dengan route update status di controller
    document.querySelectorAll('.status-toggle').forEach(function (toggle) {
        toggle.addEventListener('change', function () {
            const id = this.dataset.id;
            const isActive = this.checked;

            // TODO: ganti dengan request AJAX ke route update status, contoh:
            // fetch(`/admin/admin/${id}/status`, {
            //     method: 'PATCH',
            //     headers: {
            //         'Content-Type': 'application/json',
            //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            //     },
            //     body: JSON.stringify({ status: isActive })
            // });

            console.log(`Admin #${id} status diubah menjadi:`, isActive ? 'Aktif' : 'Nonaktif');
        });
    });
</script>
@endpush

@endsection