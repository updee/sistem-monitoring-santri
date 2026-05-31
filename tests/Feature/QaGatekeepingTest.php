<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;

class QaGatekeepingTest extends TestCase
{
    use DatabaseTransactions; // Penting agar tidak merusak data skripsi user

    public function test_login_admin_redirects_to_admin_dashboard()
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Tidak ada user admin di database.');
        }

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'admin123', // Asumsi password default
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_wali_cannot_access_admin_dashboard()
    {
        $wali = User::where('role', 'wali_santri')->first();
        if (!$wali) {
            $this->markTestSkipped('Tidak ada user wali santri di database.');
        }

        $this->actingAs($wali);

        $response = $this->get('/admin/dashboard');

        // Middleware role harus menolak (biasanya 403 atau redirect kembali)
        $this->assertTrue(in_array($response->status(), [403, 302]));
    }
}
