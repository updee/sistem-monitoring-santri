<?php

namespace Database\Seeders;

use App\Models\KategoriPelanggaran;
use Illuminate\Database\Seeder;

class KategoriPelanggaranSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            ['nama_kategori' => 'Terlambat ke masjid',           'tingkat' => 'ringan', 'poin_default' => 5],
            ['nama_kategori' => 'Tidak memakai seragam lengkap', 'tingkat' => 'ringan', 'poin_default' => 5],
            ['nama_kategori' => 'Tidak membawa kitab/Al-Quran',  'tingkat' => 'ringan', 'poin_default' => 5],
            ['nama_kategori' => 'Buang sampah sembarangan',      'tingkat' => 'ringan', 'poin_default' => 5],
            ['nama_kategori' => 'Makan di luar jadwal',          'tingkat' => 'ringan', 'poin_default' => 5],

            ['nama_kategori' => 'Tidak mengikuti kegiatan wajib','tingkat' => 'sedang', 'poin_default' => 15],
            ['nama_kategori' => 'Membawa HP tanpa izin',         'tingkat' => 'sedang', 'poin_default' => 20],
            ['nama_kategori' => 'Keluar pesantren tanpa izin',   'tingkat' => 'sedang', 'poin_default' => 25],
            ['nama_kategori' => 'Berbicara tidak sopan',         'tingkat' => 'sedang', 'poin_default' => 15],

            ['nama_kategori' => 'Membawa barang terlarang',      'tingkat' => 'berat', 'poin_default' => 50],
            ['nama_kategori' => 'Perkelahian / kekerasan fisik', 'tingkat' => 'berat', 'poin_default' => 75],
            ['nama_kategori' => 'Melanggar tata tertib asrama',  'tingkat' => 'berat', 'poin_default' => 50],
        ];

        foreach ($kategori as $k) {
            KategoriPelanggaran::updateOrCreate(
                ['nama_kategori' => $k['nama_kategori']],
                array_merge($k, ['is_active' => true])
            );
        }

        $this->command->info('✓ Kategori pelanggaran seeded: 12 kategori');
    }
}