{{-- resources/views/admin/lokasi-kantor/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Lokasi')

@section('content')

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar tetap di kiri, tidak ikut scroll --}}
    @include('admin.components.sidebar')

    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">

        @include('admin.components.header', ['title' => 'Tambah Lokasi'])

        <main class="p-6">

            @php
                // Nilai default titik peta (contoh: pusat Kota Semarang), ganti sesuai kebutuhan
                $default = $default ?? [
                    'lat'    => '-6.986931',
                    'lng'    => '110.420279',
                    'radius' => 100,
                ];
            @endphp

            {{-- Judul & Breadcrumb --}}
            <div class="flex items-center justify-between mb-5">
                <div>
                    <div class="flex items-center gap-1.5 text-xs text-gray-400 mb-1.5">
                        <a href="{{ route('admin.lokasi-kantor') }}" class="hover:text-sky-600">Lokasi Kantor</a>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="text-gray-500 font-medium">Tambah Lokasi</span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Tambah Lokasi</h2>
                    <p class="text-sm text-gray-400 mt-1">Tentukan titik lokasi absensi baru di peta</p>
                </div>

                <a href="{{ route('admin.lokasi-kantor') }}"
                   class="flex items-center gap-2 border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-semibold px-4 py-2.5 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>

            <form action="{{ route('admin.lokasi-kantor.store') }}" method="POST">
                @csrf

                {{-- Peta & Form Detail Lokasi --}}
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
                                           class="w-full bg-white border border-gray-200 rounded-lg pl-9 pr-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
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
                                               value="{{ old('radius', $default['radius']) }}"
                                               class="w-full mt-2 accent-sky-500">
                                        <div class="flex items-center justify-between text-xs text-gray-500 mt-1">
                                            <span>20 m</span>
                                            <span id="radiusValueLabel" class="font-semibold text-sky-600"></span>
                                            <span>1000 m</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Nilai yang dikirim ke server saat form lokasi disimpan --}}
                        <input type="hidden" id="inputLat" name="latitude" value="{{ old('latitude', $default['lat']) }}">
                        <input type="hidden" id="inputLng" name="longitude" value="{{ old('longitude', $default['lng']) }}">
                        <input type="hidden" id="inputRadius" name="radius" value="{{ old('radius', $default['radius']) }}">
                    </div>

                    {{-- Form Detail Lokasi --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h3 class="text-base font-bold text-gray-900 mb-4">Detail Lokasi</h3>

                        <div class="space-y-4 text-sm">

                            {{-- Nama Lokasi --}}
                            <div>
                                <label class="block text-xs text-gray-400 mb-1.5">Nama Lokasi <span class="text-rose-500">*</span></label>
                                <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Kantor Cabang Bidang UMKM"
                                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('nama') border-rose-400 @enderror">
                                @error('nama')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Alamat (otomatis terisi dari peta, tetap bisa diedit manual) --}}
                            <div>
                                <label class="block text-xs text-gray-400 mb-1.5">Alamat</label>
                                <textarea name="alamat" id="detailAlamat" rows="2" placeholder="Otomatis terisi saat memilih titik di peta"
                                          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500 resize-none @error('alamat') border-rose-400 @enderror">{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Latitude & Longitude (mengikuti posisi marker di peta) --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-400 mb-1.5">Latitude</label>
                                    <input type="text" id="detailLat" readonly
                                           value="{{ old('latitude', $default['lat']) }}"
                                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-500 bg-gray-50 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-400 mb-1.5">Longitude</label>
                                    <input type="text" id="detailLng" readonly
                                           value="{{ old('longitude', $default['lng']) }}"
                                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-500 bg-gray-50 focus:outline-none">
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 -mt-2.5">Geser marker atau klik di peta untuk mengubah titik koordinat.</p>

                            {{-- Radius & Status --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-400 mb-1.5">Radius</label>
                                    <div class="relative">
                                        <input type="text" id="detailRadius" readonly
                                               value="{{ old('radius', $default['radius']) }}"
                                               class="w-full border border-gray-200 rounded-lg pl-3 pr-12 py-2 text-sm text-gray-500 bg-gray-50 focus:outline-none">
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">Meter</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                {{-- Tombol Aksi --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.lokasi-kantor') }}"
                       class="flex items-center gap-2 border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-semibold px-5 py-2.5 rounded-lg">
                        Batal
                    </a>
                    <button type="submit"
                            class="flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Lokasi
                    </button>
                </div>

            </form>

        </main>
    @include('admin.components.footer')
    </div>

</div>

@endsection

{{-- ==========================================================
     INTEGRASI PETA: Leaflet.js + OpenStreetMap (gratis, tanpa API key)
     Pastikan layouts/app.blade.php punya @stack('styles') di <head>
     dan @stack('scripts') sebelum </body>.
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
        color: '#0ea5e9',
        weight: 2,
        dashArray: '6 6',
        fillColor: '#0ea5e9',
        fillOpacity: 0.12
    }).addTo(map);

    // ---- Elemen DOM terkait (input form, bukan teks statis, karena ini halaman create) ----
    var elLat        = document.getElementById('detailLat');
    var elLng        = document.getElementById('detailLng');
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
        if (elLat) elLat.value = lat.toFixed(6);
        if (elLng) elLng.value = lng.toFixed(6);
    }

    function updateRadius(radius) {
        circle.setRadius(radius);
        inputRadius.value = radius;
        if (elRadius) elRadius.value = radius;
        if (radiusLabel) radiusLabel.textContent = radius + ' m';
        if (radiusRange) radiusRange.value = radius;
    }

    // Reverse geocoding: isi otomatis kolom Alamat berdasarkan koordinat
    function reverseGeocode(lat, lng) {
        fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng)
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data && data.display_name && elAlamat) {
                    elAlamat.value = data.display_name;
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
                            if (elAlamat) elAlamat.value = item.display_name;
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