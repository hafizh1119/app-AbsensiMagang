<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // A. Identitas diri
            $table->enum('status_peserta', ['mahasiswa', 'siswa'])->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();

            // B. Asal pendidikan
            $table->string('asal_instansi')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('kelas_semester', 20)->nullable();

            // C. Penempatan magang (ruangan sudah ada lewat unit_id)
            $table->date('tanggal_mulai_magang')->nullable();
            $table->date('tanggal_selesai_magang')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'status_peserta',
                'jenis_kelamin',
                'asal_instansi',
                'jurusan',
                'kelas_semester',
                'tanggal_mulai_magang',
                'tanggal_selesai_magang',
            ]);
        });
    }
};