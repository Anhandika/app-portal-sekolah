<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin Pusat (super admin lintas sekolah). Duplikat logika migrasi
        // ensure_admin_pusat agar akun ini tetap tercipta walau migrasi
        // tersebut belum/pernah gagal jalan di database Railway.
        // Kolom opsional hanya diisi bila benar-benar ada (DB lama/parsial).
        $adminPusat = [
            'name' => 'Admin Pusat',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'aktif' => true,
        ];
        foreach (['school_id' => null, 'nik' => 'ADM001', 'no_hp' => '0811111111'] as $col => $val) {
            if (Schema::hasColumn('users', $col)) {
                $adminPusat[$col] = $val;
            }
        }
        User::updateOrCreate(['email' => 'adminpusat@pusat.com'], $adminPusat);

        User::updateOrCreate(
            ['email' => 'admin@sekolah.com'],
            [
                'name' => 'Admin Sekolah',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'aktif' => true,
            ]
        );

        $this->call([
            FeatureFlagsSeeder::class,
            PortalDemoSeeder::class,
            PortalFullSeeder::class,
            LmsSeeder::class,
            TugasTestDataSeeder::class,
            CompleteTugasSeeder::class,
            NilaiSeeder::class,
        ]);
    }
}
