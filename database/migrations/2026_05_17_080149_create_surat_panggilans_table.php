<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_panggilan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->string('jenis_sp', 20); // SP 1, SP 2, SP 3
            $table->integer('total_poin');
            $table->date('tanggal_terbit');
            $table->enum('status', ['draf', 'dikirim', 'selesai'])->default('draf');
            $table->text('catatan_ustadz')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surat_panggilan');
    }
};
