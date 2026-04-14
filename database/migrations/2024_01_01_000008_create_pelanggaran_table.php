<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('santri_id');
            $table->unsignedBigInteger('pencatat_id')->nullable()->comment('Admin atau Ustadz yang mencatat');
            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->string('jenis_pelanggaran');
            $table->integer('poin_sanksi')->default(0);
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->string('bukti')->nullable()->comment('Path file foto/bukti jika ada');
            $table->enum('status_tindak_lanjut', ['belum', 'sudah'])->default('belum');
            $table->text('tindak_lanjut')->nullable();
            $table->timestamps();

            $table->foreign('santri_id')->references('id')->on('santri')->cascadeOnDelete();
            $table->foreign('pencatat_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('kategori_id')->references('id')->on('kategori_pelanggaran')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggaran');
    }
};
