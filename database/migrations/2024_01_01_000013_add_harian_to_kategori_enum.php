<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE hafalan MODIFY COLUMN kategori ENUM('wisuda','zaidah','ujian','harian') NULL DEFAULT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE hafalan MODIFY COLUMN kategori ENUM('wisuda','zaidah','ujian') NULL DEFAULT NULL");
    }
};
