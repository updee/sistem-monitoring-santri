<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    use HasFactory;

    protected $table = 'kehadiran';
    protected $fillable = [
        'santri_id', 'ustadz_id', 'tanggal', 'sesi_kehadiran_id', 'status', 'keterangan',
    ];
    protected $casts = ['tanggal' => 'date'];

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }

    public function ustadz()
    {
        return $this->belongsTo(User::class, 'ustadz_id');
    }

    public function sesiKehadiran()
    {
        return $this->belongsTo(SesiKehadiran::class, 'sesi_kehadiran_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'hadir' => 'Hadir',
            'izin'  => 'Izin',
            'sakit' => 'Sakit',
            'alpha' => 'Alpha',
            default => '-',
        };
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'hadir' => 'success',
            'izin'  => 'info',
            'sakit' => 'warning',
            'alpha' => 'danger',
            default => 'secondary',
        };
    }
}
