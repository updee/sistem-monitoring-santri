<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Santri;
use App\Models\Izin;

class QaIzinWorkflowTest extends TestCase
{
    use DatabaseTransactions;

    public function test_workflow_pengajuan_izin()
    {
        // Setup User Wali
        $wali = User::where('role', 'wali_santri')->first();
        if (!$wali) {
            $this->markTestSkipped('Tidak ada user wali santri.');
        }

        // Setup Admin
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Tidak ada user admin.');
        }

        // Setup Santri
        $santri = Santri::first();
        if (!$santri) {
            $this->markTestSkipped('Tidak ada santri.');
        }

        // Pastikan wali terkait dengan santri
        $santri->wali_id = $wali->id;
        $santri->save();

        // ==========================
        // 1. WALI MENGAJUKAN IZIN
        // ==========================
        $this->actingAs($wali);
        
        $response = $this->post('/wali/izin', [
            'alasan' => 'Acara keluarga (QA Test)',
            'tanggal_mulai' => now()->addDays(1)->format('Y-m-d'),
            'tanggal_kembali' => now()->addDays(3)->format('Y-m-d'),
        ]);

        $response->assertRedirect();

        $izin = Izin::where('alasan', 'Acara keluarga (QA Test)')->orderBy('id', 'desc')->first();
        $this->assertNotNull($izin);
        $this->assertEquals('menunggu', strtolower($izin->status));

        // ==========================
        // 2. ADMIN MENYETUJUI IZIN
        // ==========================
        $this->actingAs($admin);

        $responseAdmin = $this->patch("/admin/izin/{$izin->id}/setujui");
        $responseAdmin->assertRedirect();

        $this->assertEquals('disetujui', strtolower($izin->fresh()->status));
    }
}
