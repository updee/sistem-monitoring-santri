<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->enum('tingkat', ['ringan', 'sedang', 'berat']);
            $table->integer('poin_default')->default(0)->comment('Poin sanksi default untuk kategori ini');
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_pelanggaran');
    }
};
