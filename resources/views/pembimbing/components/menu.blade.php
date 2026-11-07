<nav class="fixed bottom-0 left-0 right-0 z-50">

    <div class="relative max-w-md mx-auto">

        {{-- Bottom Navigation --}}
        <div class="bg-white h-20 rounded-t-3xl border-t border-gray-200 shadow-[0_-4px_20px_rgba(0,0,0,0.08)]">

            <div class="grid grid-cols-4 h-full">

                {{-- Home --}}
                <a href="{{ route('pembimbing.dashboard') }}"
                   class="flex flex-col items-center justify-center text-xs transition-colors duration-200
                   {{ request()->routeIs('pembimbing.dashboard') ? 'text-blue-600' : 'text-gray-500 hover:text-sky-500' }}">

                    <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3 10.5L12 3l9 7.5V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1z"/>
                    </svg>

                    <span class="{{ request()->routeIs('pembimbing.dashboard') ? 'font-semibold' : '' }}">Home</span>

                </a>

                {{-- Peserta --}}
                <a href="{{ route('pembimbing.peserta.index') }}"
                   class="flex flex-col items-center justify-center text-xs transition-colors duration-200
                   {{ request()->routeIs('pembimbing.peserta*') ? 'text-blue-600' : 'text-gray-500 hover:text-sky-500' }}">

                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-5.13a4 4 0 11-8 0 4 4 0 018 0zm6 2a3 3 0 11-6 0 3 3 0 016 0zM5 11a3 3 0 100-6 3 3 0 000 6z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 14a5 5 0 00-5 5v1h10v-1a5 5 0 00-5-5z"/>
                    </svg>

                    <span class="{{ request()->routeIs('pembimbing.peserta*') ? 'font-semibold' : '' }}">Peserta</span>

                </a>

                {{-- Verifikasi --}}
                <a href="{{ route('pembimbing.verifikasi-izin.index') }}"
                   class="flex flex-col items-center justify-center text-xs transition-colors duration-200
                   {{ request()->routeIs('pembimbing.verifikasi-izin*') ? 'text-blue-600' : 'text-gray-500 hover:text-sky-500' }}">

                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V9l-6-6H9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 3v6h6"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l2 2 4-4"/>
                    </svg>

                    <span class="{{ request()->routeIs('pembimbing.verifikasi-izin*') ? 'font-semibold' : '' }}">Verifikasi</span>

                </a>

                {{-- Profil --}}
                <a href="{{ route('pembimbing.profil') }}"
                   class="flex flex-col items-center justify-center text-xs transition-colors duration-200
                   {{ request()->routeIs('pembimbing.profil') ? 'text-blue-600' : 'text-gray-500 hover:text-sky-500' }}">

                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M5.121 17.804A9 9 0 1118.879 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>

                    <span class="{{ request()->routeIs('pembimbing.profil') ? 'font-semibold' : '' }}">Profil</span>

                </a>

            </div>

        </div>

    </div>

</nav>