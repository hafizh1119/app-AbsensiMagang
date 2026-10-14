<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Absensi\LokasiKantorController;
use App\Http\Controllers\Admin\Pegawai\JenisUnitController;
use App\Http\Controllers\Admin\Pegawai\PegawaiController;
use App\Http\Controllers\Admin\Absensi\AbsensiController as AdminAbsensiController;
use App\Http\Controllers\Admin\Izin\JenisIzinController;
use App\Http\Controllers\Admin\Izin\PengajuanIzinController;
use App\Http\Controllers\Admin\JamKerja\JamKerjaController;
use App\Http\Controllers\Admin\Management\UserController;
use App\Http\Controllers\Admin\Management\AdminController;
use App\Http\Controllers\Admin\Dashboard\DashboardController as AdminDashboardController;

use App\Http\Controllers\Auth\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Auth\Pengguna\LoginController as PenggunaLoginController;

use App\Http\Controllers\Pengguna\Profile\ProfileController;
use App\Http\Controllers\Pengguna\Absensi\AbsensiController as PenggunaAbsensiController;
use App\Http\Controllers\Pengguna\Dashboard\DashboardController;
use App\Http\Controllers\Pengguna\Absensi\RiwayatAbsensiController;
use App\Http\Controllers\Pengguna\Jadwal\JadwalController;
use App\Http\Controllers\Pengguna\Izin\PengajuanIzinController as PenggunaPengajuanIzinController;
use App\Http\Controllers\Pengguna\VerifikasiIzin\VerifikasiIzinController as PenggunaVerifikasiIzinController;

Route::get('/', function () { return view('auth.pengguna.login');});

Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm']) ->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login']) ->name('admin.login.submit');
Route::post('/admin/logout', [AdminLoginController::class, 'logout']) ->name('admin.logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/absensi/hari-ini', [AdminAbsensiController::class, 'hariIni'])->name('absensi.hari-ini');
    Route::get('/absensi/semua',    [AdminAbsensiController::class, 'index'])->name('absensi.semua');
    Route::get('/absensi/rekap',    [AdminAbsensiController::class, 'rekap'])->name('absensi.rekap');
    Route::get('/absensi/export',   [AdminAbsensiController::class, 'export'])->name('absensi.export');

    Route::put('/absensi/{id}',    [AdminAbsensiController::class, 'update'])->whereNumber('id')->name('absensi.update');
    Route::delete('/absensi/{id}', [AdminAbsensiController::class, 'destroy'])->whereNumber('id')->name('absensi.destroy');

    Route::get('/data-master/unit', [JenisUnitController::class, 'index'])->name('data-master.unit');
    Route::get('/data-master/unit/create', [JenisUnitController::class, 'create'])->name('data-master.unit.create');
    Route::post('/data-master/unit', [JenisUnitController::class, 'store'])->name('data-master.unit.store');
    Route::get('/data-master/unit/{unit}/edit', [JenisUnitController::class, 'edit'])->name('data-master.unit.edit');
    Route::put('/data-master/unit/{unit}', [JenisUnitController::class, 'update'])->name('data-master.unit.update');
    Route::delete('/data-master/unit/{unit}', [JenisUnitController::class, 'destroy'])->name('data-master.unit.destroy');

    Route::get('/data-master/pegawai', [PegawaiController::class, 'index'])->name('data-master.pegawai');
    Route::get('/data-master/pegawai/create', [PegawaiController::class, 'create'])->name('data-master.pegawai.create');
    Route::post('/data-master/pegawai', [PegawaiController::class, 'store'])->name('data-master.pegawai.store');
    Route::get('/data-master/pegawai/{pegawai}', [PegawaiController::class, 'show'])->name('data-master.pegawai.show');
    Route::get('/data-master/pegawai/{pegawai}/edit', [PegawaiController::class, 'edit'])->name('data-master.pegawai.edit');
    Route::put('/data-master/pegawai/{pegawai}', [PegawaiController::class, 'update'])->name('data-master.pegawai.update');
    Route::delete('/data-master/pegawai/{pegawai}', [PegawaiController::class, 'destroy'])->name('data-master.pegawai.destroy');

    Route::get('/data-master/jenis-izin', [JenisIzinController::class, 'index'])->name('data-master.jenis-izin');
    Route::post('/data-master/jenis-izin', [JenisIzinController::class, 'store'])->name('data-master.jenis-izin.store');
    Route::put('/data-master/jenis-izin/{jenisIzin}', [JenisIzinController::class, 'update'])->name('data-master.jenis-izin.update');
    Route::delete('/data-master/jenis-izin/{jenisIzin}', [JenisIzinController::class, 'destroy'])->name('data-master.jenis-izin.destroy');

    Route::get('/data-master/jam-kerja', [JamKerjaController::class, 'index'])->name('data-master.jam-kerja');
    Route::post('/data-master/jam-kerja', [JamKerjaController::class, 'store'])->name('data-master.jam-kerja.store');
    Route::put('/data-master/jam-kerja/{jamKerja}', [JamKerjaController::class, 'update'])->name('data-master.jam-kerja.update');
    Route::delete('/data-master/jam-kerja/{jamKerja}', [JamKerjaController::class, 'destroy'])->name('data-master.jam-kerja.destroy');

    Route::get('/izin', function () { return view('admin.izin.index'); })->name('izin');
    Route::get('/izin/semua', [PengajuanIzinController::class, 'semua'])->name('izin.semua');
    Route::get('/izin/menunggu', [PengajuanIzinController::class, 'menunggu'])->name('izin.menunggu');
    Route::get('/izin/export', [PengajuanIzinController::class, 'export'])->name('izin.export');
    
    Route::get('/izin/{id}', [PengajuanIzinController::class, 'show'])->name('izin.show');
    Route::post('/izin/{id}/approve', [PengajuanIzinController::class, 'approve'])->name('izin.approve');
    Route::post('/izin/{id}/reject', [PengajuanIzinController::class, 'reject'])->name('izin.reject');


    Route::get('/lokasi-kantor', [LokasiKantorController::class, 'index'])->name('lokasi-kantor');
    Route::get('/lokasi-kantor/create', [LokasiKantorController::class, 'create'])->name('lokasi-kantor.create');
    Route::post('/lokasi-kantor', [LokasiKantorController::class, 'store'])->name('lokasi-kantor.store');
    Route::get('/lokasi-kantor/{lokasiKantor}/edit', [LokasiKantorController::class, 'edit'])->name('lokasi-kantor.edit');
    Route::put('/lokasi-kantor/{lokasiKantor}', [LokasiKantorController::class, 'update'])->name('lokasi-kantor.update');
    Route::delete('/lokasi-kantor/{lokasiKantor}', [LokasiKantorController::class, 'destroy'])->name('lokasi-kantor.destroy');
    Route::get('/lokasi-kantor/{lokasiKantor}', [LokasiKantorController::class, 'show'])->name('lokasi-kantor.show');

    Route::get('/manajemen/user', [UserController::class, 'index']) ->name('manajemen.user');
    Route::get('/manajemen/user/{id}/edit', [UserController::class, 'edit']) ->name('manajemen.user.edit');
    Route::put('/manajemen/user/{id}', [UserController::class, 'update']) ->name('manajemen.user.update');

    Route::get('/manajemen/user', [UserController::class, 'index'])->name('manajemen.user');
    Route::get('/manajemen/admin-ruangan', [UserController::class, 'adminRuangan'])->name('manajemen.admin-ruangan');
    Route::get('/manajemen/user/{id}/edit', [UserController::class, 'edit'])->name('manajemen.user.edit');
    Route::put('/manajemen/user/{id}', [UserController::class, 'update'])->name('manajemen.user.update');

    Route::get('/manajemen/admin', [AdminController::class, 'index']) ->name('manajemen.admin');
    Route::get('/manajemen/admin/create', [AdminController::class, 'create']) ->name('manajemen.admin.create');
    Route::post('/manajemen/admin', [AdminController::class, 'store']) ->name('manajemen.admin.store');
    Route::get('/manajemen/admin/{id}/edit', [AdminController::class, 'edit']) ->name('manajemen.admin.edit');
    Route::put('/manajemen/admin/{id}', [AdminController::class, 'update']) ->name('manajemen.admin.update');
    Route::delete('/manajemen/admin/{id}', [AdminController::class, 'destroy']) ->name('manajemen.admin.destroy');

});

