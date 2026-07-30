{{-- resources/views/pengguna/absensi/face-recognition.blade.php --}}
@extends('layouts.app')

@section('title', 'Deteksi Wajah')

@section('content')

<div class="h-screen bg-gray-50 flex flex-col overflow-hidden">

<div class="shrink-0">
@include('pengguna.components.header', ['title' => 'Absensi'])
</div>

<div class="flex-1 flex flex-col justify-center px-5 py-3 overflow-hidden min-h-0 h-[calc(100vh-4rem)]">

    {{-- Flash message --}}
    @if (session('success'))
        <div class="max-w-sm mx-auto w-full mb-2 bg-sky-50 text-blue-700 text-sm font-medium px-4 py-2.5 rounded-xl border border-sky-100">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="max-w-sm mx-auto w-full mb-2 bg-rose-50 text-rose-600 text-sm font-medium px-4 py-2.5 rounded-xl border border-rose-100">
            {{ session('error') }}
        </div>
    @endif

    {{-- Kotak Kamera --}}
    <div class="relative w-full max-w-sm mx-auto mt-2 aspect-square max-h-[42vh] rounded-3xl bg-gray-200 overflow-hidden shadow-sm">

        {{-- Video live dari kamera --}}
        <video id="camera-feed" autoplay playsinline muted class="w-full h-full object-cover -scale-x-100"></video>

        {{-- Placeholder kalau kamera / model belum siap --}}
        <div id="camera-placeholder" class="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-gray-200 text-gray-400">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <circle cx="12" cy="13" r="4"/>
            </svg>
            <span id="placeholder-text" class="text-xs">Menyiapkan model deteksi wajah...</span>
        </div>

        {{-- Overlay oval panduan wajah --}}
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <div id="face-oval" class="w-40 h-52 rounded-[50%] border-[3px] border-sky-400 transition-colors duration-300"></div>
        </div>

        {{-- Bracket sudut kamera --}}
        <svg class="absolute top-3 left-3 w-6 h-6 text-white drop-shadow" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
            <path stroke-linecap="round" d="M3 9V5a2 2 0 012-2h4"/>
        </svg>
        <svg class="absolute top-3 right-3 w-6 h-6 text-white drop-shadow" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
            <path stroke-linecap="round" d="M15 3h4a2 2 0 012 2v4"/>
        </svg>
        <svg class="absolute bottom-3 left-3 w-6 h-6 text-white drop-shadow" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
            <path stroke-linecap="round" d="M9 21H5a2 2 0 01-2-2v-4"/>
        </svg>
        <svg class="absolute bottom-3 right-3 w-6 h-6 text-white drop-shadow" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
            <path stroke-linecap="round" d="M21 15v4a2 2 0 01-2 2h-4"/>
        </svg>

        {{-- Canvas tersembunyi untuk capture foto --}}
        <canvas id="capture-canvas" class="hidden"></canvas>
    </div>

    {{-- Checklist Panduan --}}
    <div class="w-full max-w-sm mx-auto mt-3 bg-white rounded-2xl shadow-sm border border-gray-100 divide-y divide-gray-100">
        <div class="flex items-center justify-between px-4 py-2">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/>
                    <path stroke-linecap="round" d="M9 10h.01M15 10h.01M8 15c1.333-1 2.667-1.5 4-1.5s2.667.5 4 1.5"/>
                </svg>
                <span class="text-sm text-gray-600">Hadap lurus ke kamera</span>
            </div>
            <span id="check-1" class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center shrink-0 transition-colors duration-300">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </span>
        </div>
        <div class="flex items-center justify-between px-4 py-2">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm text-gray-600">Jangan memakai masker</span>
            </div>
            <span id="check-2" class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center shrink-0 transition-colors duration-300">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </span>
        </div>
        <div class="flex items-center justify-between px-4 py-2">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="text-sm text-gray-600">Pastikan pencahayaan cukup</span>
            </div>
            <span id="check-3" class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center shrink-0 transition-colors duration-300">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </span>
        </div>
        {{-- Checklist lokasi GPS --}}
        <div class="flex items-center justify-between px-4 py-2">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <circle cx="12" cy="11" r="3"/>
                </svg>
                <span class="text-sm text-gray-600">Lokasi GPS terdeteksi</span>
            </div>
            <span id="check-4" class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center shrink-0 transition-colors duration-300">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </span>
        </div>
    </div>

    {{-- Status Deteksi --}}
    <div id="status-box" class="flex items-center gap-3 bg-sky-50 border border-sky-100 rounded-2xl px-4 py-2.5 mt-3 max-w-sm mx-auto">
        <svg id="status-spinner" class="w-5 h-5 text-blue-600 animate-spin shrink-0" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>
        <span id="status-text" class="text-sm font-semibold text-blue-700">Memuat model deteksi wajah...</span>
    </div>

    {{-- Form kirim hasil capture, terkirim otomatis begitu wajah cocok & lokasi valid --}}
    <form id="absen-form" action="{{ route('pengguna.absensi.store') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="photo" id="photo-input">
        <input type="hidden" name="latitude" id="latitude-input">
        <input type="hidden" name="longitude" id="longitude-input">
    </form>
