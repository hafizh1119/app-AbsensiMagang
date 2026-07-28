<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Absensi\LokasiKantorController;
use App\Http\Controllers\Admin\Pegawai\JenisUnitController;
use App\Http\Controllers\Admin\Pegawai\PenempatanUnitController;
use App\Http\Controllers\Admin\Pegawai\PegawaiController;
use App\Http\Controllers\Admin\Absensi\AbsensiController as AdminAbsensiController;
use App\Http\Controllers\Admin\Izin\JenisIzinController;
use App\Http\Controllers\Admin\Izin\PengajuanIzinController;

use App\Http\Controllers\Auth\Pengguna\LoginController;

use App\Http\Controllers\Pengguna\Profile\ProfileController;
use App\Http\Controllers\Pengguna\Absensi\AbsensiController as PenggunaAbsensiController;
use App\Http\Controllers\Pengguna\Dashboard\DashboardController;
use App\Http\Controllers\Pengguna\Absensi\RiwayatAbsensiController;
use App\Http\Controllers\Pengguna\Jadwal\JadwalController;
use App\Http\Controllers\Pengguna\Izin\PengajuanIzinController as PenggunaPengajuanIzinController;


Route::get('/', function () { return view('auth.pengguna.login');});

Route::get('/admin/login', function () { return view('auth.admin.login'); })->name('admin.login');

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () { return view('admin.dashboard.index'); })->name('dashboard');
    
    Route::get('/absensi/semua', [AdminAbsensiController::class, 'index'])->name('absensi.semua');
    Route::delete('/absensi/{id}', [AdminAbsensiController::class, 'destroy'])->name('absensi.destroy');
    Route::get('/absensi/{id}/edit', [AdminAbsensiController::class, 'edit'])->name('absensi.edit');
    Route::put('/absensi/{id}', [AdminAbsensiController::class, 'update'])->name('absensi.update');
    Route::get('/absensi/hari-ini', [AdminAbsensiController::class, 'hariIni'])->name('absensi.hari-ini');
    Route::get('/absensi/rekap', [AdminAbsensiController::class, 'rekap'])->name('absensi.rekap');

    Route::get('/data-master/unit', [JenisUnitController::class, 'index'])
    ->name('data-master.unit');

    Route::post('/data-master/unit', [JenisUnitController::class, 'store'])
        ->name('data-master.unit.store');

    Route::put('/data-master/unit/{unit}', [JenisUnitController::class, 'update'])
        ->name('data-master.unit.update');

    Route::delete('/data-master/unit/{unit}', [JenisUnitController::class, 'destroy'])
        ->name('data-master.unit.destroy');

    Route::get('/data-master/pegawai', [PegawaiController::class, 'index'])->name('data-master.pegawai');
    Route::get('/data-master/pegawai/create', [PegawaiController::class, 'create'])->name('data-master.pegawai.create');
    Route::post('/data-master/pegawai', [PegawaiController::class, 'store'])->name('data-master.pegawai.store');
    Route::get('/data-master/pegawai/{pegawai}', [PegawaiController::class, 'show'])->name('data-master.pegawai.show');
    Route::get('/data-master/pegawai/{pegawai}/edit', [PegawaiController::class, 'edit'])->name('data-master.pegawai.edit');
    Route::put('/data-master/pegawai/{pegawai}', [PegawaiController::class, 'update'])->name('data-master.pegawai.update');
    Route::delete('/data-master/pegawai/{pegawai}', [PegawaiController::class, 'destroy'])->name('data-master.pegawai.destroy');

    Route::get('/data-master/jenis-izin', [JenisIzinController::class, 'index'])
    ->name('data-master.jenis-izin');

    Route::post('/data-master/jenis-izin', [JenisIzinController::class, 'store'])
        ->name('data-master.jenis-izin.store');

    Route::put('/data-master/jenis-izin/{jenisIzin}', [JenisIzinController::class, 'update'])
        ->name('data-master.jenis-izin.update');

    Route::delete('/data-master/jenis-izin/{jenisIzin}', [JenisIzinController::class, 'destroy'])
        ->name('data-master.jenis-izin.destroy');

    Route::get('/izin', function () { return view('admin.izin.index'); })->name('izin');
    Route::get('/izin/semua', [PengajuanIzinController::class, 'semua']) ->name('izin.semua');
    Route::get('/izin/menunggu', [PengajuanIzinController::class, 'menunggu'])->name('izin.menunggu');
    Route::get('/izin/{id}', [PengajuanIzinController::class, 'show'])->name('izin.show');
    Route::post('/izin/{id}/approve', [PengajuanIzinController::class, 'approve'])->name('izin.approve');
    Route::post('/izin/{id}/reject', [PengajuanIzinController::class, 'reject'])->name('izin.reject');

    Route::get('/unit', [PenempatanUnitController::class, 'index'])->name('unit');
    Route::get('/unit/{unit}', [PenempatanUnitController::class, 'show'])->name('unit.show');
    Route::post('/unit/{unit}/anggota', [PenempatanUnitController::class, 'store'])->name('unit.anggota.store');
    Route::delete('/unit/anggota/{penempatan}', [PenempatanUnitController::class, 'destroy'])->name('unit.anggota.destroy');

    Route::get('/lokasi-kantor', [LokasiKantorController::class, 'index'])
        ->name('lokasi-kantor');
    Route::get('/lokasi-kantor/create', [LokasiKantorController::class, 'create'])
        ->name('lokasi-kantor.create');
    Route::post('/lokasi-kantor', [LokasiKantorController::class, 'store'])
        ->name('lokasi-kantor.store');
    Route::get('/lokasi-kantor/{lokasiKantor}/edit', [LokasiKantorController::class, 'edit'])
        ->name('lokasi-kantor.edit');
    Route::put('/lokasi-kantor/{lokasiKantor}', [LokasiKantorController::class, 'update'])
        ->name('lokasi-kantor.update');
    Route::delete('/lokasi-kantor/{lokasiKantor}', [LokasiKantorController::class, 'destroy'])
        ->name('lokasi-kantor.destroy');
    Route::get('/lokasi-kantor/{lokasiKantor}', [LokasiKantorController::class, 'show'])
        ->name('lokasi-kantor.show');

    Route::get('/manajemen/user', function () { return view('admin.manajemen.user.index'); })->name('manajemen.user');
    Route::get('/manajemen/user/create', function () { return view('admin.manajemen.user.create'); })->name('manajemen.user.create');
    Route::get('/manajemen/user/edit', function () { return view('admin.manajemen.user.edit'); })->name('manajemen.user.edit');

    Route::get('/manajemen/admin', function () { return view('admin.manajemen.admin.index'); })->name('manajemen.admin');
    Route::get('/manajemen/admin/create', function () { return view('admin.manajemen.admin.create'); })->name('manajemen.admin.create');
    Route::get('/manajemen/admin/edit', function () { return view('admin.manajemen.admin.edit');})->name('manajemen.admin.edit');

});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('pengguna')->name('pengguna.')->middleware(['auth', 'role:pegawai'])->group(function () {

    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    Route::get('/absensi', [PenggunaAbsensiController::class, 'create'])->name('absensi');
    Route::post('/absensi', [PenggunaAbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/absensi/riwayat', [RiwayatAbsensiController::class, 'index'])->name('riwayat');

    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal');

    // Izin Pengguna - menggunakan route baru
    Route::get('/pengajuan-izin', [PenggunaPengajuanIzinController::class, 'index'])->name('izin.index');
    Route::get('/pengajuan-izin/sakit', [PenggunaPengajuanIzinController::class, 'create'])->name('izin.form');
    Route::post('/pengajuan-izin/review', [PenggunaPengajuanIzinController::class, 'review'])->name('izin.review');
    Route::post('/pengajuan-izin/simpan', [PenggunaPengajuanIzinController::class, 'store'])->name('izin.simpan');
    Route::get('/pengajuan-izin/berhasil/{pengajuan}', [PenggunaPengajuanIzinController::class, 'show'])->name('izin.akhir');
    Route::get('/pengajuan-izin/riwayat', [PenggunaPengajuanIzinController::class, 'riwayat'])->name('izin.riwayat');

    Route::get('/profil', [ProfileController::class, 'index'])->name('profil');
    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('profil.password.update');

});