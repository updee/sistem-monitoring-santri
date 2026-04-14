<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('izin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('santri_id');
            $table->unsignedBigInteger('pengaju_id')->nullable()->comment('Wali santri yang mengajukan');
            $table->unsignedBigInteger('approver_id')->nullable()->comment('Admin yang menyetujui/menolak');
            $table->date('tanggal_mulai');
            $table->date('tanggal_kembali');
            $table->text('alasan');
            $table->string('no_telepon_penjemput', 20)->nullable();
            $table->string('nama_penjemput')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable();
            $table->timestamp('diproses_pada')->nullable();
            $table->timestamps();

            $table->foreign('santri_id')->references('id')->on('santri')->cascadeOnDelete();
            $table->foreign('pengaju_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('approver_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('izin');
    }
};
