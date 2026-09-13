<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kompatibel Postgres: tanpa ->change() (butuh doctrine/dbal).
        try {
            $driver = Schema::getConnection()->getDriverName();
            if ($driver === 'pgsql') {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE absensi ALTER COLUMN status TYPE VARCHAR(20)");
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE absensi ALTER COLUMN status SET DEFAULT 'hadir'");
            } else {
                Schema::table('absensi', function (Blueprint $table) {
                    $table->string('status', 20)->default('hadir')->change();
                });
            }
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        // no-op: status tetap VARCHAR agar kompatibel pgsql/mysql
    }
};
