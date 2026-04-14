<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pencapaian extends Model
{
    use HasFactory;

    protected $table = 'pencapaian';
    protected $fillable = [
        'santri_id', 'pencatat_id', 'judul_pencapaian', 'jenis',
        'tingkat', 'peringkat', 'tanggal', 'penyelenggara',
        'keterangan', 'foto_sertifikat',
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

    public function getTingkatLabelAttribute(): string
    {
        return match($this->tingkat) {
            'pesantren'     => 'Pesantren',
            'kabupaten'     => 'Kabupaten',
            'provinsi'      => 'Provinsi',
            'nasional'      => 'Nasional',
            'internasional' => 'Internasional',
            default         => '-',
        };
    }

    public function getPeringkatLabelAttribute(): string
    {
        return match($this->peringkat) {
            'juara_1'  => 'Juara 1',
            'juara_2'  => 'Juara 2',
            'juara_3'  => 'Juara 3',
            'harapan'  => 'Harapan',
            'peserta'  => 'Peserta',
            default    => '-',
        };
    }
}
