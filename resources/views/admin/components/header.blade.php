<header class="sticky top-0 z-30 bg-white border-b border-gray-200">
    <div class="flex items-stretch">

        <div class="flex-1 flex items-center justify-between px-6 py-5.5">

            {{-- Judul Halaman --}}
            <h1 class="text-lg font-bold text-gray-800">
                {{ $title ?? 'Dashboard' }}
            </h1>

            {{-- Kanan: Notifikasi & Profil Admin --}}
            <div class="flex items-center gap-5">

                {{-- Notifikasi --}}
                <button type="button" class="relative text-gray-500 hover:text-emerald-600 transition-colors duration-150">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @php($jumlahNotifikasi = $jumlahNotifikasi ?? 5)
                    @if($jumlahNotifikasi > 0)
                        <span class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1 flex items-center justify-center bg-red-500 text-white text-[10px] font-bold rounded-full">
                            {{ $jumlahNotifikasi > 9 ? '9+' : $jumlahNotifikasi }}
                        </span>
                    @endif
                </button>

                {{-- Garis pemisah --}}
                <div class="w-px h-8 bg-gray-200"></div>

                {{-- Profil Admin (dropdown) --}}
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button type="button" @click="open = !open"
                            class="flex items-center gap-2.5 hover:opacity-80 transition">

                        <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm overflow-hidden shrink-0">
                            @if(auth()->check() && auth()->user()->avatar)
                                <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="Foto Admin" class="w-full h-full object-cover">
                            @else
                                {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'A' }}
                            @endif
                        </div>

                        <div class="text-left hidden sm:block">
                            <p class="text-sm font-semibold text-gray-800 leading-tight">{{ auth()->user()->name ?? 'Admin Utama' }}</p>
                            <p class="text-xs text-gray-400 leading-tight">{{ auth()->user()->role ?? 'Administrator' }}</p>
                        </div>

                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Dropdown menu --}}
                    <div x-show="open" x-cloak x-transition
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 z-40">
                        <a href="#"
                           class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors duration-150">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profil Saya
                        </a>
                        <a href="#"
                           class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors duration-150">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Pengaturan
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form action="#" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors duration-150">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>