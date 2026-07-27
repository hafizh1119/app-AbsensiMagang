<header class="sticky top-0 z-40 bg-white border-b border-gray-200 shadow-sm">

    <div class="h-16 px-4 flex items-center">

        <a href="{{ url()->previous() }}"
           class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-6 h-6 text-gray-800"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2.5">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M15 19l-7-7 7-7"/>
            </svg>

        </a>

        <h1 class="ml-3 text-xl font-bold text-gray-900">
            {{ $title }}
        </h1>

    </div>

</header>