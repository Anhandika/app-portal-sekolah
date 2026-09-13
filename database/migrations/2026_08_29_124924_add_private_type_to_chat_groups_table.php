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
        // Kompatibel Postgres: enum di pgsql = VARCHAR + CHECK, jadi
        // ->change() gagal. Ubah ke VARCHAR(20) via raw SQL.
        try {
            $driver = Schema::getConnection()->getDriverName();
            if ($driver === 'pgsql') {
                foreach (['chat_groups_type_check', 'chat_groups_type_chk'] as $c) {
                    try { \Illuminate\Support\Facades\DB::statement("ALTER TABLE chat_groups DROP CONSTRAINT IF EXISTS {$c}"); } catch (\Throwable $e) {}
                }
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE chat_groups ALTER COLUMN type TYPE VARCHAR(20)");
            } else {
                Schema::table('chat_groups', function (Blueprint $table) {
                    // Adding 'private' to the enum requires raw SQL in some DB engines or
                    // a complete redefinition if using change().
                    $table->enum('type', ['school', 'class', 'eskul', 'private'])->change();
                });
            }
        } catch (\Throwable $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // no-op agar kompatibel pgsql
    }
};
