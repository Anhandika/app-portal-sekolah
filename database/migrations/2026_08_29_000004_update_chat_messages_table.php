<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->foreignId('chat_group_id')->nullable()->constrained('chat_groups')->cascadeOnDelete();
        });

        // Kompatibel Postgres: buat kelas_id nullable tanpa ->change().
        try {
            $driver = Schema::getConnection()->getDriverName();
            if ($driver === 'pgsql') {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE chat_messages ALTER COLUMN kelas_id DROP NOT NULL");
            } else {
                Schema::table('chat_messages', function (Blueprint $table) {
                    $table->foreignId('kelas_id')->nullable()->change(); // Buat nullable agar bisa transisi
                });
            }
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropForeign(['chat_group_id']);
            $table->dropColumn('chat_group_id');
        });
    }
};
