<?php

namespace Database\Seeders;

use App\Models\Santri;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Kamar;
use Illuminate\Database\Seeder;

class SantriSeeder extends Seeder
{
    public function run(): void
    {
        $waliIds  = User::where('role', 'wali_santri')->pluck('id')->toArray();
        $kelasIds = Kelas::pluck('id')->toArray();
        $kamarIds = Kamar::pluck('id')->toArray();

        $santriData = [
            ['nis' => '2024001', 'nama' => 'Ahmad Zaki Mubarak',       'tl' => '2008-03-15', 'kelas' => 0, 'kamar' => 0, 'wali' => 0],
            ['nis' => '2024002', 'nama' => 'Muhammad Fajri Ramadhan',  'tl' => '2007-11-22', 'kelas' => 0, 'kamar' => 0, 'wali' => 1],
            ['nis' => '2024003', 'nama' => 'Abdullah Hakim Siregar',   'tl' => '2008-06-10', 'kelas' => 1, 'kamar' => 1, 'wali' => 2],
            ['nis' => '2024004', 'nama' => 'Yusuf Al-Amin',            'tl' => '2007-09-05', 'kelas' => 1, 'kamar' => 1, 'wali' => 3],
            ['nis' => '2024005', 'nama' => 'Ibrahim Khalil',           'tl' => '2006-01-18', 'kelas' => 2, 'kamar' => 2, 'wali' => 4],
            ['nis' => '2024006', 'nama' => 'Ismail Saleh Lubis',       'tl' => '2006-07-30', 'kelas' => 2, 'kamar' => 2, 'wali' => 5],
            ['nis' => '2024007', 'nama' => 'Umar Faruq',               'tl' => '2007-04-12', 'kelas' => 3, 'kamar' => 3, 'wali' => 6],
            ['nis' => '2024008', 'nama' => 'Bilal Hasan Pratama',      'tl' => '2006-12-25', 'kelas' => 3, 'kamar' => 3, 'wali' => 7],
            ['nis' => '2024009', 'nama' => 'Salman Al-Farisi',         'tl' => '2005-08-08', 'kelas' => 4, 'kamar' => 4, 'wali' => 8],
            ['nis' => '2024010', 'nama' => 'Hamzah Nur Ikhsan',        'tl' => '2005-02-14', 'kelas' => 4, 'kamar' => 4, 'wali' => 9],
            ['nis' => '2024011', 'nama' => 'Zaid bin Tsabit',          'tl' => '2007-05-20', 'kelas' => 0, 'kamar' => 5, 'wali' => 10],
            ['nis' => '2024012', 'nama' => 'Ali Imran Hasbullah',      'tl' => '2008-10-03', 'kelas' => 1, 'kamar' => 5, 'wali' => 11],
            ['nis' => '2024013', 'nama' => 'Hasan Bashri Sinaga',      'tl' => '2006-03-28', 'kelas' => 2, 'kamar' => 6, 'wali' => 12],
            ['nis' => '2024014', 'nama' => 'Husain Mubarrok',          'tl' => '2005-11-11', 'kelas' => 4, 'kamar' => 6, 'wali' => 13],
            ['nis' => '2024015', 'nama' => 'Khabbab bin Al-Aratt',     'tl' => '2006-06-16', 'kelas' => 3, 'kamar' => 7, 'wali' => 14],
        ];

        foreach ($santriData as $s) {
            Santri::updateOrCreate(
                ['nis' => $s['nis']],
                [
                    'nama'           => $s['nama'],
                    'jenis_kelamin'  => 'L',
                    'tanggal_lahir'  => $s['tl'],
                    'tempat_lahir'   => 'Jambi',
                    'alamat'         => 'Kab. Batanghari, Jambi',
                    'kelas_id'       => $kelasIds[$s['kelas']] ?? ($kelasIds[0] ?? null),
                    'kamar_id'       => $kamarIds[$s['kamar']] ?? ($kamarIds[0] ?? null),
                    'wali_id'        => $waliIds[$s['wali']]  ?? ($waliIds[0] ?? null),
                    'tanggal_masuk'  => '2024-07-15',
                    'status'         => 'aktif',
                ]
            );
        }

        $this->command->info('  ✓ Santri seeded: 15 santri aktif');
    }
}
