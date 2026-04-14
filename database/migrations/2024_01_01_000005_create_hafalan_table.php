<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hafalan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('santri_id');
            $table->unsignedBigInteger('ustadz_id')->nullable()->comment('Ustadz yang menyimak');
            $table->string('nama_surat');
            $table->integer('nomor_juz')->nullable();
            $table->integer('halaman_dari')->nullable();
            $table->integer('halaman_sampai')->nullable();
            $table->integer('jumlah_halaman')->default(0);
            $table->decimal('nilai', 5, 2)->nullable()->comment('Nilai 0-100');
            $table->enum('grade', ['A', 'B', 'C', 'D'])->nullable()->comment('A>=90, B>=75, C>=60, D<60');
            $table->enum('jenis', ['setoran_baru', 'murojaah'])->default('setoran_baru');
            $table->date('tanggal_setoran');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('santri_id')->references('id')->on('santri')->cascadeOnDelete();
            $table->foreign('ustadz_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hafalan');
    }
};
