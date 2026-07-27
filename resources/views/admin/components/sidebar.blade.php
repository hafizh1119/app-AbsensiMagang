<aside class="w-64 min-h-screen bg-white border-r border-gray-100 flex flex-col">

    {{-- Header / Brand --}}
    <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 px-4 py-4">
        <div class="flex items-center gap-3">
            {{-- Logo polos, tanpa lingkaran/background di belakangnya --}}
            <img src="{{ asset('icon/logo.svg') }}" alt="Logo" class="shrink-0 w-10 h-10 object-contain">
            <div class="min-w-0">
                <p class="text-white font-bold text-sm leading-tight">ABSENSI PEGAWAI</p>
                <p class="text-white/80 text-[11px] leading-snug">Dinas Koperasi UKM</p>
                <p class="text-white/80 text-[11px] leading-snug">Provinsi Jawa Tengah</p>
            </div>
        </div>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
           {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Dashboard</span>
        </a>

        {{-- Data Absensi --}}
        <div x-data="{ open: {{ request()->routeIs('admin.absensi.*') ? 'true' : 'false' }} }">
            <button type="button" @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
                    {{ request()->routeIs('admin.absensi.*') ? 'text-emerald-700' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span>Data Absensi</span>
                </span>
                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open" x-collapse class="mt-1 ml-11 space-y-1">
                <a href="{{ route('admin.absensi.semua') }}"
                   class="block px-3 py-2 rounded-lg text-sm transition-colors duration-150
                   {{ request()->routeIs('admin.absensi.semua') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-500 hover:bg-emerald-50 hover:text-emerald-700' }}">Semua Absensi</a>
                <a href="{{ route('admin.absensi.hari-ini') }}"
                   class="block px-3 py-2 rounded-lg text-sm transition-colors duration-150
                   {{ request()->routeIs('admin.absensi.hari-ini') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-500 hover:bg-emerald-50 hover:text-emerald-700' }}">Absensi Hari Ini</a>
                <a href="{{ route('admin.absensi.rekap') }}"
                   class="block px-3 py-2 rounded-lg text-sm transition-colors duration-150
                   {{ request()->routeIs('admin.absensi.rekap') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-500 hover:bg-emerald-50 hover:text-emerald-700' }}">Rekap Absensi</a>
            </div>
        </div>

        {{-- Pengajuan Izin --}}
        <div x-data="{ open: {{ request()->routeIs('admin.izin.*') ? 'true' : 'false' }} }">
            <button type="button" @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
                    {{ request()->routeIs('admin.izin.*') ? 'text-emerald-700' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Pengajuan Izin</span>
                </span>
                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open" x-collapse class="mt-1 ml-11 space-y-1">
                <a href="{{ route('admin.izin.semua') }}"
                   class="block px-3 py-2 rounded-lg text-sm transition-colors duration-150
                   {{ request()->routeIs('admin.izin.semua') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-500 hover:bg-emerald-50 hover:text-emerald-700' }}">Semua Pengajuan</a>
                <a href="{{ route('admin.izin.menunggu') }}"
                   class="block px-3 py-2 rounded-lg text-sm transition-colors duration-150
                   {{ request()->routeIs('admin.izin.menunggu') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-500 hover:bg-emerald-50 hover:text-emerald-700' }}">Menunggu Persetujuan</a>
                <a href="{{ route('admin.izin.riwayat') }}"
                   class="block px-3 py-2 rounded-lg text-sm transition-colors duration-150
                   {{ request()->routeIs('admin.izin.riwayat') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-500 hover:bg-emerald-50 hover:text-emerald-700' }}">Riwayat Pengajuan</a>
            </div>
        </div>

        {{-- Data Pegawai --}}
        <a href="{{ route('admin.pegawai') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
           {{ request()->routeIs('admin.pegawai') ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Data Pegawai</span>
        </a>

        {{-- Unit / Divisi --}}
        <a href="{{ route('admin.unit') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
           {{ request()->routeIs('admin.unit') ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m4 0h10M9 7h1m4 0h1M9 11h1m4 0h1m-6 4h1m4 0h1"/>
            </svg>
            <span>Unit</span>
        </a>

        {{-- Jadwal Kerja --}}
        <a href="{{ route('admin.jadwal-kerja') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
           {{ request()->routeIs('admin.jadwal-kerja') ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>Jadwal Kerja</span>
        </a>

        {{-- Lokasi Kantor --}}
        <a href="{{ route('admin.lokasi-kantor') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
           {{ request()->routeIs('admin.lokasi-kantor') ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Lokasi Kantor</span>
        </a>

        {{-- Manajemen --}}
        <div x-data="{ open: {{ request()->routeIs('admin.manajemen.*') ? 'true' : 'false' }} }">
            <button type="button" @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
                    {{ request()->routeIs('admin.manajemen.*') ? 'text-emerald-700' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Manajemen</span>
                </span>
                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open" x-collapse class="mt-1 ml-11 space-y-1">
                <a href="{{ route('admin.manajemen.user') }}"
                   class="block px-3 py-2 rounded-lg text-sm transition-colors duration-150
                   {{ request()->routeIs('admin.manajemen.user') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-500 hover:bg-emerald-50 hover:text-emerald-700' }}">Pengguna</a>
                <a href="{{ route('admin.manajemen.admin') }}"
                   class="block px-3 py-2 rounded-lg text-sm transition-colors duration-150
                   {{ request()->routeIs('admin.manajemen.admin') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-500 hover:bg-emerald-50 hover:text-emerald-700' }}">Admin</a>
            </div>
        </div>

    </nav>

    {{-- Keluar --}}
    <div class="px-3 py-4 border-t border-gray-100">
        <form method="POST" action="#">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-red-50 hover:text-red-600 transition-colors duration-150">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Keluar</span>
            </button>
        </form>
    </div>

</aside>