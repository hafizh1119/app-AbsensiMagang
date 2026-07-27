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
        Schema::create('pengajuan_izin', function (Blueprint $table) {

            $table->id();

            // Pegawai yang mengajukan
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Jenis izin
            $table->foreignId('jenis_izin_id')
                ->constrained('jenis_izin')
                ->cascadeOnDelete();

            // Pengajuan
            $table->date('tanggal_pengajuan');

            // Tanggal izin
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');

            // Lama izin
            $table->integer('durasi');

            // Alasan
            $table->text('alasan');

            // File pendukung
            $table->string('lampiran')->nullable();

            // Diisi oleh Kepala Unit/Admin
            $table->text('catatan')->nullable();

            // Approval
            $table->enum('status',[
                'menunggu',
                'disetujui',
                'ditolak'
            ])->default('menunggu');

            // Kepala Unit/Admin yang memverifikasi
            $table->foreignId('disetujui_oleh')
                ->nullable()
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->timestamp('tanggal_persetujuan')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_izin');
    }
};
