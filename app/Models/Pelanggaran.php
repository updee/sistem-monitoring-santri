<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    use HasFactory;

    protected $table = 'pelanggaran';
    protected $fillable = [
        'santri_id', 'pencatat_id', 'kategori_id', 'jenis_pelanggaran',
        'poin_sanksi', 'tanggal', 'keterangan', 'bukti',
        'status_tindak_lanjut', 'tindak_lanjut',
    ];
    protected $casts = ['tanggal' => 'date'];

    protected static function booted(): void
    {
        static::created(function (self $model) {
            $model->checkAndCreateSp();
        });
    }

    public function checkAndCreateSp(): void
    {
        $totalPoin = self::where('santri_id', $this->santri_id)->sum('poin_sanksi');

        if ($totalPoin >= 100) {
            $this->createSpIfNotExists('SP 3', $totalPoin);
        } elseif ($totalPoin >= 75) {
            $this->createSpIfNotExists('SP 2', $totalPoin);
        } elseif ($totalPoin >= 50) {
            $this->createSpIfNotExists('SP 1', $totalPoin);
        }
    }

    private function createSpIfNotExists($jenisSp, $totalPoin)
    {
        $exists = SuratPanggilan::where('santri_id', $this->santri_id)
            ->where('jenis_sp', $jenisSp)
            ->exists();

        if (!$exists) {
            SuratPanggilan::create([
                'santri_id'      => $this->santri_id,
                'jenis_sp'       => $jenisSp,
                'total_poin'     => $totalPoin,
                'tanggal_terbit' => now(),
                'status'         => 'dikirim',
                'catatan_ustadz' => "Otomatis diterbitkan sistem karena akumulasi poin mencapai {$totalPoin}."
            ]);
        }
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }

    public function pencatat()
    {
        return $this->belongsTo(User::class, 'pencatat_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriPelanggaran::class, 'kategori_id');
    }
}
