<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hafalan extends Model
{
    use HasFactory;

    protected $table = 'hafalan';
    protected $fillable = [
        'santri_id', 'ustadz_id', 'nama_surat', 'nomor_juz',
        'halaman_dari', 'halaman_sampai', 'jumlah_halaman',
        'nilai', 'grade', 'jenis', 'tanggal_setoran', 'catatan',
    ];
    protected $casts = [
        'tanggal_setoran' => 'date',
        'nilai'           => 'decimal:2',
    ];

    // Auto-hitung grade dari nilai saat saving
    protected static function booted(): void
    {
        static::saving(function (self $model) {
            if ($model->nilai !== null) {
                $model->grade = match(true) {
                    $model->nilai >= 90 => 'A',
                    $model->nilai >= 75 => 'B',
                    $model->nilai >= 60 => 'C',
                    default             => 'D',
                };
            }
            if ($model->halaman_dari && $model->halaman_sampai) {
                $model->jumlah_halaman = abs($model->halaman_sampai - $model->halaman_dari) + 1;
            }
        });
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }

    public function ustadz()
    {
        return $this->belongsTo(User::class, 'ustadz_id');
    }

    public function getJenisLabelAttribute(): string
    {
        return $this->jenis === 'setoran_baru' ? 'Setoran Baru' : 'Muroja\'ah';
    }

    public function getGradeBadgeColorAttribute(): string
    {
        return match($this->grade) {
            'A'     => 'success',
            'B'     => 'info',
            'C'     => 'warning',
            'D'     => 'danger',
            default => 'secondary',
        };
    }
}