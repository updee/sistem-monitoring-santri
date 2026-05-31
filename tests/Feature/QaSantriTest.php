<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Kamar;

class QaSantriTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_create_santri()
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) $this->markTestSkipped('Admin not found.');

        $kelas = Kelas::first() ?? Kelas::create(['nama_kelas' => '1A']);
        $kamar = Kamar::first() ?? Kamar::create(['nama_kamar' => 'A1', 'kapasitas' => 10]);

        $this->actingAs($admin);

        $response = $this->post('/admin/santri', [
            'nis' => 'QA999999',
            'nama' => 'Santri QA Test',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-01-01',
            'alamat' => 'Jalan QA Testing No 1',
            'kelas_id' => $kelas->id,
            'kamar_id' => $kamar->id,
            'status_aktif' => 1
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('santri', ['nis' => 'QA999999', 'nama' => 'Santri QA Test']);
    }
}
