<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratPanggilan extends Model
{
    use HasFactory;

    protected $table = 'surat_panggilan';

    protected $fillable = [
        'santri_id',
        'jenis_sp',
        'total_poin',
        'tanggal_terbit',
        'status',
        'catatan_ustadz',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }
}
