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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('lokasi_absensi_id')
                  ->constrained('lokasi_absensi')
                  ->cascadeOnDelete();

            // Data absensi
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();

            // Koordinat saat absensi
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Foto hasil face recognition
            $table->string('foto_masuk')->nullable();
            $table->string('foto_pulang')->nullable();

            // Status
            $table->enum('status', [
                'hadir',
                'terlambat',
                'izin',
                'sakit',
                'alpha'
            ])->default('hadir');

            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};