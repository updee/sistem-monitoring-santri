<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;

class QaReportTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_access_all_report_pages()
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) $this->markTestSkipped('Admin not found.');

        $this->actingAs($admin);

        $reports = [
            '/admin/laporan/kehadiran',
            '/admin/laporan/pelanggaran',
            '/admin/laporan/hafalan',
            '/admin/laporan/pencapaian',
            '/admin/laporan/izin',
        ];

        foreach ($reports as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_admin_can_export_reports()
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) $this->markTestSkipped('Admin not found.');

        $this->actingAs($admin);

        // We will just test one or two export endpoints to ensure PhpSpreadsheet does not throw 500
        $exports = [
            '/admin/laporan/export/kehadiran',
            '/admin/laporan/export/pelanggaran',
        ];

        foreach ($exports as $route) {
            $response = $this->get($route);
            // Export usually returns 200 with headers for file download
            $response->assertStatus(200);
            $response->assertHeader('content-type');
        }
    }
}
