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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Data akun
            $table->string('nama');
            $table->string('nip')->unique();
            $table->string('email')->unique();
            $table->string('password');

            // Hak akses
            $table->enum('role', [
                'admin',
                'admin_unit',
                'pegawai'
            ])->default('pegawai');

            // Relasi
            $table->foreignId('unit_id')
                  ->nullable()
                  ->constrained('unit')
                  ->nullOnDelete();

            // Data pegawai
            $table->string('jabatan');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');

            $table->string('kewarganegaraan')->default('Indonesia');

            $table->enum('status_kewarganegaraan', [
                'WNI',
                'WNA'
            ])->default('WNI');

            $table->string('no_hp',20)->nullable();
            $table->text('alamat')->nullable();

            $table->string('foto')->nullable();

            $table->enum('status',[
                'aktif',
                'nonaktif'
            ])->default('aktif');

            $table->timestamp('last_login')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};