<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengumpulan_tugas', function (Blueprint $table) {
            $table->decimal('nilai', 5, 2)->nullable();
            $table->text('feedback_guru')->nullable();
            $table->boolean('revisi_aktif')->default(false);
            $table->timestamp('dinilai_pada')->nullable();
        });

        // Kompatibel Postgres: jangan pakai ->change() untuk kolom status.
        try {
            $driver = Schema::getConnection()->getDriverName();
            if ($driver === 'pgsql') {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE pengumpulan_tugas ALTER COLUMN status TYPE VARCHAR(30)");
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE pengumpulan_tugas ALTER COLUMN status SET DEFAULT 'terkirim'");
            } else {
                Schema::table('pengumpulan_tugas', function (Blueprint $table) {
                    $table->string('status', 30)->default('terkirim')->change();
                });
            }
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        Schema::table('pengumpulan_tugas', function (Blueprint $table) {
            $table->dropColumn(['nilai', 'feedback_guru', 'revisi_aktif', 'dinilai_pada']);
            // status dibiarkan VARCHAR — no-op agar kompatibel pgsql
        });
    }
};