Route::get('/login', [PenggunaLoginController::class, 'showLoginForm']) ->name('login');
Route::post('/login', [PenggunaLoginController::class, 'login']) ->name('login.submit');
Route::post('/logout', [PenggunaLoginController::class, 'logout']) ->name('logout');

Route::prefix('pengguna')->name('pengguna.')->middleware(['auth', 'role:pegawai,admin_unit'])->group(function () {

    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    Route::get('/absensi', [PenggunaAbsensiController::class, 'create'])->name('absensi');
    Route::post('/absensi', [PenggunaAbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/absensi/riwayat', [RiwayatAbsensiController::class, 'index'])->name('riwayat');

    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal');

    Route::get('/pengajuan-izin', [PenggunaPengajuanIzinController::class, 'index'])->name('izin.index');
    Route::post('/pengajuan-izin/review', [PenggunaPengajuanIzinController::class, 'review'])->name('izin.review');
    Route::post('/pengajuan-izin/simpan', [PenggunaPengajuanIzinController::class, 'store'])->name('izin.simpan');
    Route::get('/pengajuan-izin/berhasil/{pengajuan}', [PenggunaPengajuanIzinController::class, 'show'])->name('izin.akhir');
    
    Route::get('/verifikasi', [PenggunaVerifikasiIzinController::class, 'index'])->name('verifikasi.index');
    Route::get('/verifikasi/{id}', [PenggunaVerifikasiIzinController::class, 'show'])->name('verifikasi.show');
    Route::post('/verifikasi/{id}/approve', [PenggunaVerifikasiIzinController::class, 'approve'])->name('verifikasi.approve');
    Route::post('/verifikasi/{id}/reject', [PenggunaVerifikasiIzinController::class, 'reject'])->name('verifikasi.reject');

    Route::get('/pengajuan-izin/detail/{pengajuan}', [PenggunaPengajuanIzinController::class, 'detail'])->name('izin.detail');
    Route::get('/pengajuan-izin/riwayat', [PenggunaPengajuanIzinController::class, 'riwayat'])->name('izin.riwayat');

    Route::get('/profil', [ProfileController::class, 'index'])->name('profil');
    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('profil.password.update');

});