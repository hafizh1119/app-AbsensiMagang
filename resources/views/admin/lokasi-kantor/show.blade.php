{{-- resources/views/admin/lokasi/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Lokasi')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Detail Lokasi'])

        <main class="p-6">

            @php
                // Data contoh lokasi yang ditampilkan, ganti dengan data asli dari controller (route model binding)
                $lokasi = $lokasi ?? (object) [
                    'id'        => 1,
                    'nama'      => 'Kantor Dinas Koperasi Prov. Jateng',
                    'utama'     => true,
                    'alamat'    => 'Jl. Pahlawan No. 17, Mugassari, Kec. Semarang Selatan, Kota Semarang, Jawa Tengah 50243',
                    'lat'       => '-6.986931',
                    'lng'       => '110.420279',
                    'radius'    => 100,
                    'status'    => 'Aktif',
                    'deskripsi' => 'Titik lokasi utama untuk absensi pegawai kantor pusat.',
                    'jumlah_pegawai' => 156,
                ];
            @endphp

            {{-- Judul & Breadcrumb --}}
            <div class="flex items-center justify-between mb-5">
                <div>
                    <div class="flex items-center gap-1.5 text-xs text-gray-400 mb-1.5">
                        <a href="{{ route('admin.lokasi-kantor') }}" class="hover:text-emerald-600">Lokasi Kantor</a>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="text-gray-500 font-medium">Detail Lokasi</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $lokasi->nama }}</h2>
                        @if ($lokasi->utama)
                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600">Utama</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-400 mt-1">Informasi lengkap titik lokasi absensi</p>
                </div>

                <a href="{{ route('admin.lokasi-kantor') }}"
                   class="flex items-center gap-2 border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-semibold px-4 py-2.5 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>

            {{-- Peta & Detail Lokasi --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                {{-- Peta (read-only, tanpa kontrol edit) --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="relative h-[420px]">
                        <div id="map" class="absolute inset-0 z-0"></div>
                    </div>
                </div>

                {{-- Detail Lokasi --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-base font-bold text-gray-900 mb-4">Detail Lokasi</h3>

                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Nama Lokasi</p>
                            <p class="font-semibold text-gray-800">{{ $lokasi->nama }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 mb-1">Alamat</p>
                            <p class="text-gray-700">{{ $lokasi->alamat }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Latitude</p>
                                <p class="text-gray-700">{{ $lokasi->lat }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Longitude</p>
                                <p class="text-gray-700">{{ $lokasi->lng }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Radius</p>
                                <p class="text-gray-700">{{ $lokasi->radius }} Meter</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Status</p>
                                <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full
                                    {{ $lokasi->status === 'Aktif' ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $lokasi->status }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 mb-1">Jumlah Pegawai Terdaftar</p>
                            <p class="text-gray-700">{{ $lokasi->jumlah_pegawai }} Orang</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 mb-1">Deskripsi</p>
                            <p class="text-gray-700">{{ $lokasi->deskripsi }}</p>
                        </div>
                    </div>

                </div>

            </div>

        </main>

    </div>

</div>

@endsection

{{-- ==========================================================
     INTEGRASI PETA: Leaflet.js + OpenStreetMap (gratis, tanpa API key)
     Versi READ-ONLY: tanpa pencarian alamat, tanpa geser marker,
     tanpa atur radius — cuma menampilkan titik & radius lokasi.
     Pastikan layouts/app.blade.php punya @stack('styles') di <head>
     dan @stack('scripts') sebelum </body>.
     ========================================================== --}}

@push('styles')
<link rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin="">
<style>
    #map { border-radius: 0; }
    .leaflet-control-attribution { font-size: 10px; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    var lat    = {{ (float) $lokasi->lat }};
    var lng    = {{ (float) $lokasi->lng }};
    var radius = {{ (int) $lokasi->radius }};

    // ---- Inisialisasi peta (read-only: interaksi drag/scroll tetap bisa untuk melihat-lihat) ----
    var map = L.map('map', { zoomControl: false }).setView([lat, lng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    L.control.zoom({ position: 'bottomright' }).addTo(map);

    // Perbaiki path ikon marker default Leaflet
    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
    });

    // ---- Marker tetap (tidak bisa digeser, hanya menampilkan titik lokasi) ----
    L.marker([lat, lng], { draggable: false }).addTo(map)
        .bindPopup('{{ addslashes($lokasi->nama) }}')
        .openPopup();

    // ---- Lingkaran radius (statis) ----
    L.circle([lat, lng], {
        radius: radius,
        color: '#059669',
        weight: 2,
        dashArray: '6 6',
        fillColor: '#059669',
        fillOpacity: 0.12
    }).addTo(map);

    // Perbaiki ukuran tile saat kolom/peta baru pertama kali dirender di dalam flex/grid.
    function fixMapSize() { map.invalidateSize(); }

    setTimeout(fixMapSize, 100);
    setTimeout(fixMapSize, 400);
    setTimeout(fixMapSize, 1000);
    window.addEventListener('load', fixMapSize);
    window.addEventListener('resize', fixMapSize);

    var mapContainer = document.getElementById('map').parentElement;
    if (window.ResizeObserver && mapContainer) {
        new ResizeObserver(fixMapSize).observe(mapContainer);
    }
});
</script>
@endpush