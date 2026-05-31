<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Santri;
use App\Models\Pelanggaran;
use App\Models\SuratPanggilan;
use App\Models\KategoriPelanggaran;

class QaSpTriggerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_input_pelanggaran_triggers_sp_otomatis()
    {
        // Setup User Ustadz
        $ustadz = User::where('role', 'ustadz')->first();
        if (!$ustadz) {
            $this->markTestSkipped('Tidak ada user ustadz di database.');
        }

        // Cari atau buat kategori pelanggaran sementara untuk test
        $kategori = KategoriPelanggaran::firstOrCreate(
            ['nama_kategori' => 'Pelanggaran Berat Test QA'],
            ['poin' => 80] // Poin 80 seharusnya memicu SP 2 (karena > 75)
        );

        // Ambil santri acak
        $santri = Santri::first();
        if (!$santri) {
            $this->markTestSkipped('Tidak ada santri di database.');
        }

        // Reset data santri dalam transaksi ini agar poin mulai dari 0 murni
        $santri->update(['total_poin' => 0]);
        SuratPanggilan::where('santri_id', $santri->id)->delete();
        Pelanggaran::where('santri_id', $santri->id)->delete();

        // Hitung poin awal (harus 0)
        $initialPoin = Pelanggaran::where('santri_id', $santri->id)->sum('poin_sanksi');

        $this->actingAs($ustadz);

        // Simulasikan submit form tambah pelanggaran
        $response = $this->post("/ustadz/pelanggaran", [
            'santri_id' => $santri->id,
            'kategori_id' => $kategori->id,
            'jenis_pelanggaran' => 'Pelanggaran Berat Test QA',
            'poin_sanksi' => 80,
            'tanggal' => now()->format('Y-m-d'),
            'keterangan' => 'QA Auto Test',
            'status_tindak_lanjut' => 'belum'
        ]);

        $response->assertRedirect();
        
        // Assert bahwa total poin santri bertambah menjadi 80
        $totalPoin = Pelanggaran::where('santri_id', $santri->id)->sum('poin_sanksi');
        $this->assertEquals($initialPoin + 80, $totalPoin);

        // Assert bahwa Surat Panggilan SP 2 otomatis terbuat
        $sp = SuratPanggilan::where('santri_id', $santri->id)
                ->orderBy('id', 'desc')
                ->first();
                
        $this->assertNotNull($sp, 'Surat Panggilan tidak otomatis terbuat.');
        $this->assertEquals('SP 2', $sp->jenis_sp);
        $this->assertEquals('dikirim', $sp->status);
    }
}
