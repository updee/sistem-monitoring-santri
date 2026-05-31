<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesiKehadiran extends Model
{
    use HasFactory;

    protected $table = 'sesi_kehadiran';
    protected $fillable = ['nama_sesi', 'urutan', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'sesi_kehadiran_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('is_active', true)->orderBy('urutan');
    }
}
