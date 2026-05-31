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

        $kategoriList = [null, 'wisuda', 'zaidah', 'ujian', 'harian'];
        $targetWisudaList = ['Paket Juz 30', '5 Juz', '10 Juz', '15 Juz', '20 Juz', '30 Juz (Khatam)'];

        foreach ($santriIds as $i => $santriId) {
            // Setiap santri punya 3-6 record hafalan
            $jumlahSetoran = rand(3, 6);
            $tanggal = Carbon::now()->subDays(90);

            for ($j = 0; $j < $jumlahSetoran; $j++) {
                $surat      = $suratList[($i + $j) % count($suratList)];
                $halDari    = $surat['hal_mulai'] + ($j * 2);
                $halSampai  = $halDari + rand(1, 4);
                $nilai      = rand(60, 98) + (rand(0, 9) / 10);
                $kategori   = $kategoriList[($i + $j) % count($kategoriList)];

                $data = [
                    'santri_id'       => $santriId,
                    'ustadz_id'       => $ustadzIds[array_rand($ustadzIds)],
                    'nama_surat'      => $surat['nama'],
                    'nomor_juz'       => $surat['juz'],
                    'halaman_dari'    => $halDari,
                    'halaman_sampai'  => $halSampai,
                    'jumlah_halaman'  => $halSampai - $halDari + 1,
                    'nilai'           => $nilai,
                    'jenis'           => $j % 4 === 0 ? 'murojaah' : 'setoran_baru',
                    'kategori'        => $kategori,
                    'tanggal_setoran' => $tanggal->addDays(rand(5, 14))->toDateString(),
                    'catatan'         => $nilai >= 90 ? 'Sangat lancar' : ($nilai >= 75 ? 'Lancar' : 'Perlu diperbaiki'),
                    // Penilaian teknis
                    'salah_ringan'    => rand(0, 5),
                    'salah_berat'     => rand(0, 2),
                    'kelancaran'      => rand(3, 5),
                    'tajwid_makhraj'  => rand(3, 5),
                ];

                // Isi field dinamis sesuai kategori
                if ($kategori === 'wisuda') {
                    $data['target_wisuda']     = $targetWisudaList[array_rand($targetWisudaList)];
                    $data['sesi_wisuda']       = ['setoran_bertahap', 'tasmi'][rand(0, 1)];
                    $statusOptions             = ['lulus', 'perbaikan', 'belum_lulus'];
                    $statusW                   = $statusOptions[array_rand($statusOptions)];
                    $data['status_wisuda']     = $statusW;
                    if ($statusW === 'perbaikan') {
                        $data['catatan_perbaikan'] = 'Perlu perbaikan pada tajwid dan kelancaran.';
                    }
                } elseif ($kategori === 'zaidah') {
                    $data['zaidah_ke']         = rand(1, 10);
                    $data['keterangan_zaidah'] = 'Tambahan di luar target wisuda';
                } elseif ($kategori === 'ujian') {
                    $jenisUjianList            = ['pekanan', 'bulanan', 'tengah_semester', 'semester'];
                    $modelUjianList            = ['tasmi', 'sambung_ayat', 'acak_halaman'];
                    $data['jenis_ujian']       = $jenisUjianList[array_rand($jenisUjianList)];
                    $data['model_ujian']       = $modelUjianList[array_rand($modelUjianList)];
                    $statusU                   = ['lulus', 'remedial'][rand(0, 1)];
                    $data['status_ujian']      = $statusU;
                    if ($statusU === 'remedial') {
                        $data['jadwal_remedial'] = Carbon::now()->addDays(rand(3, 14))->toDateString();
                    }
                }

                Hafalan::create($data);
            }
        }

        $this->command->info('  ✓ Hafalan seeded (with kategori: wisuda/zaidah/ujian)');
    }
}