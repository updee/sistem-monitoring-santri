<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'no_telepon', 'foto', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active'         => 'boolean',
    ];

    // ── Role Helpers ────────────────────────────────────────────────────────
    public function isAdmin(): bool      { return $this->role === 'admin'; }
    public function isUstadz(): bool     { return $this->role === 'ustadz'; }
    public function isWaliSantri(): bool { return $this->role === 'wali_santri'; }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin'       => 'Administrator',
            'ustadz'      => 'Ustadz / Guru',
            'wali_santri' => 'Wali Santri',
            default       => 'Unknown',
        };
    }

    public function getFotoUrlAttribute(): string
    {
        return $this->foto
            ? asset('storage/' . $this->foto)
            : asset('images/default-avatar.png');
    }

    // ── Relationships ────────────────────────────────────────────────────────

    /** Santri yang walinya adalah user ini */
    public function santriWali()
    {
        return $this->hasMany(Santri::class, 'wali_id');
    }

    /** Kelas yang diampu ustadz ini */
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'ustadz_id');
    }

    /** Hafalan yang dicatat oleh ustadz ini */
    public function hafalan()
    {
        return $this->hasMany(Hafalan::class, 'ustadz_id');
    }

    /** Kehadiran yang dicatat oleh ustadz ini */
    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'ustadz_id');
    }

    /** Pelanggaran yang dicatat oleh user ini */
    public function pelanggaran()
    {
        return $this->hasMany(Pelanggaran::class, 'pencatat_id');
    }

    /** Pencapaian yang dicatat oleh user ini */
    public function pencapaian()
    {
        return $this->hasMany(Pencapaian::class, 'pencatat_id');
    }

    /** Izin yang diajukan oleh wali santri ini */
    public function izinDiajukan()
    {
        return $this->hasMany(Izin::class, 'pengaju_id');
    }

    /** Izin yang diproses (setujui/tolak) oleh admin ini */
    public function izinDisetujui()
    {
        return $this->hasMany(Izin::class, 'approver_id');
    }
}
