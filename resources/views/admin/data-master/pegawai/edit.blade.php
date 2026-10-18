{{-- resources/views/admin/data-master/pegawai/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Peserta')

@section('content')

<div class="flex">

    {{-- Sidebar tetap di kiri --}}
    @include('admin.components.sidebar')

    <div class="flex-1 min-w-0 flex flex-col min-h-screen">

        @include('admin.components.header', ['title' => 'Edit Peserta'])

        <main class="p-6">

            {{-- Judul & Breadcrumb --}}
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit Peserta</h2>
                    <p class="text-sm text-gray-400 mt-1">Perbarui data peserta di bawah ini</p>
                </div>
            </div>

            <form action="{{ route('admin.data-master.pegawai.update', $pegawai->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Ringkasan error validasi --}}
                @if ($errors->any())
                    <div class="mb-4 bg-rose-50 border border-rose-100 text-rose-600 text-sm rounded-xl px-4 py-3">
                        <p class="font-semibold mb-1">Data belum bisa disimpan:</p>
                        <ul class="list-disc list-inside text-xs space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @php
                    $statusPesertaVal = old('status_peserta', $pegawai->status_peserta);
                    $jenisKelaminVal  = old('jenis_kelamin', $pegawai->jenis_kelamin);
                @endphp

                {{-- Satu Card Gabungan --}}
                <div x-data="{ status: '{{ $statusPesertaVal }}' }"
                     class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">

                    {{-- Foto Profil --}}
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-gray-900 mb-4">Foto Profil</h3>

                        <div x-data="{ preview: {{ $pegawai->foto ? "'" . asset('foto-pegawai/' . $pegawai->foto) . "'" : 'null' }} }" class="flex items-center gap-5">
                            <div class="w-28 h-28 rounded-full bg-gray-100 border border-gray-200 overflow-hidden flex items-center justify-center flex-shrink-0">
                                <template x-if="!preview">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <circle cx="12" cy="8" r="4"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 20c0-4 3.5-6 8-6s8 2 8 6"/>
                                    </svg>
                                </template>
                                <template x-if="preview">
                                    <img :src="preview" class="w-full h-full object-cover">
                                </template>
                            </div>

                            <div>
                                <label class="cursor-pointer text-xs font-semibold text-sky-600 hover:text-sky-700 border border-sky-200 hover:bg-sky-50 px-3.5 py-2 rounded-lg transition inline-block">
                                    Ganti Foto
                                    <input type="file" name="foto" accept="image/*" class="hidden"
                                           @change="preview = URL.createObjectURL($event.target.files[0])">
                                </label>
                                <p class="text-[11px] text-gray-400 mt-2">Format JPG/PNG, maksimal 2MB. Kosongkan jika tidak ingin mengubah foto.</p>
                                @error('foto')
                                    <p class="text-xs text-rose-500 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100 mb-6">

                    {{-- A. Identitas Diri --}}
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-gray-900 mb-4">A. Identitas Diri</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            {{-- Nama Lengkap --}}
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Nama Lengkap</label>
                                <input type="text" name="nama" value="{{ old('nama', $pegawai->nama) }}" placeholder="Masukkan nama lengkap"
                                       class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('nama') border-rose-400 @enderror">
                                @error('nama') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Status Peserta --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Status Peserta</label>
                                <div class="relative">
                                    <select name="status_peserta" x-model="status"
                                            class="appearance-none w-full bg-white border border-gray-200 rounded-lg pl-3 pr-8 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('status_peserta') border-rose-400 @enderror">
                                        <option value="">Pilih Status</option>
                                        <option value="mahasiswa">Mahasiswa</option>
                                        <option value="siswa">Siswa</option>
                                    </select>
                                    <svg class="w-4 h-4 text-gray-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                                @error('status_peserta') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Jenis Kelamin --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Jenis Kelamin</label>
                                <div class="relative">
                                    <select name="jenis_kelamin"
                                            class="appearance-none w-full bg-white border border-gray-200 rounded-lg pl-3 pr-8 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('jenis_kelamin') border-rose-400 @enderror">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L" {{ $jenisKelaminVal === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ $jenisKelaminVal === 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    <svg class="w-4 h-4 text-gray-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                                @error('jenis_kelamin') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Email Aktif --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Email Aktif</label>
                                <input type="email" name="email" value="{{ old('email', $pegawai->email) }}" placeholder="contoh@gmail.com"
                                       class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('email') border-rose-400 @enderror">
                                @error('email') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- No. HP --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">No. HP</label>
                                <input type="tel" name="no_hp" value="{{ old('no_hp', $pegawai->no_hp) }}" placeholder="Contoh: 08123456789"
                                       class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('no_hp') border-rose-400 @enderror">
                                @error('no_hp') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Password (akun login) --}}
                            <div x-data="{ show: false }">
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Password Akun</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" name="password" placeholder="Kosongkan jika tidak ingin mengubah"
                                           class="w-full bg-white border border-gray-200 rounded-lg px-3 pr-10 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('password') border-rose-400 @enderror">
                                    <button type="button" @click="show = !show"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        <svg x-show="show" style="display:none" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.3 5.1A11 11 0 0 1 12 5c7 0 11 7 11 7a13.2 13.2 0 0 1-3.1 3.9M6.1 6.1A13.4 13.4 0 0 0 1 12s4 7 11 7a10.9 10.9 0 0 0 5-1.2"/>
                                        </svg>
                                    </button>
                                </div>
                                @error('password') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Alamat Domisili --}}
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Alamat Domisili</label>
                                <textarea name="alamat" rows="3" placeholder="Masukkan alamat domisili lengkap"
                                          class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500 resize-none @error('alamat') border-rose-400 @enderror">{{ old('alamat', $pegawai->alamat) }}</textarea>
                                @error('alamat') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                        </div>
                    </div>

                    <hr class="border-gray-100 mb-6">

                    {{-- B. Asal Pendidikan --}}
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-gray-900 mb-4">B. Asal Pendidikan</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            {{-- Asal Sekolah / Perguruan Tinggi --}}
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-500 mb-1.5"
                                       x-text="status === 'mahasiswa' ? 'Asal Perguruan Tinggi' : (status === 'siswa' ? 'Asal Sekolah' : 'Asal Sekolah / Perguruan Tinggi')"></label>
                                <input type="text" name="asal_instansi" value="{{ old('asal_instansi', $pegawai->asal_instansi) }}"
                                       :placeholder="status === 'mahasiswa' ? 'Contoh: Universitas Diponegoro' : 'Contoh: SMK Negeri 1 Semarang'"
                                       class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('asal_instansi') border-rose-400 @enderror">
                                @error('asal_instansi') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Jurusan / Program Studi --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5"
                                       x-text="status === 'mahasiswa' ? 'Program Studi' : (status === 'siswa' ? 'Jurusan' : 'Jurusan / Program Studi')"></label>
                                <input type="text" name="jurusan" value="{{ old('jurusan', $pegawai->jurusan) }}"
                                       :placeholder="status === 'mahasiswa' ? 'Contoh: Teknik Informatika' : 'Contoh: Rekayasa Perangkat Lunak'"
                                       class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('jurusan') border-rose-400 @enderror">
                                @error('jurusan') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Kelas / Semester --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5"
                                       x-text="status === 'mahasiswa' ? 'Semester' : (status === 'siswa' ? 'Kelas' : 'Kelas / Semester')"></label>
                                <input type="text" name="kelas_semester" value="{{ old('kelas_semester', $pegawai->kelas_semester) }}"
                                       :placeholder="status === 'mahasiswa' ? 'Contoh: 6' : 'Contoh: XII'"
                                       class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('kelas_semester') border-rose-400 @enderror">
                                @error('kelas_semester') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                        </div>
                    </div>

                    <hr class="border-gray-100 mb-6">

                    {{-- C. Penempatan Magang --}}
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 mb-4">C. Penempatan Magang</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            {{-- Ruangan Penempatan --}}
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Ruangan Penempatan</label>
                                <div class="relative">
                                    <select name="unit_id"
                                            class="appearance-none w-full bg-white border border-gray-200 rounded-lg pl-3 pr-8 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('unit_id') border-rose-400 @enderror">
                                        <option value="">Pilih Ruangan</option>
                                        @foreach ($units ?? [] as $unit)
                                            <option value="{{ $unit->id }}" {{ old('unit_id', $pegawai->unit_id) == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->nama_unit }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <svg class="w-4 h-4 text-gray-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                                @error('unit_id') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Tanggal Mulai --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Tanggal Mulai Magang</label>
                                <input type="date" name="tanggal_mulai_magang"
                                       value="{{ old('tanggal_mulai_magang', optional($pegawai->tanggal_mulai_magang)->format('Y-m-d')) }}"
                                       class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('tanggal_mulai_magang') border-rose-400 @enderror">
                                @error('tanggal_mulai_magang') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Tanggal Selesai --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Tanggal Selesai Magang</label>
                                <input type="date" name="tanggal_selesai_magang"
                                       value="{{ old('tanggal_selesai_magang', optional($pegawai->tanggal_selesai_magang)->format('Y-m-d')) }}"
                                       class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('tanggal_selesai_magang') border-rose-400 @enderror">
                                @error('tanggal_selesai_magang') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                        </div>
                    </div>

                </div>

                {{-- Tombol Aksi --}}
                <div class="flex items-center justify-end gap-3 mt-5">
                    <a href="{{ route('admin.data-master.pegawai') }}"
                       class="text-sm font-semibold px-5 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="text-sm font-semibold px-5 py-2.5 rounded-lg bg-sky-500 hover:bg-sky-600 text-white shadow-sm transition">
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </main>
    @include('admin.components.footer')
    </div>

</div>

@endsection