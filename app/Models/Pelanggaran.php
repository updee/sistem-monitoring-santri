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
