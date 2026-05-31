<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('kehadiran', 'sesi_kehadiran_id')) {
            return;
        }

        // 1) Seed default sesi from the existing enum values
        $mapping = [
            'pagi'  => ['nama_sesi' => 'Pagi',  'urutan' => 1],
            'siang' => ['nama_sesi' => 'Siang', 'urutan' => 2],
            'malam' => ['nama_sesi' => 'Malam', 'urutan' => 3],
        ];

        foreach ($mapping as $key => $data) {
            DB::table('sesi_kehadiran')->insertOrIgnore([
                'nama_sesi'  => $data['nama_sesi'],
                'urutan'     => $data['urutan'],
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2) Add the new FK column
        Schema::table('kehadiran', function (Blueprint $table) {
            $table->unsignedBigInteger('sesi_kehadiran_id')->nullable()->after('sesi');
            $table->foreign('sesi_kehadiran_id')->references('id')->on('sesi_kehadiran')->nullOnDelete();
        });

        // 3) Migrate old enum data to the new FK column
        $sesiRows = DB::table('sesi_kehadiran')->pluck('id', 'nama_sesi');
        // Map: 'pagi' => id of 'Pagi', etc.
        $lowerMap = $sesiRows->mapWithKeys(fn($id, $name) => [strtolower($name) => $id]);

        foreach ($lowerMap as $oldVal => $newId) {
            DB::table('kehadiran')
                ->where('sesi', $oldVal)
                ->update(['sesi_kehadiran_id' => $newId]);
        }

        // 4) Drop old unique constraint and sesi column, create new unique
        Schema::table('kehadiran', function (Blueprint $table) {
            $table->dropUnique('unique_absensi');
        });

        Schema::table('kehadiran', function (Blueprint $table) {
            $table->dropColumn('sesi');
        });

        Schema::table('kehadiran', function (Blueprint $table) {
            $table->unique(['santri_id', 'tanggal', 'sesi_kehadiran_id'], 'unique_absensi_v2');
        });
    }

    public function down(): void
    {
        Schema::table('kehadiran', function (Blueprint $table) {
            $table->dropUnique('unique_absensi_v2');
        });

        Schema::table('kehadiran', function (Blueprint $table) {
            $table->enum('sesi', ['pagi', 'siang', 'malam'])->default('pagi')->after('tanggal');
        });

        // Migrate data back
        $sesiRows = DB::table('sesi_kehadiran')->pluck('nama_sesi', 'id');
        foreach ($sesiRows as $id => $name) {
            DB::table('kehadiran')
                ->where('sesi_kehadiran_id', $id)
                ->update(['sesi' => strtolower($name)]);
        }

        Schema::table('kehadiran', function (Blueprint $table) {
            $table->dropForeign(['sesi_kehadiran_id']);
            $table->dropColumn('sesi_kehadiran_id');
            $table->unique(['santri_id', 'tanggal', 'sesi'], 'unique_absensi');
        });
    }
};