</div>
</div>

{{-- face-api.js: deteksi wajah via TensorFlow.js/WASM, jalan di semua browser modern
     (tidak tergantung FaceDetector API bawaan browser yang sudah tidak didukung luas) --}}
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

<script>
    const MODEL_URL = 'https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@master/weights';

    const video = document.getElementById('camera-feed');
    const placeholder = document.getElementById('camera-placeholder');
    const placeholderText = document.getElementById('placeholder-text');
    const canvas = document.getElementById('capture-canvas');
    const faceOval = document.getElementById('face-oval');
    const statusText = document.getElementById('status-text');
    const statusBox = document.getElementById('status-box');
    const statusSpinner = document.getElementById('status-spinner');
    const absenForm = document.getElementById('absen-form');
    const photoInput = document.getElementById('photo-input');
    const latitudeInput = document.getElementById('latitude-input');
    const longitudeInput = document.getElementById('longitude-input');
    const check1 = document.getElementById('check-1');
    const check2 = document.getElementById('check-2');
    const check3 = document.getElementById('check-3');
    const check4 = document.getElementById('check-4');

    let modelsReady = false;
    let detectLoop = null;
    let locationWatch = null;
    let stableFrames = 0;      // hitung berapa kali wajah kedeteksi berturut-turut
    const REQUIRED_STABLE_FRAMES = 6; // ~ 3 detik kalau interval 500ms, wajah harus stabil kedeteksi
    let faceReady = false;
    let coords = null;         // { lat, lng } begitu GPS berhasil didapat
    let locationFailed = false;
    let done = false;

    function markChecklist(active) {
        [check1, check2, check3].forEach(el => {
            el.classList.toggle('bg-blue-600', active);
            el.classList.toggle('bg-gray-200', !active);
        });
    }

    function markLocationChecklist(active) {
        check4.classList.toggle('bg-blue-600', active);
        check4.classList.toggle('bg-gray-200', !active);
    }

    function setStatus(text, tone) {
        statusText.textContent = text;
        statusBox.classList.remove('bg-sky-50', 'border-sky-100', 'bg-amber-50', 'border-amber-100', 'bg-red-50', 'border-red-100');
        statusText.classList.remove('text-blue-700', 'text-amber-700', 'text-red-600');

        if (tone === 'amber') {
            statusBox.classList.add('bg-amber-50', 'border-amber-100');
            statusText.classList.add('text-amber-700');
        } else if (tone === 'red') {
            statusBox.classList.add('bg-red-50', 'border-red-100');
            statusText.classList.add('text-red-600');
        } else {
            statusBox.classList.add('bg-sky-50', 'border-sky-100');
            statusText.classList.add('text-blue-700');
        }
    }

    // Ambil lokasi GPS user. Dipakai terus (watchPosition) supaya begitu user
    // mengizinkan akses lokasi meski agak telat, prosesnya tetap lanjut otomatis.
    function startLocation() {
        if (!navigator.geolocation) {
            locationFailed = true;
            markLocationChecklist(false);
            setStatus('Perangkat tidak mendukung GPS, absen tidak bisa dilanjutkan', 'red');
            return;
        }

        locationWatch = navigator.geolocation.watchPosition(
            (pos) => {
                coords = { lat: pos.coords.latitude, lng: pos.coords.longitude };
                locationFailed = false;
                markLocationChecklist(true);
                tryFinish();
            },
            () => {
                locationFailed = true;
                coords = null;
                markLocationChecklist(false);
                if (!faceReady) {
                    setStatus('Aktifkan izin lokasi untuk melanjutkan absen', 'amber');
                }
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 5000 }
        );
    }

    // Muat model face-api.js (sekali saja saat halaman dibuka)
    async function loadModels() {
        try {
            await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
            modelsReady = true;
            startCamera();
        } catch (err) {
            placeholderText.textContent = 'Gagal memuat model deteksi wajah';
            setStatus('Gagal memuat model deteksi wajah. Periksa koneksi internet Anda.', 'red');
            statusSpinner.classList.add('hidden');
        }
    }

    // Aktifkan kamera depan (selfie)
    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user' },
                audio: false
            });
            video.srcObject = stream;
            placeholder.classList.add('hidden');
            setStatus('Arahkan wajah ke kamera...');

            detectLoop = setInterval(runDetection, 500);
        } catch (err) {
            setStatus('Kamera tidak dapat diakses. Izinkan akses kamera pada browser Anda.', 'red');
            statusSpinner.classList.add('hidden');
        }
    }

    // Cek betulan ada wajah di frame video saat ini
    async function runDetection() {
        if (done || !modelsReady || video.readyState < 2) return;

        try {
            const detection = await faceapi.detectSingleFace(
                video,
                new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 })
            );

            if (detection) {
                stableFrames++;
                faceOval.classList.remove('border-sky-400');
                faceOval.classList.add('border-blue-600');
                markChecklist(true);

                if (stableFrames >= REQUIRED_STABLE_FRAMES) {
                    faceReady = true;
                    tryFinish();
                } else if (!locationFailed) {
                    setStatus(`Wajah terdeteksi (${stableFrames}/${REQUIRED_STABLE_FRAMES})...`);
                }
            } else {
                // Wajah tidak/kepotong dari frame -> reset progres
                stableFrames = 0;
                faceReady = false;
                faceOval.classList.remove('border-blue-600');
                faceOval.classList.add('border-sky-400');
                markChecklist(false);
                if (!locationFailed) {
                    setStatus('Arahkan wajah ke kamera...');
                }
            }
        } catch (e) {
            // Abaikan error deteksi sesaat, coba lagi di interval berikutnya
        }
    }

    // Baru capture & kirim kalau wajah SUDAH stabil DAN lokasi SUDAH didapat.
    function tryFinish() {
        if (done) return;

        if (faceReady && coords) {
            done = true;
            clearInterval(detectLoop);
            if (locationWatch !== null) navigator.geolocation.clearWatch(locationWatch);
            capturePhoto();
        } else if (faceReady && !coords) {
            setStatus('Wajah oke, menunggu lokasi GPS...', 'amber');
        }
    }

    function capturePhoto() {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        const dataUrl = canvas.toDataURL('image/jpeg', 0.9);

        photoInput.value = dataUrl;
        latitudeInput.value = coords.lat;
        longitudeInput.value = coords.lng;

        statusSpinner.classList.add('hidden');
        setStatus('Wajah & lokasi terverifikasi! Menyimpan absensi...');

        // Hentikan kamera setelah selesai
        if (video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
        }

        // Kirim otomatis ke server tanpa perlu konfirmasi manual
        setTimeout(() => {
            absenForm.submit();
        }, 800);
    }

    startLocation();
    loadModels();
</script>
@endsection