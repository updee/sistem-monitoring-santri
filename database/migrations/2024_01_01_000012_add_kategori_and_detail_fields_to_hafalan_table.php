<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hafalan', function (Blueprint $table) {
            // ── Kategori Program ─────────────────────────────────
            $table->enum('kategori', ['wisuda', 'zaidah', 'ujian', 'harian'])
                  ->nullable()
                  ->after('jenis')
                  ->comment('Kategori program hafalan');

            // ── Ayat opsional ────────────────────────────────────
            $table->integer('ayat_dari')->nullable()->after('halaman_sampai');
            $table->integer('ayat_sampai')->nullable()->after('ayat_dari');

            // ── Wisuda fields ────────────────────────────────────
            $table->string('target_wisuda', 100)->nullable()->after('catatan')
                  ->comment('cth: Paket Juz 30, 5 Juz');
            $table->enum('sesi_wisuda', ['setoran_bertahap', 'tasmi'])
                  ->nullable()->after('target_wisuda');
            $table->enum('status_wisuda', ['lulus', 'perbaikan', 'belum_lulus'])
                  ->nullable()->after('sesi_wisuda');
            $table->text('catatan_perbaikan')->nullable()->after('status_wisuda');

            // ── Zaidah fields ────────────────────────────────────
            $table->integer('zaidah_ke')->nullable()->after('catatan_perbaikan')
                  ->comment('Urutan capaian zaidah');
            $table->text('keterangan_zaidah')->nullable()->after('zaidah_ke');

            // ── Ujian fields ─────────────────────────────────────
            $table->enum('jenis_ujian', ['pekanan', 'bulanan', 'tengah_semester', 'semester'])
                  ->nullable()->after('keterangan_zaidah');
            $table->enum('model_ujian', ['tasmi', 'sambung_ayat', 'acak_halaman'])
                  ->nullable()->after('jenis_ujian');
            $table->enum('status_ujian', ['lulus', 'remedial'])
                  ->nullable()->after('model_ujian');
            $table->date('jadwal_remedial')->nullable()->after('status_ujian')
                  ->comment('Muncul jika status ujian = remedial');

            // ── Penilaian teknis ─────────────────────────────────
            $table->unsignedInteger('salah_ringan')->nullable()->after('grade')
                  ->comment('Jumlah kesalahan ringan');
            $table->unsignedInteger('salah_berat')->nullable()->after('salah_ringan')
                  ->comment('Jumlah kesalahan berat');
            $table->unsignedTinyInteger('kelancaran')->nullable()->after('salah_berat')
                  ->comment('Skala 1-5');
            $table->unsignedTinyInteger('tajwid_makhraj')->nullable()->after('kelancaran')
                  ->comment('Skala 1-5');
        });
    }

    public function down(): void
    {
        Schema::table('hafalan', function (Blueprint $table) {
            $table->dropColumn([
                'kategori',
                'ayat_dari', 'ayat_sampai',
                'target_wisuda', 'sesi_wisuda', 'status_wisuda', 'catatan_perbaikan',
                'zaidah_ke', 'keterangan_zaidah',
                'jenis_ujian', 'model_ujian', 'status_ujian', 'jadwal_remedial',
                'salah_ringan', 'salah_berat', 'kelancaran', 'tajwid_makhraj',
            ]);
        });
    }
};
