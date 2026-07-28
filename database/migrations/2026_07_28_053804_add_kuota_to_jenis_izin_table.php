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
            $table->unsignedInteger('kuota')
                  ->default(0)
                  ->after('deskripsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_izin', function (Blueprint $table) {
            $table->dropColumn('kuota');
        });
    }
};