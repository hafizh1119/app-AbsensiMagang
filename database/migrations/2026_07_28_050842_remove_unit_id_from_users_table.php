<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Hapus foreign key
            $table->dropForeign(['unit_id']);

            // Hapus kolom unit_id
            $table->dropColumn('unit_id');

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Tambahkan kembali kolom unit_id
            $table->foreignId('unit_id')
                  ->nullable()
                  ->constrained('unit')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

        });
    }
};
