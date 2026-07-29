<?php

// database/migrations/xxxx_xx_xx_add_unit_id_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('unit_id')
                ->nullable()
                ->after('nip')
                ->constrained('jenis_unit')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('unit_id');
        });
    }
};
