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

    Route::get('/izin', function () { return view('admin.izin.index'); })->name('izin');
    Route::get('/izin/semua', function () { return view('admin.izin.semua'); })->name('izin.semua');
    Route::get('/izin/menunggu', function () { return view('admin.izin.menunggu'); })->name('izin.menunggu');
    Route::get('/izin/riwayat', function () { return view('admin.izin.riwayat'); })->name('izin.riwayat');

    Route::get('/pegawai', function () { return view('admin.pegawai.index'); })->name('pegawai');

    Route::get('/unit', function () { return view('admin.unit.index'); })->name('unit');

    Route::get('/jadwal-kerja', function () { return view('admin.jadwal-kerja.index'); })->name('jadwal-kerja');

    Route::get('/lokasi-kantor', function () { return view('admin.lokasi-kantor.index'); })->name('lokasi-kantor');

    Route::get('/manajemen/user', function () { return view('admin.manajemen.user'); })->name('manajemen.user');
    Route::get('/manajemen/admin', function () { return view('admin.manajemen.admin'); })->name('manajemen.admin');

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