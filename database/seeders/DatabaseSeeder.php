<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin Pusat (super admin lintas sekolah). Duplikat logika migrasi
        // ensure_admin_pusat agar akun ini tetap tercipta walau migrasi
        // tersebut belum/pernah gagal jalan di database Railway.
        User::updateOrCreate(
            ['email' => 'adminpusat@pusat.com'],
            [
                'name' => 'Admin Pusat',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'aktif' => true,
                'school_id' => null,
                'nik' => 'ADM001',
                'no_hp' => '0811111111',
            ]
        );

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
