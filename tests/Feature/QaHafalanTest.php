<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Santri;

class QaHafalanTest extends TestCase
{
    use DatabaseTransactions;

    public function test_ustadz_can_create_hafalan()
    {
        $ustadz = User::where('role', 'ustadz')->first();
        if (!$ustadz) $this->markTestSkipped('Ustadz not found.');

        $santri = Santri::first();
        if (!$santri) $this->markTestSkipped('Santri not found.');

        $this->actingAs($ustadz);

        $response = $this->post('/ustadz/hafalan', [
            'santri_id' => $santri->id,
            'tanggal_setoran' => now()->format('Y-m-d'),
            'nama_surat' => 'Al-Mulk',
            'ayat_dari' => 1,
            'ayat_sampai' => 10,
            'jenis' => 'setoran_baru',
            'kategori' => 'harian',
            'nilai' => 85,
            'catatan' => 'QA Testing'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('hafalan', [
            'santri_id' => $santri->id,
            'nama_surat' => 'Al-Mulk'
        ]);
    }
    
    public function test_ustadz_can_create_pencapaian()
    {
        $ustadz = User::where('role', 'ustadz')->first();
        if (!$ustadz) $this->markTestSkipped('Ustadz not found.');

        $santri = Santri::first();
        if (!$santri) $this->markTestSkipped('Santri not found.');

        $this->actingAs($ustadz);

        $response = $this->post('/ustadz/pencapaian', [
            'santri_id' => $santri->id,
            'tanggal' => now()->format('Y-m-d'),
            'judul_pencapaian' => 'Juara 1 Lomba Tahfidz (QA)',
            'jenis' => 'Akademik',
            'tingkat' => 'nasional',
            'peringkat' => 'juara_1',
            'keterangan' => 'QA Testing'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pencapaian', [
            'santri_id' => $santri->id,
            'judul_pencapaian' => 'Juara 1 Lomba Tahfidz (QA)'
        ]);
    }
}
