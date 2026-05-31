<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Kamar;
use App\Models\SesiKehadiran;

class QaMasterDataTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_create_kelas()
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) $this->markTestSkipped('Admin not found.');

        $this->actingAs($admin);

        $response = $this->post('/admin/kelas', [
            'nama_kelas' => 'Kelas QA Test',
            'tingkat' => 'SMP',
            'deskripsi' => 'Dibuat oleh Auto QA'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('kelas', ['nama_kelas' => 'Kelas QA Test']);
    }

    public function test_admin_can_create_kamar()
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) $this->markTestSkipped('Admin not found.');

        $this->actingAs($admin);

        $response = $this->post('/admin/kamar', [
            'nama_kamar' => 'Kamar QA Test',
            'kapasitas' => 10,
            'lokasi' => 'Gedung A',
            'keterangan' => 'Dibuat oleh Auto QA'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('kamar', ['nama_kamar' => 'Kamar QA Test']);
    }

    public function test_admin_can_create_sesi()
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) $this->markTestSkipped('Admin not found.');

        $this->actingAs($admin);

        $response = $this->post('/admin/sesi-kehadiran', [
            'nama_sesi' => 'Sesi QA Test',
            'urutan' => 1,
            'waktu_mulai' => '08:00',
            'waktu_selesai' => '10:00'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('sesi_kehadiran', ['nama_sesi' => 'Sesi QA Test']);
    }

    public function test_admin_can_toggle_user_status()
    {
        $admin = User::where('role', 'admin')->first();
        $wali = User::where('role', 'wali_santri')->first();
        if (!$admin || !$wali) $this->markTestSkipped('User not found.');

        $this->actingAs($admin);
        
        $initialStatus = $wali->is_active;

        $response = $this->patch("/admin/users/{$wali->id}/toggle-active");
        $response->assertRedirect();
        
        $this->assertNotEquals($initialStatus, $wali->fresh()->is_active);
    }
}
