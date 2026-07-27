<nav class="fixed bottom-0 left-0 right-0 z-50">

    <div class="relative max-w-md mx-auto">

        {{-- Bottom Navigation --}}
        <div class="bg-white h-20 rounded-t-3xl border-t border-gray-200 shadow-[0_-4px_20px_rgba(0,0,0,0.08)]">

            <div class="grid grid-cols-5 h-full">

                {{-- Dashboard --}}
                <a href="{{ url('/pengguna/home') }}"
                   class="flex flex-col items-center justify-center text-xs
                   {{ request()->is('pengguna/home') ? 'text-emerald-600' : 'text-gray-500' }}">

                    <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3 10.5L12 3l9 7.5V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1z"/>
                    </svg>

                    <span class="font-semibold">Home</span>

                </a>

                {{-- Jadwal --}}
                <a href="{{ url('/pengguna/jadwal') }}"
                   class="flex flex-col items-center justify-center text-xs
                   {{ request()->is('pengguna/jadwal') ? 'text-emerald-600' : 'text-gray-500' }}">

                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <path d="M8 2v4M16 2v4M3 10h18"/>
                    </svg>

                    <span>Jadwal</span>

                </a>

                {{-- Spacer untuk tombol tengah --}}
                <div></div>

                {{-- Izin --}}
                <a href="{{ route('pengguna.izin') }}"
                class="flex flex-col items-center justify-center text-xs
                {{ request()->routeIs('pengguna.izin*') ? 'text-emerald-600' : 'text-gray-500' }}">

                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V9l-6-6H9z"/>
                        <path d="M13 3v6h6"/>
                    </svg>

                    <span>Izin</span>

                </a>

                {{-- Profil --}}
                <a href="{{ route('pengguna.profil') }}"
                   class="flex flex-col items-center justify-center text-xs
                   {{ request()->is('pengguna/profil') ? 'text-emerald-600' : 'text-gray-500' }}">

                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M5.121 17.804A9 9 0 1118.879 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>

                    <span>Profil</span>

                </a>

            </div>

        </div>

        {{-- Tombol Absensi --}}
        <a href="{{ route('pengguna.absensi') }}"
           class="absolute left-1/2 -translate-x-1/2 -top-8">

            <div class="w-18 h-18 rounded-full bg-emerald-600 shadow-xl border-[6px] border-white flex items-center justify-center hover:scale-105 transition">

                <svg class="w-8 h-8 text-white"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2.2"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 8v4l2 2"/>

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>

                </svg>

            </div>

            <p class="text-center text-[11px] font-semibold text-emerald-700 mt-1">
                Absensi
            </p>

        </a>

    </div>

</nav>