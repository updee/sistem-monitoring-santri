<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $fillable = ['nama_kelas', 'tingkat', 'ustadz_id', 'keterangan', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function ustadz()
    {
        return $this->belongsTo(User::class, 'ustadz_id');
    }

    public function santri()
    {
        return $this->hasMany(Santri::class, 'kelas_id');
    }

    public function getJumlahSantriAttribute(): int
    {
        return $this->santri()->where('status', 'aktif')->count();
    }
}
