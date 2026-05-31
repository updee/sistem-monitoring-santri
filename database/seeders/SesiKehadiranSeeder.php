<?php

namespace Database\Seeders;

use App\Models\SesiKehadiran;
use Illuminate\Database\Seeder;

class SesiKehadiranSeeder extends Seeder
{
    public function run(): void
    {
        $sesiData = [
            ['nama_sesi' => 'Subuh', 'urutan' => 1],
            ['nama_sesi' => 'Pagi',  'urutan' => 2],
            ['nama_sesi' => 'Sore',  'urutan' => 3],
            ['nama_sesi' => 'Malam', 'urutan' => 4],
        ];

        foreach ($sesiData as $data) {
            SesiKehadiran::firstOrCreate(
                ['nama_sesi' => $data['nama_sesi']],
                ['urutan' => $data['urutan'], 'is_active' => true]
            );
        }
    }
}
