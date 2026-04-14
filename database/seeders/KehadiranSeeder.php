<?php
namespace Database\Seeders;

use App\Models\Santri;
use App\Models\User;
use App\Models\Kehadiran;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class KehadiranSeeder extends Seeder
{
    public function run(): void
    {
        $santriIds = Santri::aktif()->pluck('id')->toArray();
        $ustadzId  = User::where('role', 'ustadz')->value('id');
        if (! $ustadzId) {
            $this->command->warn('  ! KehadiranSeeder dilewati: tidak ada user role ustadz.');
            return;
        }

        // Buat absensi 30 hari terakhir, sesi pagi
        $statuses = ['hadir', 'hadir', 'hadir', 'hadir', 'hadir', 'hadir', 'hadir', 'sakit', 'izin', 'alpha'];

        foreach ($santriIds as $santriId) {
            for ($hari = 29; $hari >= 0; $hari--) {
                $tanggal = Carbon::now()->subDays($hari)->toDateString();
                $status  = $statuses[array_rand($statuses)];

                Kehadiran::updateOrCreate(
                    [
                        'santri_id' => $santriId,
                        'tanggal'   => $tanggal,
                        'sesi'      => 'pagi',
                    ],
                    [
                        'ustadz_id'  => $ustadzId,
                        'status'     => $status,
                        'keterangan' => $status !== 'hadir' ? 'Keterangan ' . $status : null,
                    ]
                );
            }
        }

        $this->command->info('  ✓ Kehadiran seeded: 30 hari x ' . count($santriIds) . ' santri');
    }
}