<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';
    protected $fillable = ['nama_kamar', 'gedung', 'kapasitas', 'keterangan', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function santri()
    {
        return $this->hasMany(Santri::class, 'kamar_id');
    }

    public function getJumlahPenghuniAttribute(): int
    {
        return $this->santri()->where('status', 'aktif')->count();
    }

    public function getSisaKapasitasAttribute(): int
    {
        return max(0, $this->kapasitas - $this->jumlah_penghuni);
    }
}