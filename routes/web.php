<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('auth.pengguna.login');});

Route::get('/login', function () { return view('auth.pengguna.login'); })->name('login');
Route::get('/admin/login', function () { return view('auth.admin.login'); })->name('admin.login');

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () { return view('admin.dashboard.index'); })->name('dashboard');
    
    Route::get('/absensi/semua', function () { return view('admin.absensi.semua'); })->name('absensi.semua');
    Route::get('/absensi/hari-ini', function () { return view('admin.absensi.hari-ini'); })->name('absensi.hari-ini');
    Route::get('/absensi/rekap', function () { return view('admin.absensi.rekap'); })->name('absensi.rekap');

    Route::get('/data-master/unit', function () { return view('admin.data-master.unit.index'); })->name('data-master.unit');
    Route::get('/data-master/unit/create', function () { return view('admin.data-master.unit.create'); })->name('data-master.unit.create');
    Route::get('/data-master/unit/edit', function () { return view('admin.data-master.unit.edit'); })->name('data-master.unit.edit');

    Route::get('/data-master/pegawai', function () { return view('admin.data-master.pegawai.index'); })->name('data-master.pegawai');
    Route::get('/data-master/pegawai/create', function () { return view('admin.data-master.pegawai.create'); })->name('data-master.pegawai.create');
    Route::get('/data-master/pegawai/edit', function () { return view('admin.data-master.pegawai.edit'); })->name('data-master.pegawai.edit');
    Route::get('/data-master/pegawai/show', function () { return view('admin.data-master.pegawai.show'); })->name('data-master.pegawai.show');

    Route::get('/data-master/jenis-izin', function () { return view('admin.data-master.jenis-izin.index'); })->name('data-master.jenis-izin');
    Route::get('/data-master/jenis-izin/create', function () { return view('admin.data-master.jenis-izin.create'); })->name('data-master.jenis-izin.create');
    Route::get('/data-master/jenis-izin/edit', function () { return view('admin.data-master.jenis-izin.edit'); })->name('data-master.jenis-izin.edit');

    Route::get('/izin', function () { return view('admin.izin.index'); })->name('izin');
    Route::get('/izin/semua', function () { return view('admin.izin.semua'); })->name('izin.semua');
    Route::get('/izin/menunggu', function () { return view('admin.izin.menunggu'); })->name('izin.menunggu');

    Route::get('/unit', function () { return view('admin.unit.index'); })->name('unit');
    Route::get('/unit/edit', function () { return view('admin.unit.edit'); })->name('unit.edit');
    Route::get('/unit/show', function () { return view('admin.unit.show'); })->name('unit.show');

    Route::get('/lokasi-kantor', function () { return view('admin.lokasi-kantor.index'); })->name('lokasi-kantor');
    Route::get('/lokasi-kantor/create', function () { return view('admin.lokasi-kantor.create'); })->name('lokasi-kantor.create');
    Route::get('/lokasi-kantor/edit', function () { return view('admin.lokasi-kantor.edit'); })->name('lokasi-kantor.edit');
    Route::get('/lokasi-kantor/show', function () { return view('admin.lokasi-kantor.show'); })->name('lokasi-kantor.show');

    Route::get('/manajemen/user', function () { return view('admin.manajemen.user.index'); })->name('manajemen.user');
    Route::get('/manajemen/user/create', function () { return view('admin.manajemen.user.create'); })->name('manajemen.user.create');
    Route::get('/manajemen/user/edit', function () { return view('admin.manajemen.user.edit'); })->name('manajemen.user.edit');

    Route::get('/manajemen/admin', function () { return view('admin.manajemen.admin.index'); })->name('manajemen.admin');
    Route::get('/manajemen/admin/create', function () { return view('admin.manajemen.admin.create'); })->name('manajemen.admin.create');
    Route::get('/manajemen/admin/edit', function () { return view('admin.manajemen.admin.edit');})->name('manajemen.admin.edit');

});

Route::prefix('pengguna')->name('pengguna.')->group(function () {

    Route::get('/home', function () { return view('pengguna.home.index'); })->name('home');

    Route::get('/absensi', function () { return view('pengguna.absensi.face-recognition'); })->name('absensi');
    Route::get('/absensi/riwayat', function () { return view('pengguna.absensi.riwayat-absensi'); })->name('riwayat');

    Route::get('/jadwal', function () { return view('pengguna.jadwal.index'); })->name('jadwal');

    Route::get('/izin', function () { return view('pengguna.izin.pengajuan'); })->name('izin');
    Route::get('/izin/form', function () { return view('pengguna.izin.form-pengajuan'); })->name('izin.form');
    Route::get('/izin/review', function () { return view('pengguna.izin.review'); })->name('izin.review');
    Route::get('/izin/akhir', function () { return view('pengguna.izin.akhir'); })->name('izin.akhir');
    Route::get('/izin/riwayat-izin', function () { return view('pengguna.izin.riwayat-izin'); })->name('izin.riwayat');
    Route::get('/izin/detail', function () { return view('pengguna.detail'); })->name('izin.detail');
    
    Route::get('/profil', function () { return view('pengguna.profil.index'); })->name('profil');

});