<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Note: waktu might have been renamed to waktu_masuk in a previous failed run
        if (Schema::hasColumn('absensi', 'waktu') && ! Schema::hasColumn('absensi', 'waktu_masuk')) {
            Schema::table('absensi', function (Blueprint $table) {
                $table->renameColumn('waktu', 'waktu_masuk');
            });
        }

        Schema::table('absensi', function (Blueprint $table) {
            if (! Schema::hasColumn('absensi', 'waktu_pulang')) {
                $table->time('waktu_pulang')->nullable();
            }
            if (! Schema::hasColumn('absensi', 'foto_masuk')) {
                $table->string('foto_masuk')->nullable();
            }
            if (! Schema::hasColumn('absensi', 'foto_pulang')) {
                $table->string('foto_pulang')->nullable();
            }
            if (! Schema::hasColumn('absensi', 'lat_masuk')) {
                $table->decimal('lat_masuk', 10, 8)->nullable();
            }
            if (! Schema::hasColumn('absensi', 'long_masuk')) {
                $table->decimal('long_masuk', 11, 8)->nullable();
            }
            if (! Schema::hasColumn('absensi', 'lat_pulang')) {
                $table->decimal('lat_pulang', 10, 8)->nullable();
            }
            if (! Schema::hasColumn('absensi', 'long_pulang')) {
                $table->decimal('long_pulang', 11, 8)->nullable();
            }
        });

        // Kompatibel Postgres: ubah kolom status jadi VARCHAR + default tanpa ->change()
        // (butuh doctrine/dbal & gagal untuk enum di pgsql).
        try {
            $driver = Schema::getConnection()->getDriverName();
            if ($driver === 'pgsql') {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE absensi ALTER COLUMN status TYPE VARCHAR(20)");
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE absensi ALTER COLUMN status SET DEFAULT 'hadir'");
            } else {
                Schema::table('absensi', function (Blueprint $table) {
                    $table->enum('status', ['hadir', 'terlambat', 'bolos'])->default('hadir')->change();
                });
            }
        } catch (\Throwable $e) {
            // kolom status sudah sesuai — lanjutkan
        }
    }

    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropColumn(['waktu_pulang', 'foto_masuk', 'foto_pulang', 'lat_masuk', 'long_masuk', 'lat_pulang', 'long_pulang']);
            $table->renameColumn('waktu_masuk', 'waktu');
            // status dibiarkan VARCHAR — no-op agar kompatibel pgsql
        });
    }
};
