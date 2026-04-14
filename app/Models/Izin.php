<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    use HasFactory;

    protected $table = 'izin';
    protected $fillable = [
        'santri_id', 'pengaju_id', 'approver_id',
        'tanggal_mulai', 'tanggal_kembali', 'alasan',
        'no_telepon_penjemput', 'nama_penjemput',
        'status', 'catatan_admin', 'diproses_pada',
    ];
    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_kembali' => 'date',
        'diproses_pada'   => 'datetime',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }

    public function pengaju()
    {
        return $this->belongsTo(User::class, 'pengaju_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function getDurasiAttribute(): int
    {
        return $this->tanggal_mulai->diffInDays($this->tanggal_kembali) + 1;
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'menunggu'   => 'Menunggu',
            'disetujui'  => 'Disetujui',
            'ditolak'    => 'Ditolak',
            default      => '-',
        };
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'menunggu'   => 'warning',
            'disetujui'  => 'success',
            'ditolak'    => 'danger',
            default      => 'secondary',
        };
    }

    public function scopeMenunggu($query)   { return $query->where('status', 'menunggu'); }
    public function scopeDisetujui($query)  { return $query->where('status', 'disetujui'); }
}
