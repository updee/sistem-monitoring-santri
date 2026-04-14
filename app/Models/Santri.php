<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Santri extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'santri';

    protected $fillable = [
        'nis', 'nama', 'jenis_kelamin', 'tanggal_lahir', 'tempat_lahir',
        'alamat', 'no_telepon', 'foto', 'kelas_id', 'kamar_id', 'wali_id',
        'tanggal_masuk', 'tanggal_keluar', 'status', 'catatan',
    ];

    protected $casts = [
        'tanggal_lahir'  => 'date',
        'tanggal_masuk'  => 'date',
        'tanggal_keluar' => 'date',
    ];

    // ── Accessors ────────────────────────────────────────────────────────────
    public function getFotoUrlAttribute(): string
    {
        return $this->foto
            ? asset('storage/' . $this->foto)
            : asset('images/default-santri.png');
    }

    public function getJenisKelaminLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'aktif'  => 'Aktif',
            'alumni' => 'Alumni',
            'keluar' => 'Keluar',
            default  => '-',
        };
    }

    public function getUsiAttribute(): ?int
    {
        return $this->tanggal_lahir
            ? $this->tanggal_lahir->age
            : null;
    }

    // ── Relationships ────────────────────────────────────────────────────────
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }

    public function wali()
    {
        return $this->belongsTo(User::class, 'wali_id');
    }

    public function hafalan()
    {
        return $this->hasMany(Hafalan::class, 'santri_id')->latest('tanggal_setoran');
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'santri_id')->latest('tanggal');
    }

    public function pelanggaran()
    {
        return $this->hasMany(Pelanggaran::class, 'santri_id')->latest('tanggal');
    }

    public function pencapaian()
    {
        return $this->hasMany(Pencapaian::class, 'santri_id')->latest('tanggal');
    }

    public function izin()
    {
        return $this->hasMany(Izin::class, 'santri_id')->latest();
    }

    // ── Scopes ───────────────────────────────────────────────────────────────
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // ── Helper Methods ────────────────────────────────────────────────────────
    /** Total poin pelanggaran santri */
    public function getTotalPoinPelanggaranAttribute(): int
    {
        return $this->pelanggaran()->sum('poin_sanksi');
    }

    /** Total halaman hafalan tercatat (setoran baru & muroja'ah) */
    public function getTotalHalamanHafalanAttribute(): int
    {
        return (int) $this->hafalan()->sum('jumlah_halaman');
    }

    /** Persentase kehadiran bulan ini */
    public function getPersentaseKehadiranBulanIniAttribute(): float
    {
        $bulan = now()->month;
        $tahun = now()->year;

        $total  = $this->kehadiran()
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->count();

        $hadir  = $this->kehadiran()
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('status', 'hadir')
            ->count();

        return $total > 0 ? round(($hadir / $total) * 100, 1) : 0;
    }
}
