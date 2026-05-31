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
        'ayat_dari', 'ayat_sampai',
        'nilai', 'grade', 'jenis', 'kategori',
        'tanggal_setoran', 'catatan',
        // Wisuda
        'target_wisuda', 'sesi_wisuda', 'status_wisuda', 'catatan_perbaikan',
        // Zaidah
        'zaidah_ke', 'keterangan_zaidah',
        // Ujian
        'jenis_ujian', 'model_ujian', 'status_ujian', 'jadwal_remedial',
        // Penilaian teknis
        'salah_ringan', 'salah_berat', 'kelancaran', 'tajwid_makhraj',
    ];
    protected $casts = [
        'tanggal_setoran' => 'date',
        'jadwal_remedial' => 'date',
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

    // ── Label Accessors ──────────────────────────────────────────

    public function getJenisLabelAttribute(): string
    {
        return $this->jenis === 'setoran_baru' ? 'Setoran Baru' : 'Muroja\'ah';
    }

    public function getKategoriLabelAttribute(): string
    {
        return match($this->kategori) {
            'wisuda' => 'Wisuda',
            'zaidah' => 'Zaidah',
            'ujian'  => 'Ujian',
            'harian' => 'Harian',
            default  => '-',
        };
    }

    public function getKategoriBadgeColorAttribute(): string
    {
        return match($this->kategori) {
            'wisuda' => 'badge-gold',
            'zaidah' => 'badge-blue',
            'ujian'  => 'badge-purple',
            'harian' => 'badge-teal',
            default  => 'badge-gray',
        };
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

    public function getTargetWisudaLabelAttribute(): string
    {
        return $this->target_wisuda ?? '-';
    }

    public function getSesiWisudaLabelAttribute(): string
    {
        return match($this->sesi_wisuda) {
            'setoran_bertahap' => 'Setoran Bertahap',
            'tasmi'            => 'Tasmi\'',
            default            => '-',
        };
    }

    public function getStatusWisudaLabelAttribute(): string
    {
        return match($this->status_wisuda) {
            'lulus'        => 'Lulus',
            'perbaikan'    => 'Perbaikan',
            'belum_lulus'  => 'Belum Lulus',
            default        => '-',
        };
    }

    public function getStatusWisudaBadgeAttribute(): string
    {
        return match($this->status_wisuda) {
            'lulus'       => 'badge-green',
            'perbaikan'   => 'badge-gold',
            'belum_lulus' => 'badge-red',
            default       => 'badge-gray',
        };
    }

    public function getJenisUjianLabelAttribute(): string
    {
        return match($this->jenis_ujian) {
            'pekanan'          => 'Pekanan',
            'bulanan'          => 'Bulanan',
            'tengah_semester'  => 'Tengah Semester',
            'semester'         => 'Semester',
            default            => '-',
        };
    }

    public function getModelUjianLabelAttribute(): string
    {
        return match($this->model_ujian) {
            'tasmi'         => 'Tasmi\'',
            'sambung_ayat'  => 'Sambung Ayat',
            'acak_halaman'  => 'Acak Halaman',
            default         => '-',
        };
    }

    public function getStatusUjianLabelAttribute(): string
    {
        return match($this->status_ujian) {
            'lulus'    => 'Lulus',
            'remedial' => 'Remedial',
            default    => '-',
        };
    }

    public function getStatusUjianBadgeAttribute(): string
    {
        return match($this->status_ujian) {
            'lulus'    => 'badge-green',
            'remedial' => 'badge-red',
            default    => 'badge-gray',
        };
    }
}