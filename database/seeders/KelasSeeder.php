<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $ustadzIds = User::where('role', 'ustadz')->pluck('id')->toArray();

        $kelas = [
            ['nama_kelas' => 'Halaqah Al-Fatihah',  'tingkat' => 'X',   'ustadz_id' => $ustadzIds[0] ?? null],
            ['nama_kelas' => 'Halaqah Al-Baqarah',  'tingkat' => 'X',   'ustadz_id' => $ustadzIds[1] ?? null],
            ['nama_kelas' => 'Halaqah Ali-Imran',   'tingkat' => 'XI',  'ustadz_id' => $ustadzIds[2] ?? null],
            ['nama_kelas' => 'Halaqah An-Nisa',     'tingkat' => 'XI',  'ustadz_id' => $ustadzIds[3] ?? null],
            ['nama_kelas' => 'Halaqah Al-Maidah',   'tingkat' => 'XII', 'ustadz_id' => $ustadzIds[4] ?? null],
        ];

        foreach ($kelas as $k) {
            Kelas::updateOrCreate(
                ['nama_kelas' => $k['nama_kelas']],
                array_merge($k, ['is_active' => true])
            );
        }

        $this->command->info('✓ Kelas seeded: 5 halaqah');
    }
}