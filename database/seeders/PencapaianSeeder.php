<?php

namespace Database\Seeders; // 🔥 WAJIB

use App\Models\Santri;
use App\Models\User;
use App\Models\Pencapaian;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PencapaianSeeder extends Seeder
{
    public function run(): void
    {
        // Idempotent: jangan menumpuk data ketika db:seed dijalankan berulang
        Pencapaian::query()->delete();

        $santriIds   = Santri::aktif()->pluck('id')->toArray();
        $pencatatIds = User::whereIn('role', ['admin', 'ustadz'])->pluck('id')->toArray();
        if (empty($santriIds) || empty($pencatatIds)) {
            $this->command->warn('  ! PencapaianSeeder dilewati: data santri/pencatat belum tersedia.');
            return;
        }

        $jenisPrestasiList = [
            'MTQ (Musabaqah Tilawatil Quran)',
            'Olimpiade Matematika',
            'Lomba Pidato Bahasa Arab',
            'Lomba Kaligrafi',
            'Tahfidz Competition',
            'Futsal Antar Pesantren',
            'Lomba Debat Bahasa Inggris',
            'Olimpiade Sains Pesantren',
        ];

        $pemenang = array_slice($santriIds, 0, 8);

        foreach ($pemenang as $i => $santriId) {
            Pencapaian::create([
                'santri_id'        => $santriId,
                'pencatat_id'      => $pencatatIds[array_rand($pencatatIds)],
                'judul_pencapaian' => $jenisPrestasiList[$i],
                'jenis'            => $i % 2 === 0 ? 'Non-Akademik' : 'Akademik',
                'tingkat'          => ['pesantren', 'kabupaten', 'provinsi'][rand(0, 2)],
                'peringkat'        => ['juara_1', 'juara_2', 'juara_3', 'harapan'][rand(0, 3)],
                'tanggal'          => Carbon::now()->subDays(rand(10, 120))->toDateString(),
                'penyelenggara'    => 'IBS Ash-Shiddiiqi Jambi',
                'keterangan'       => 'Alhamdulillah, santri berprestasi.',
            ]);
        }

        $this->command->info('✓ Pencapaian seeded: 8 prestasi');
    }
}