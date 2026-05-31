<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Santri;
use App\Models\Kelas;
use App\Models\SesiKehadiran;
use App\Models\Kehadiran;

class QaKehadiranTest extends TestCase
{
    use DatabaseTransactions;

    public function test_ustadz_can_bulk_input_kehadiran()
    {
        $ustadz = User::where('role', 'ustadz')->first();
        if (!$ustadz) $this->markTestSkipped('Ustadz not found.');

        $kelas = Kelas::first();
        $sesi = SesiKehadiran::first();
        $santri = Santri::where('kelas_id', $kelas->id)->first();
        
        if (!$kelas || !$sesi || !$santri) $this->markTestSkipped('Missing data.');

        $this->actingAs($ustadz);

        $response = $this->post('/ustadz/kehadiran/store-bulk', [
            'kelas_id' => $kelas->id,
            'sesi_kehadiran_id' => $sesi->id,
            'tanggal' => now()->format('Y-m-d'),
            'kehadiran' => [
                $santri->id => 'hadir'
            ]
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('kehadiran', [
            'santri_id' => $santri->id,
            'sesi_kehadiran_id' => $sesi->id,
            'status' => 'hadir'
        ]);
    }
}
