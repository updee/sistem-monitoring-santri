<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPelanggaran extends Model
{
    use HasFactory;

    protected $table = 'kategori_pelanggaran';
    protected $fillable = ['nama_kategori', 'tingkat', 'poin_default', 'deskripsi', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function pelanggaran()
    {
        return $this->hasMany(Pelanggaran::class, 'kategori_id');
    }

    public function getTingkatBadgeColorAttribute(): string
    {
        return match($this->tingkat) {
            'ringan' => 'warning',
            'sedang' => 'orange',
            'berat'  => 'danger',
            default  => 'secondary',
        };
    }
}

