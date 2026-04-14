<?php

namespace Database\Seeders;

use App\Models\Kamar;
use Illuminate\Database\Seeder;

class KamarSeeder extends Seeder
{
    public function run(): void
    {
        $kamarList = [
            ['nama_kamar' => 'Kamar Abu Bakar', 'gedung' => 'Gedung A', 'kapasitas' => 8],
            ['nama_kamar' => 'Kamar Umar',      'gedung' => 'Gedung A', 'kapasitas' => 8],
            ['nama_kamar' => 'Kamar Utsman',    'gedung' => 'Gedung A', 'kapasitas' => 8],
            ['nama_kamar' => 'Kamar Ali',       'gedung' => 'Gedung B', 'kapasitas' => 10],
            ['nama_kamar' => 'Kamar Hasan',     'gedung' => 'Gedung B', 'kapasitas' => 10],
            ['nama_kamar' => 'Kamar Husain',    'gedung' => 'Gedung B', 'kapasitas' => 10],
            ['nama_kamar' => 'Kamar Salman',    'gedung' => 'Gedung C', 'kapasitas' => 6],
            ['nama_kamar' => 'Kamar Bilal',     'gedung' => 'Gedung C', 'kapasitas' => 6],
        ];

        foreach ($kamarList as $k) {
            Kamar::updateOrCreate(
                ['nama_kamar' => $k['nama_kamar']],
                array_merge($k, ['is_active' => true])
            );
        }

        $this->command->info('✓ Kamar seeded: 8 kamar');
    }
}