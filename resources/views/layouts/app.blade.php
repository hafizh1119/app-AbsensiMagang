<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Absensi Pegawai')</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('icon/logo.svg') }}">
    <link rel="alternate icon" href="{{ asset('icon/logo.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Chart.js: taruh di head supaya PASTI ter-load sebelum script chart di @stack('scripts') dijalankan --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>

    @stack('styles')
</head>

<body class="bg-gray-100 min-h-screen">

    @yield('content')

    {{-- Alpine.js: plugin (Collapse) WAJIB dimuat SEBELUM core Alpine --}}
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    @stack('scripts')
</body>
</html>