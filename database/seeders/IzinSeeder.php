<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Santri;
use App\Models\User;
use App\Models\Izin;
use Carbon\Carbon;

class IzinSeeder extends Seeder
{
    public function run(): void
    {
        // Idempotent: jangan menumpuk data ketika db:seed dijalankan berulang
        Izin::query()->delete();

        $santriIds = Santri::aktif()->pluck('id')->toArray();
        $waliUsers = User::where('role', 'wali_santri')->get();
        $adminId   = User::where('role', 'admin')->value('id');
        if (empty($santriIds) || $waliUsers->isEmpty() || ! $adminId) {
            $this->command->warn('  ! IzinSeeder dilewati: data santri/wali/admin belum tersedia.');
            return;
        }

        $alasanList = [
            'Keperluan keluarga (acara pernikahan saudara)',
            'Pemeriksaan kesehatan ke rumah sakit',
            'Libur akhir semester - pulang ke rumah',
            'Menghadiri acara keluarga di kampung halaman',
            'Kontrol kesehatan rutin',
        ];

        foreach ($santriIds as $i => $santriId) {
            if ($i >= $waliUsers->count()) break;
            $wali = $waliUsers[$i];

            // Izin sudah diproses (disetujui)
            Izin::create([
                'santri_id'          => $santriId,
                'pengaju_id'         => $wali->id,
                'approver_id'        => $adminId,
                'tanggal_mulai'      => Carbon::now()->subDays(20)->toDateString(),
                'tanggal_kembali'    => Carbon::now()->subDays(17)->toDateString(),
                'alasan'             => $alasanList[rand(0, count($alasanList) - 1)],
                'nama_penjemput'     => $wali->name,
                'no_telepon_penjemput' => '08' . rand(1000000000, 9999999999),
                'status'             => 'disetujui',
                'catatan_admin'      => 'Izin disetujui. Harap kembali tepat waktu.',
                'diproses_pada'      => Carbon::now()->subDays(20),
            ]);
        }

        // Izin menunggu (baru diajukan) — 3 data
        foreach (array_slice($santriIds, 0, 3) as $i => $santriId) {
            $wali = $waliUsers[$i + 5] ?? $waliUsers[0];
            Izin::create([
                'santri_id'       => $santriId,
                'pengaju_id'      => $wali->id,
                'approver_id'     => null,
                'tanggal_mulai'   => Carbon::now()->addDays(3)->toDateString(),
                'tanggal_kembali' => Carbon::now()->addDays(5)->toDateString(),
                'alasan'          => 'Keperluan keluarga mendesak.',
                'nama_penjemput'  => $wali->name,
                'status'          => 'menunggu',
            ]);
        }

        $this->command->info('  ✓ Izin seeded: izin disetujui + 3 menunggu');
    }
}
