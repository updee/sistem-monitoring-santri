<?php

namespace Database\Seeders;

use App\Models\Santri;
use App\Models\User;
use App\Models\Hafalan;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class HafalanSeeder extends Seeder
{
    public function run(): void
    {
        // Idempotent: jangan menumpuk data ketika db:seed dijalankan berulang
        Hafalan::query()->delete();

        $santriIds = Santri::aktif()->pluck('id')->toArray();
        $ustadzIds = User::where('role', 'ustadz')->pluck('id')->toArray();
        if (empty($santriIds) || empty($ustadzIds)) {
            $this->command->warn('  ! HafalanSeeder dilewati: data santri/ustadz belum tersedia.');
            return;
        }

        $suratList = [
            ['nama' => 'Al-Fatihah', 'juz' => 1, 'hal_mulai' => 1],
            ['nama' => 'Al-Baqarah', 'juz' => 1, 'hal_mulai' => 2],
            ['nama' => 'Ali Imran',  'juz' => 3, 'hal_mulai' => 50],
            ['nama' => 'An-Nisa',    'juz' => 4, 'hal_mulai' => 77],
            ['nama' => 'Al-Maidah',  'juz' => 6, 'hal_mulai' => 106],
            ['nama' => 'Al-An\'am',  'juz' => 7, 'hal_mulai' => 128],
            ['nama' => 'Yasin',      'juz' => 22,'hal_mulai' => 440],
            ['nama' => 'Al-Mulk',    'juz' => 29,'hal_mulai' => 562],
            ['nama' => 'Al-Kahfi',   'juz' => 15,'hal_mulai' => 293],
        ];

        foreach ($santriIds as $i => $santriId) {
            // Setiap santri punya 3-6 record hafalan
            $jumlahSetoran = rand(3, 6);
            $tanggal = Carbon::now()->subDays(90);

            for ($j = 0; $j < $jumlahSetoran; $j++) {
                $surat      = $suratList[($i + $j) % count($suratList)];
                $halDari    = $surat['hal_mulai'] + ($j * 2);
                $halSampai  = $halDari + rand(1, 4);
                $nilai      = rand(60, 98) + (rand(0, 9) / 10);

                Hafalan::create([
                    'santri_id'       => $santriId,
                    'ustadz_id'       => $ustadzIds[array_rand($ustadzIds)],
                    'nama_surat'      => $surat['nama'],
                    'nomor_juz'       => $surat['juz'],
                    'halaman_dari'    => $halDari,
                    'halaman_sampai'  => $halSampai,
                    'jumlah_halaman'  => $halSampai - $halDari + 1,
                    'nilai'           => $nilai,
                    'jenis'           => $j % 4 === 0 ? 'murojaah' : 'setoran_baru',
                    'tanggal_setoran' => $tanggal->addDays(rand(5, 14))->toDateString(),
                    'catatan'         => $nilai >= 90 ? 'Sangat lancar' : ($nilai >= 75 ? 'Lancar' : 'Perlu diperbaiki'),
                ]);
            }
        }

        $this->command->info('  ✓ Hafalan seeded');
    }
}