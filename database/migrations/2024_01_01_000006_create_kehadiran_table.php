<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kehadiran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('santri_id');
            $table->unsignedBigInteger('ustadz_id')->nullable();
            $table->date('tanggal');
            $table->enum('sesi', ['pagi', 'siang', 'malam'])->default('pagi');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Mencegah duplikasi absensi santri per hari per sesi
            $table->unique(['santri_id', 'tanggal', 'sesi'], 'unique_absensi');

            $table->foreign('santri_id')->references('id')->on('santri')->cascadeOnDelete();
            $table->foreign('ustadz_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kehadiran');
    }
};
