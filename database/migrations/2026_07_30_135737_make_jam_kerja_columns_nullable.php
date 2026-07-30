<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jam_kerja', function (Blueprint $table) {
            $table->time('jam_masuk')->nullable()->change();
            $table->time('jam_pulang')->nullable()->change();
            $table->integer('toleransi_terlambat')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('jam_kerja', function (Blueprint $table) {
            $table->time('jam_masuk')->nullable(false)->change();
            $table->time('jam_pulang')->nullable(false)->change();
            $table->integer('toleransi_terlambat')->nullable(false)->change();
        });
    }
};