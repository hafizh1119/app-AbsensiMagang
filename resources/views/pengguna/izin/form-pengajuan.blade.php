@extends('layouts.app')

@section('title', 'Form Pengajuan Izin')

@section('content')

@include('pengguna.components.header', ['title' => 'Form Pengajuan Izin'])

<div class="min-h-screen bg-gray-50 pb-28">

    {{-- Step Indicator --}}
    <div class="flex items-center justify-center gap-3 px-5 pt-6 pb-5">
        <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center text-sm font-semibold">1</div>
        <div class="w-10 border-t-2 border-dashed border-gray-300"></div>
        <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-sm font-semibold">2</div>
        <div class="w-10 border-t-2 border-dashed border-gray-300"></div>
        <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-sm font-semibold">3</div>
    </div>

    @if ($errors->any())
        <div class="mx-5 mb-4 bg-rose-50 border border-rose-200 text-rose-600 text-sm rounded-xl px-4 py-3">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pengguna.izin.review') }}" method="POST" enctype="multipart/form-data" class="px-5 space-y-5">
        @csrf

        {{-- Jenis Izin --}}
        <div>
            <label class="block text-sm font-semibold text-gray-800 mb-2">Jenis Izin</label>
            <div class="relative">
                <select name="jenis_izin_id" id="jenis_izin_id" required
                        class="w-full appearance-none bg-white border border-gray-200 rounded-2xl shadow-sm pl-4 pr-9 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Pilih Jenis Izin</option>
                    @foreach($jenisIzinList as $jenis)
                        <option value="{{ $jenis->id }}" @selected(old('jenis_izin_id', request('jenis')) == $jenis->id)>
                            {{ $jenis->nama_izin }}
                            @if($jenis->deskripsi)
                                - {{ $jenis->deskripsi }}
                            @endif
                        </option>
                    @endforeach
                </select>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </div>
            @error('jenis_izin_id')
                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tanggal Izin --}}
        <div>
            <label class="block text-sm font-semibold text-gray-800 mb-2">Tanggal Izin</label>

            <div class="bg-white rounded-2xl shadow-sm p-4 space-y-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1.5">Mulai</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="9"></circle>
                            <polyline points="12 7 12 12 15 14"></polyline>
                        </svg>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', now()->format('Y-m-d')) }}" required
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-9 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    @error('tanggal_mulai')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1.5">Sampai (Opsional)</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="9"></circle>
                            <polyline points="12 7 12 12 15 14"></polyline>
                        </svg>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}" disabled
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-9 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    @error('tanggal_selesai')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="satu_hari" checked
                           class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                    <span class="text-sm text-gray-700">Satu hari saja</span>
                </label>
            </div>
        </div>

        {{-- Ditujukan Kepada --}}
        <div>
            <label class="block text-sm font-semibold text-gray-800 mb-2">Ditujukan Kepada</label>
            <div class="relative">
                <select name="ditujukan_kepada" id="ditujukan_kepada" required
                        class="w-full appearance-none bg-white border border-gray-200 rounded-2xl shadow-sm pl-4 pr-9 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Pilih atasan / penerima izin</option>
                    @foreach($atasanList as $atasan)
                        <option value="{{ $atasan->id }}" @selected(old('ditujukan_kepada') == $atasan->id)>
                            {{ $atasan->nama }} - {{ $atasan->jabatan ?? 'Staff' }}
                        </option>
                    @endforeach
                </select>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </div>
            @error('ditujukan_kepada')
                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Keterangan --}}
        <div>
            <label class="block text-sm font-semibold text-gray-800 mb-2">Keterangan</label>
            <div class="relative">
                <textarea name="alasan" id="alasan" rows="4" maxlength="200" placeholder="Jelaskan alasan izin Anda" required
                          class="w-full bg-white border border-gray-200 rounded-2xl shadow-sm px-4 py-3 text-sm text-gray-800 placeholder-gray-400 resize-none focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ old('alasan') }}</textarea>
                <span id="counter-alasan" class="absolute bottom-3 right-4 text-xs text-gray-400">0/200</span>
            </div>
            @error('alasan')
                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Lampiran --}}
        <div>
            <label class="block text-sm font-semibold text-gray-800 mb-2">
                Lampiran <span class="font-normal text-gray-400">(Opsional)</span>
            </label>
            <label class="flex flex-col items-center justify-center gap-2 bg-white border-2 border-dashed border-gray-200 rounded-2xl py-8 px-4 cursor-pointer hover:border-emerald-300 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M12 3v12"></path>
                    <path d="M7 8l5-5 5 5"></path>
                    <path d="M5 21h14"></path>
                </svg>
                <p class="text-sm text-gray-500 text-center">Upload surat dokter atau bukti pendukung</p>
                <p class="text-xs text-gray-400">Maks. 2 MB (JPG, PNG, PDF)</p>
                <input id="lampiran-input" name="lampiran" type="file" class="hidden" accept=".jpg,.jpeg,.png,.pdf">
            </label>

            {{-- Preview file yang sudah diupload --}}
            <div id="lampiran-preview" class="hidden mt-3 flex items-center justify-between bg-white border border-gray-200 rounded-2xl px-4 py-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="shrink-0 w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <path d="M14 2v6h6"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p id="lampiran-nama" class="text-sm font-medium text-gray-800 truncate">nama_file.jpg</p>
                        <p id="lampiran-ukuran" class="text-xs text-gray-400">0 KB</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0 ml-3">
                    <a id="lampiran-lihat" href="#" target="_blank"
                       class="text-emerald-600 text-sm font-medium hover:underline">Lihat File</a>
                    <button type="button" id="lampiran-hapus" class="text-gray-400 hover:text-red-500 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
            @error('lampiran')
                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol Selanjutnya --}}
        <div class="pt-2">
            <button type="submit"
                    class="block w-full text-center bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-2xl py-3.5 shadow-sm transition">
                Selanjutnya
            </button>
        </div>

    </form>

    <script>
        (function () {
            const input   = document.getElementById('lampiran-input');
            const preview = document.getElementById('lampiran-preview');
            const nama    = document.getElementById('lampiran-nama');
            const ukuran  = document.getElementById('lampiran-ukuran');
            const lihat   = document.getElementById('lampiran-lihat');
            const hapus   = document.getElementById('lampiran-hapus');

            input.addEventListener('change', function () {
                const file = input.files[0];
                if (!file) return;

                nama.textContent = file.name;
                ukuran.textContent = (file.size / 1024).toFixed(0) + ' KB';
                lihat.href = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            });

            hapus.addEventListener('click', function () {
                input.value = '';
                preview.classList.add('hidden');
            });

            // Checkbox "Satu hari saja"
            const satuHari = document.getElementById('satu_hari');
            const mulai = document.getElementById('tanggal_mulai');
            const selesai = document.getElementById('tanggal_selesai');

            function toggleSelesai() {
                if (satuHari.checked) {
                    selesai.value = '';
                    selesai.disabled = true;
                } else {
                    selesai.disabled = false;
                }
            }
            satuHari.addEventListener('change', toggleSelesai);
            toggleSelesai();

            // Counter keterangan
            const alasan = document.getElementById('alasan');
            const counter = document.getElementById('counter-alasan');
            function updateCounter() {
                counter.textContent = alasan.value.length + '/200';
            }
            alasan.addEventListener('input', updateCounter);
            updateCounter();
        })();
    </script>

</div>
@include('pengguna.components.menu')
@endsection