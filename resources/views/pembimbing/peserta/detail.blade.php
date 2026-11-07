{{-- resources/views/pembimbing/peserta/detail.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Peserta')

@section('content')
@php
    Carbon\Carbon::setLocale('id');

    $isSiswa = $peserta->status_peserta === 'siswa';

    $fmtTanggal = fn ($tgl) => $tgl ? Carbon\Carbon::parse($tgl)->translatedFormat('d F Y') : '-';

    // Icon (path SVG outline)
    $ic = [
        'user'     => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
        'badge'    => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'building' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2M5 21H3M9 7h1m4 0h1M9 11h1m4 0h1M9 15h1m4 0h1',
        'calendar' => 'M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z',
        'school'   => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.42A12 12 0 0118 17.5M12 14l-6.16-3.42A12 12 0 006 17.5M12 14v7',
        'book'     => 'M12 6.25v13m0-13C10.83 5.48 9.25 5 7.5 5S4.17 5.48 3 6.25v13C4.17 18.48 5.75 18 7.5 18s3.33.48 4.5 1.25m0-13C13.17 5.48 14.75 5 16.5 5c1.75 0 3.33.48 4.5 1.25v13C19.83 18.48 18.25 18 16.5 18c-1.75 0-3.33.48-4.5 1.25',
        'hash'     => 'M7 20l4-16m2 16l4-16M6 9h14M4 15h14',
        'mail'     => 'M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        'phone'    => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.5 4.5a1 1 0 01-.5 1.2l-2.26 1.13a11 11 0 005.52 5.52l1.13-2.26a1 1 0 011.2-.5l4.5 1.5a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C9.72 21 3 14.28 3 6V5z',
        'pin'      => 'M17.66 16.66L13.41 20.9a2 2 0 01-2.82 0l-4.25-4.24a8 8 0 1111.32 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z',
    ];

    $infoSections = [
        'Informasi Peserta' => [
            ['Nama Lengkap',       $peserta->nama,                                      $ic['user']],
            ['Status Peserta',     $peserta->status_peserta ? ucfirst($peserta->status_peserta) : '-', $ic['badge']],
            ['Ruangan Penempatan', $peserta->unit->nama_unit ?? '-',                    $ic['building']],
            ['Mulai Magang',       $fmtTanggal($peserta->tanggal_mulai_magang),         $ic['calendar']],
            ['Selesai Magang',     $fmtTanggal($peserta->tanggal_selesai_magang),       $ic['calendar']],
        ],
        'Asal Pendidikan' => [
            [$isSiswa ? 'Sekolah' : 'Perguruan Tinggi', $peserta->asal_instansi ?? '-',   $ic['school']],
            [$isSiswa ? 'Jurusan' : 'Program Studi',    $peserta->jurusan ?? '-',         $ic['book']],
            [$isSiswa ? 'Kelas'   : 'Semester',         $peserta->kelas_semester ?? '-',  $ic['hash']],
        ],
        'Data Diri' => [
            ['Jenis Kelamin',   match ($peserta->jenis_kelamin) { 'L' => 'Laki-laki', 'P' => 'Perempuan', default => '-' }, $ic['user']],
            ['Email',           $peserta->email ?? '-',   $ic['mail']],
            ['No. HP',          $peserta->no_hp ?? '-',   $ic['phone']],
            ['Alamat Domisili', $peserta->alamat ?? '-',  $ic['pin']],
        ],
    ];

    $badge = [
        'hadir'     => ['Hadir',     'bg-emerald-100 text-emerald-600'],
        'terlambat' => ['Terlambat', 'bg-amber-100 text-amber-600'],
        'izin'      => ['Izin',      'bg-sky-100 text-sky-600'],
        'cuti'      => ['Cuti',      'bg-violet-100 text-violet-600'],
        'sakit'     => ['Sakit',     'bg-rose-100 text-rose-600'],
    ];
@endphp

<div class="min-h-screen bg-gray-50 pb-24">

    {{-- Header --}}
    <div class="bg-gradient-to-br from-sky-400 via-blue-500 to-indigo-600 px-5 pt-5 pb-20 rounded-b-3xl relative overflow-hidden">
        {{-- Dekorasi lingkaran --}}
        <div class="absolute -top-8 -right-10 w-32 h-32 rounded-full bg-white/15"></div>
        <div class="absolute top-16 -right-4 w-16 h-16 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-10 -left-8 w-28 h-28 rounded-full bg-white/10"></div>

        <div class="relative flex items-center gap-3 mt-3">
            <a href="{{ route('pembimbing.peserta.index') }}"
               class="w-10 h-10 -ml-2 rounded-full flex items-center justify-center text-white hover:bg-white/15 transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-white text-lg font-bold">Detail Peserta</h1>
        </div>
    </div>

    {{-- Kartu Profil (mengambang di atas header) --}}
    <div class="mx-4 -mt-14 relative">
        <div class="bg-white rounded-2xl shadow-lg shadow-blue-900/10 border border-gray-100 p-5">
            <div class="flex items-center gap-4">
                <div class="shrink-0 w-16 h-16 rounded-full bg-sky-100 text-blue-500 flex items-center justify-center ring-4 ring-sky-50 overflow-hidden">
                    @if($peserta->foto)
                        <img src="{{ asset('foto-pegawai/'.$peserta->foto) }}" alt="Foto {{ $peserta->nama }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-9 h-9" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4.42 0-8 2.24-8 5v1h16v-1c0-2.76-3.58-5-8-5z"/>
                        </svg>
                    @endif
                </div>
                <div class="min-w-0">
                    <h2 class="text-gray-900 text-lg font-bold leading-tight truncate">{{ $peserta->nama }}</h2>
                    <p class="text-sm text-gray-500 mt-0.5 truncate">{{ $peserta->jurusan ?? '-' }}</p>
                    <p class="text-sm text-gray-500 truncate">{{ $peserta->asal_instansi ?? '-' }}</p>
                </div>
            </div>

            <div class="mt-4 flex justify-center">
                @if($sudahSelesai)
                    <span class="px-6 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-semibold">Selesai</span>
                @else
                    <span class="px-6 py-1 rounded-full bg-emerald-100 text-emerald-600 text-xs font-semibold">Aktif</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Tab --}}
    <div class="mx-4 mt-4 bg-gray-200/70 rounded-full p-1 flex items-center">
        <button type="button" id="tab-ringkasan" onclick="showTab('ringkasan')"
                class="flex-1 text-center py-2 rounded-full text-sm font-semibold transition-all duration-200 bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-sm shadow-blue-500/30">
            Ringkasan
        </button>
        <button type="button" id="tab-riwayat" onclick="showTab('riwayat')"
                class="flex-1 text-center py-2 rounded-full text-sm font-semibold transition-all duration-200 text-gray-500">
            Riwayat Presensi
        </button>
    </div>

    {{-- ================= TAB: RINGKASAN ================= --}}
    <div id="panel-ringkasan">

        {{-- Statistik --}}
        <div class="mx-4 mt-4 grid grid-cols-2 gap-3">

            {{-- Hari Hadir --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex items-center gap-3">
                <div class="shrink-0 w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-sm shadow-emerald-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-gray-800 leading-none">{{ $ringkasan['hadir'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Hari Hadir</p>
                </div>
            </div>

            {{-- Terlambat --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex items-center gap-3">
                <div class="shrink-0 w-10 h-10 rounded-full bg-amber-400 text-white flex items-center justify-center shadow-sm shadow-amber-400/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-gray-800 leading-none">{{ $ringkasan['terlambat'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Terlambat</p>
                </div>
            </div>

            {{-- Izin --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex items-center gap-3">
                <div class="shrink-0 w-10 h-10 rounded-full bg-sky-500 text-white flex items-center justify-center shadow-sm shadow-sky-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8h.01M12 12v4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-gray-800 leading-none">{{ $ringkasan['izin'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Izin</p>
                </div>
            </div>

            {{-- Sakit --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex items-center gap-3">
                <div class="shrink-0 w-10 h-10 rounded-full bg-rose-500 text-white flex items-center justify-center shadow-sm shadow-rose-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-gray-800 leading-none">{{ $ringkasan['sakit'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Sakit</p>
                </div>
            </div>

        </div>

        {{-- Lihat Riwayat Lengkap --}}
        <div class="mx-4 mt-3">
            <button type="button" onclick="showTab('riwayat')"
                    class="w-full flex items-center gap-3 bg-sky-50 border border-sky-100 rounded-2xl px-4 py-4 hover:bg-sky-100 transition-colors duration-200 group">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $ic['calendar'] }}"/>
                </svg>
                <span class="flex-1 text-left text-sm font-semibold text-gray-700">Lihat Riwayat Lengkap</span>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-500 transition-colors duration-200" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        {{-- Informasi Peserta, Asal Pendidikan, Data Diri --}}
        @foreach ($infoSections as $judul => $rows)
            <div class="bg-white mx-4 mt-4 rounded-2xl shadow-sm border border-gray-100 p-5">
                <h2 class="text-sm font-bold text-gray-700 mb-2">{{ $judul }}</h2>

                <div class="divide-y divide-gray-100">
                    @foreach ($rows as [$label, $value, $icon])
                        <div class="flex items-start gap-3 py-3">
                            <svg class="shrink-0 w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
                            </svg>
                            <span class="text-xs text-gray-500 w-32 shrink-0 pt-0.5">{{ $label }}</span>
                            <span class="flex-1 text-sm font-semibold text-gray-800 text-right break-words">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

    </div>

    {{-- ================= TAB: RIWAYAT PRESENSI ================= --}}
    <div id="panel-riwayat" class="hidden">

        {{-- Pilih Bulan --}}
        <div class="mx-4 mt-4 relative">
            <svg class="w-5 h-5 text-gray-500 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $ic['calendar'] }}"/>
            </svg>
            <select onchange="window.location = '{{ route('pembimbing.peserta.detail', $peserta->id) }}?tab=riwayat&bulan=' + this.value"
                    class="w-full appearance-none bg-white border border-gray-200 rounded-xl pl-12 pr-10 py-3 text-sm font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                @foreach ($daftarBulan as $nilai => $label)
                    <option value="{{ $nilai }}" {{ $bulanDipilih === $nilai ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <svg class="w-5 h-5 text-gray-500 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        {{-- Daftar Presensi --}}
        <div class="bg-white mx-4 mt-4 rounded-2xl shadow-sm border border-gray-100 px-5">
            <div class="divide-y divide-gray-100">
                @forelse ($riwayat as $r)
                    @php
                        $tgl    = Carbon\Carbon::parse($r->tanggal);
                        $status = strtolower($r->status);
                        [$labelStatus, $kelasStatus] = $badge[$status] ?? [ucfirst($status), 'bg-gray-100 text-gray-600'];
                    @endphp

                    <div class="flex items-center py-4">

                        {{-- Hari & tanggal --}}
                        <div class="w-24 shrink-0">
                            <p class="text-sm font-bold text-gray-900">{{ $tgl->translatedFormat('D') }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $tgl->translatedFormat('d M Y') }}</p>
                        </div>

                        {{-- Status --}}
                        <div class="flex-1 flex justify-center">
                            <span class="px-4 py-1 rounded-full text-xs font-semibold {{ $kelasStatus }}">
                                {{ $labelStatus }}
                            </span>
                        </div>

                        {{-- Jam masuk --}}
                        <div class="w-16 shrink-0 flex items-center justify-end gap-1.5 text-sm text-gray-600">
                            @if ($r->jam_masuk)
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                                </svg>
                                <span>{{ Carbon\Carbon::parse($r->jam_masuk)->format('H.i') }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </div>

                    </div>
                @empty
                    <p class="text-center text-sm text-gray-400 py-10">Belum ada data presensi di bulan ini.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>

<script>
    // Pindah tab Ringkasan <-> Riwayat Presensi (tanpa reload)
    function showTab(name, scroll = true) {
        const aktif = ['bg-gradient-to-r', 'from-sky-500', 'to-blue-600', 'text-white', 'shadow-sm', 'shadow-blue-500/30'];
        const pasif = ['text-gray-500'];

        ['ringkasan', 'riwayat'].forEach(key => {
            const btn   = document.getElementById('tab-' + key);
            const panel = document.getElementById('panel-' + key);
            const on    = key === name;

            btn.classList.remove(...aktif, ...pasif);
            btn.classList.add(...(on ? aktif : pasif));
            panel.classList.toggle('hidden', !on);
        });

        if (scroll) {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    // Tab awal sesuai query (?tab=riwayat dipakai saat ganti bulan)
    showTab('{{ $tabAktif }}', false);
</script>

@include('pembimbing.components.menu')

@endsection