<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jenis_izin', function (Blueprint $table) {
            $table->enum('kategori', ['izin', 'sakit', 'cuti', 'izin_terlambat'])
                ->default('izin')
                ->after('nama_izin');
            // menentukan status absensi apa yang dipakai saat izin ini disetujui

            $table->boolean('wajib_absen')->default(true)->after('kategori');
            // true  = pegawai tetap wajib absen masuk/pulang (mis. Izin Terlambat)
            // false = tidak perlu absen fisik, langsung dianggap izin penuh hari (mis. Cuti, Sakit)
        });

        Schema::table('absensi', function (Blueprint $table) {
            $table->foreignId('pengajuan_izin_id')->nullable()->after('lokasi_absensi_id')
                ->constrained('pengajuan_izin')->nullOnDelete();

            // ganti enum -> string supaya tidak terikat daftar tetap dan aman dari enum lama yang tidak lengkap terlihat
            $table->string('status', 30)->default('hadir')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_izin', function (Blueprint $table) {
            //
        });
    }
};
