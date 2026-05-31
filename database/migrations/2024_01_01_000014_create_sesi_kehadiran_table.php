<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesi_kehadiran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sesi')->unique();
            $table->integer('urutan')->default(0)->comment('Urutan tampil di dropdown');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesi_kehadiran');
    }
};
