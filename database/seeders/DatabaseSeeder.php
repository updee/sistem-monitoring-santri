<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KelasSeeder::class,
            KamarSeeder::class,
            SantriSeeder::class,
            HafalanSeeder::class,
            KehadiranSeeder::class,
            KategoriPelanggaranSeeder::class,
            PencapaianSeeder::class,
            IzinSeeder::class,
        ]);

        $this->command->info('✅ Semua data seeder berhasil dijalankan!');
    }
}
