{{-- resources/views/admin/lokasi/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Lokasi Kantor')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Lokasi Kantor'])

        <main class="p-6">

            {{-- Judul & Tombol Tambah --}}
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Lokasi Kantor</h2>
                    <p class="text-sm text-gray-400 mt-1">Kelola titik lokasi absensi pegawai</p>
                </div>
                <a href="#"
                   class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Tambah Lokasi
                </a>
            </div>

            @php
                $lokasi = $lokasi ?? collect([
                    [
                        'no' => 1,
                        'nama' => 'Kantor Dinas Koperasi Prov. Jateng',
                        'utama' => true,
                        'alamat' => 'Jl. Pahlawan No. 17, Semarang, Jawa Tengah',
                        'radius' => '100 Meter',
                        'jumlah_pegawai' => 156,
                        'status' => 'Aktif',
                    ],
                ]);

                $detail = $detail ?? [
                    'nama' => 'Kantor Dinas Koperasi Prov. Jateng',
                    'alamat' => 'Jl. Pahlawan No. 17, Mugassari, Kec. Semarang Selatan, Kota Semarang, Jawa Tengah 50243',
                    'lat' => '-6.986931',
                    'lng' => '110.420279',
                    'radius' => '100 Meter',
                    'status' => 'Aktif',
                    'deskripsi' => 'Titik lokasi utama untuk absensi pegawai kantor pusat.',
                ];
            @endphp

            {{-- Peta & Detail Lokasi --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-5">

                {{-- Peta --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="relative h-[420px]">

                        {{-- Kontainer peta Leaflet (OpenStreetMap, gratis, tanpa API key) --}}
                        <div id="map" class="absolute inset-0 z-0"></div>

                        {{-- Kontrol atas --}}
                        <div class="absolute top-4 left-4 right-4 z-[500] flex flex-wrap items-start gap-2">
                            <div class="relative flex-1 min-w-[200px] max-w-xs">
                                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="7"/>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                </svg>
                                <input type="text" id="mapSearchInput" autocomplete="off" placeholder="Cari alamat atau tempat..."
                                       class="w-full bg-white border border-gray-200 rounded-lg pl-9 pr-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <div id="mapSearchResults"
                                     class="hidden absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-56 overflow-y-auto text-sm"></div>
                            </div>

                            <button type="button" id="btnLocateMe"
                                    class="flex items-center gap-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-medium px-3.5 py-2.5 rounded-lg shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="3"/>
                                    <path stroke-linecap="round" d="M12 2v3M12 19v3M2 12h3M19 12h3"/>
                                </svg>
                                Gunakan Lokasi Saya
                            </button>

                            <div class="relative">
                                <button type="button" id="btnSetRadius"
                                        class="flex items-center gap-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-medium px-3.5 py-2.5 rounded-lg shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="9"/>
                                        <circle cx="12" cy="12" r="1.5" fill="currentColor" stroke="none"/>
                                    </svg>
                                    Atur Radius
                                </button>
                                <div id="radiusPanel"
                                     class="hidden absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg p-4 z-[600]">
                                    <label class="text-xs font-medium text-gray-500">Radius (meter)</label>
                                    <input type="range" id="radiusRange" min="20" max="1000" step="10"
                                           value="{{ (int) preg_replace('/\D/', '', $detail['radius']) }}"
                                           class="w-full mt-2 accent-emerald-600">
                                    <div class="flex items-center justify-between text-xs text-gray-500 mt-1">
                                        <span>20 m</span>
                                        <span id="radiusValueLabel" class="font-semibold text-emerald-600"></span>
                                        <span>1000 m</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Nilai yang dikirim ke server saat form lokasi disimpan --}}
                    <input type="hidden" id="inputLat" name="latitude" value="{{ $detail['lat'] }}">
                    <input type="hidden" id="inputLng" name="longitude" value="{{ $detail['lng'] }}">
                    <input type="hidden" id="inputRadius" name="radius" value="{{ (int) preg_replace('/\D/', '', $detail['radius']) }}">
                </div>

                {{-- Detail Lokasi --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-base font-bold text-gray-900 mb-4">Detail Lokasi</h3>

                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Nama Lokasi</p>
                            <p class="font-semibold text-gray-800">{{ $detail['nama'] }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 mb-1">Alamat</p>
                            <p class="text-gray-700" id="detailAlamat">{{ $detail['alamat'] }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Latitude</p>
                                <p class="text-gray-700" id="detailLat">{{ $detail['lat'] }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Longitude</p>
                                <p class="text-gray-700" id="detailLng">{{ $detail['lng'] }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Radius</p>
                                <p class="text-gray-700"><span id="detailRadius">{{ (int) preg_replace('/\D/', '', $detail['radius']) }}</span> Meter</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Status</p>
                                <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full bg-emerald-50 text-emerald-600">
                                    {{ $detail['status'] }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 mb-1">Deskripsi</p>
                            <p class="text-gray-700">{{ $detail['deskripsi'] }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 mt-5">
                        <a href="#"
                           class="flex items-center gap-1.5 border border-emerald-200 text-emerald-600 hover:bg-emerald-50 text-sm font-semibold px-3.5 py-2 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 4H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            Edit Lokasi
                        </a>
                        <button type="button"
                                class="flex items-center gap-1.5 border border-amber-200 text-amber-500 hover:bg-amber-50 text-sm font-semibold px-3.5 py-2 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9"/>
                                <line x1="5.5" y1="18.5" x2="18.5" y2="5.5"/>
                            </svg>
                            Nonaktifkan
                        </button>
                        <form action="#" method="POST" class="inline" onsubmit="return confirm('Hapus lokasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="flex items-center gap-1.5 border border-rose-200 text-rose-500 hover:bg-rose-50 text-sm font-semibold px-3.5 py-2 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/>
                                </svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            {{-- Daftar Lokasi Kantor --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">

                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">Daftar Lokasi Kantor</h3>
                    <div class="flex items-center gap-2">
                        <button type="button"
                                class="flex items-center gap-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-semibold px-4 py-2.5 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18l-7 8v6l-4 2v-8L3 4z"/>
                            </svg>
                            Filter
                        </button>
                        <button type="button"
                                class="flex items-center gap-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-semibold px-4 py-2.5 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5M4 9a8 8 0 0 1 14.5-4.5M20 15a8 8 0 0 1-14.5 4.5"/>
                            </svg>
                            Reset
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 text-xs">
                                <th class="text-left font-medium px-4 py-3 w-10">No</th>
                                <th class="text-left font-medium px-4 py-3">Nama Lokasi</th>
                                <th class="text-left font-medium px-4 py-3">Alamat</th>
                                <th class="text-left font-medium px-4 py-3">Radius</th>
                                <th class="text-left font-medium px-4 py-3">Status</th>
                                <th class="text-left font-medium px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($lokasi as $row)
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-4 py-3 text-gray-500">{{ $row['no'] }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-gray-800">{{ $row['nama'] }}</span>
                                            @if ($row['utama'])
                                                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600">Utama</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">{{ $row['alamat'] }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $row['radius'] }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full
                                            {{ $row['status'] === 'Aktif' ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-500' }}">
                                            {{ $row['status'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-1">
                                            <a href="#"
                                               class="inline-flex items-center justify-center w-7 h-7 rounded-full text-gray-400 hover:bg-gray-100 hover:text-emerald-600 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                            </a>
                                            <a href="#"
                                               class="inline-flex items-center justify-center w-7 h-7 rounded-full text-gray-400 hover:bg-gray-100 hover:text-emerald-600 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 4H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                            </a>
                                            <form action="#" method="POST" class="inline" onsubmit="return confirm('Hapus lokasi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full text-gray-400 hover:bg-rose-50 hover:text-rose-500 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-between px-5 py-4 border-t border-gray-100">
                    <p class="text-sm text-gray-400">Menampilkan 1 - {{ $lokasi->count() }} dari {{ $lokasi->count() }} data</p>
                    <div class="flex items-center gap-1.5">
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 disabled:opacity-40" disabled>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-600 text-white text-sm font-semibold">1</button>
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100" disabled>
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

@endsection

{{-- ==========================================================
     INTEGRASI PETA: Leaflet.js + OpenStreetMap (gratis, tanpa API key)
     Pastikan layouts/app.blade.php punya @stack('styles') di <head>
     dan @stack('scripts') sebelum </body>. Lihat penjelasan di chat.
     ========================================================== --}}

@push('styles')
<link rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin="">
<style>
    /* Pastikan tile peta tampil penuh & rounded mengikuti card */
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

    // ---- Nilai awal dari server (Blade) ----
    var initialLat    = parseFloat(document.getElementById('inputLat').value) || -6.986931;
    var initialLng    = parseFloat(document.getElementById('inputLng').value) || 110.420279;
    var initialRadius = parseInt(document.getElementById('inputRadius').value, 10) || 100;

    // ---- Inisialisasi peta ----
    var map = L.map('map', { zoomControl: false }).setView([initialLat, initialLng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Kontrol zoom ditaruh di kanan bawah biar posisinya sama seperti desain
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    // Perbaiki path ikon marker default Leaflet (404 kalau dimuat lewat CDN unpkg
    // tanpa folder /images di sebelahnya, karena path default dihitung relatif
    // terhadap lokasi file JS-nya)
    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
    });

    // ---- Marker (bisa digeser untuk menentukan titik presisi) ----
    var marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);

    // ---- Lingkaran radius ----
    var circle = L.circle([initialLat, initialLng], {
        radius: initialRadius,
        color: '#059669',
        weight: 2,
        dashArray: '6 6',
        fillColor: '#059669',
        fillOpacity: 0.12
    }).addTo(map);

    // ---- Elemen DOM terkait ----
    var elLat        = document.getElementById('detailLat');
    var elLng         = document.getElementById('detailLng');
    var elRadius      = document.getElementById('detailRadius');
    var elAlamat      = document.getElementById('detailAlamat');
    var inputLat      = document.getElementById('inputLat');
    var inputLng      = document.getElementById('inputLng');
    var inputRadius   = document.getElementById('inputRadius');
    var radiusRange   = document.getElementById('radiusRange');
    var radiusLabel   = document.getElementById('radiusValueLabel');

    function updateLatLngDisplay(lat, lng) {
        inputLat.value = lat.toFixed(6);
        inputLng.value = lng.toFixed(6);
        if (elLat) elLat.textContent = lat.toFixed(6);
        if (elLng) elLng.textContent = lng.toFixed(6);
    }

    function updateRadius(radius) {
        circle.setRadius(radius);
        inputRadius.value = radius;
        if (elRadius) elRadius.textContent = radius;
        if (radiusLabel) radiusLabel.textContent = radius + ' m';
        if (radiusRange) radiusRange.value = radius;
    }

    // Reverse geocoding: isi otomatis kolom Alamat berdasarkan koordinat
    function reverseGeocode(lat, lng) {
        fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng)
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data && data.display_name && elAlamat) {
                    elAlamat.textContent = data.display_name;
                }
            })
            .catch(function () { /* diamkan jika gagal, biarkan alamat lama */ });
    }

    updateLatLngDisplay(initialLat, initialLng);
    updateRadius(initialRadius);

    // ---- Geser marker manual ----
    marker.on('dragend', function (e) {
        var pos = e.target.getLatLng();
        circle.setLatLng(pos);
        updateLatLngDisplay(pos.lat, pos.lng);
        reverseGeocode(pos.lat, pos.lng);
    });

    // Klik di peta juga memindahkan marker
    map.on('click', function (e) {
        marker.setLatLng(e.latlng);
        circle.setLatLng(e.latlng);
        updateLatLngDisplay(e.latlng.lat, e.latlng.lng);
        reverseGeocode(e.latlng.lat, e.latlng.lng);
    });

    // ---- Tombol "Gunakan Lokasi Saya" ----
    document.getElementById('btnLocateMe').addEventListener('click', function () {
        if (!navigator.geolocation) {
            alert('Browser tidak mendukung geolocation.');
            return;
        }
        navigator.geolocation.getCurrentPosition(function (pos) {
            var lat = pos.coords.latitude;
            var lng = pos.coords.longitude;
            map.setView([lat, lng], 17);
            marker.setLatLng([lat, lng]);
            circle.setLatLng([lat, lng]);
            updateLatLngDisplay(lat, lng);
            reverseGeocode(lat, lng);
        }, function () {
            alert('Gagal mengambil lokasi. Pastikan izin lokasi diaktifkan.');
        });
    });

    // ---- Panel & slider "Atur Radius" ----
    var btnSetRadius = document.getElementById('btnSetRadius');
    var radiusPanel  = document.getElementById('radiusPanel');

    btnSetRadius.addEventListener('click', function (e) {
        e.stopPropagation();
        radiusPanel.classList.toggle('hidden');
    });

    document.addEventListener('click', function (e) {
        if (!radiusPanel.contains(e.target) && e.target !== btnSetRadius) {
            radiusPanel.classList.add('hidden');
        }
    });

    radiusRange.addEventListener('input', function () {
        updateRadius(parseInt(this.value, 10));
    });

    // ---- Pencarian alamat (Nominatim geocoding) ----
    var searchInput   = document.getElementById('mapSearchInput');
    var searchResults = document.getElementById('mapSearchResults');
    var debounceTimer = null;

    searchInput.addEventListener('input', function () {
        var q = this.value.trim();
        clearTimeout(debounceTimer);

        if (q.length < 3) {
            searchResults.classList.add('hidden');
            searchResults.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(function () {
            fetch('https://nominatim.openstreetmap.org/search?format=json&limit=5&countrycodes=id&q=' + encodeURIComponent(q))
                .then(function (res) { return res.json(); })
                .then(function (results) {
                    searchResults.innerHTML = '';

                    if (!results.length) {
                        searchResults.classList.add('hidden');
                        return;
                    }

                    results.forEach(function (item) {
                        var row = document.createElement('div');
                        row.className = 'px-3 py-2 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 text-gray-700';
                        row.textContent = item.display_name;
                        row.addEventListener('click', function () {
                            var lat = parseFloat(item.lat);
                            var lng = parseFloat(item.lon);
                            map.setView([lat, lng], 17);
                            marker.setLatLng([lat, lng]);
                            circle.setLatLng([lat, lng]);
                            updateLatLngDisplay(lat, lng);
                            if (elAlamat) elAlamat.textContent = item.display_name;
                            searchInput.value = item.display_name;
                            searchResults.classList.add('hidden');
                        });
                        searchResults.appendChild(row);
                    });

                    searchResults.classList.remove('hidden');
                })
                .catch(function () { searchResults.classList.add('hidden'); });
        }, 400); // debounce 400ms sesuai batas wajar penggunaan Nominatim
    });

    // Perbaiki ukuran tile saat kolom/peta baru pertama kali dirender di dalam flex/grid.
    // Dipanggil beberapa kali + saat resize + via ResizeObserver, karena ukuran container
    // bisa berubah setelah CSS Tailwind/Vite & webfont selesai load (bukan cuma sekali di awal).
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