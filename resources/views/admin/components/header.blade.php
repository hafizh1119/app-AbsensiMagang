<header class="sticky top-0 z-30 bg-white border-b border-gray-200">
    <div class="flex items-stretch">

        <div class="flex-1 flex items-center justify-between pl-6 pr-10 py-5.5">

            {{-- Judul Halaman --}}
            <h1 class="text-lg font-bold text-gray-800">
                {{ $title ?? 'Dashboard' }}
            </h1>

            <div class="flex items-center gap-5">

                {{-- Profil Admin (statis, tanpa dropdown) --}}
                <div class="flex items-center gap-2.5">

                    <div class="w-9 h-9 rounded-full bg-sky-100 flex items-center justify-center text-sky-700 font-bold text-sm overflow-hidden shrink-0">
                        @if(auth()->check() && auth()->user()->foto)
                            <img src="{{ asset('storage/'.auth()->user()->foto) }}" alt="Foto Admin" class="w-full h-full object-cover">
                        @else
                            {{ auth()->check() ? strtoupper(substr(auth()->user()->nama, 0, 1)) : 'A' }}
                        @endif
                    </div>

                    <div class="text-left hidden sm:block">
                        <p class="text-sm font-semibold text-gray-800 leading-tight">{{ auth()->user()->nama ?? 'Admin Utama' }}</p>
                        <p class="text-xs text-gray-400 leading-tight">Super Admin</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</header>