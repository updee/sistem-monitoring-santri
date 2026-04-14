<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pencapaian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('santri_id');
            $table->unsignedBigInteger('pencatat_id')->nullable();
            $table->string('judul_pencapaian');
            $table->string('jenis')->comment('Akademik / Non-Akademik / Hafalan / Olahraga / dll');
            $table->enum('tingkat', ['pesantren', 'kabupaten', 'provinsi', 'nasional', 'internasional'])
                  ->default('pesantren');
            $table->enum('peringkat', ['juara_1', 'juara_2', 'juara_3', 'harapan', 'peserta'])
                  ->default('peserta');
            $table->date('tanggal');
            $table->string('penyelenggara')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('foto_sertifikat')->nullable();
            $table->timestamps();

            $table->foreign('santri_id')->references('id')->on('santri')->cascadeOnDelete();
            $table->foreign('pencatat_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pencapaian');
    }
};
