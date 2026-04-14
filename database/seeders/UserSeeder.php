<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ─────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@ibs-ashiddiqi.sch.id'],
            [
                'name'       => 'Administrator',
                'password'   => Hash::make('admin123'),
                'role'       => 'admin',
                'no_telepon' => '08112345678',
                'is_active'  => true,
            ]
        );

        // ── Ustadz ────────────────────────────
        $ustadzList = [
            ['name' => 'Ust. Ahmad Fauzi, S.Pd.I', 'email' => 'ahmad.fauzi@ibs-ashiddiqi.sch.id'],
            ['name' => 'Ust. Muhammad Ridwan',     'email' => 'muhammad.ridwan@ibs-ashiddiqi.sch.id'],
            ['name' => 'Ust. Abdurrahman Wahid',   'email' => 'abdurrahman.wahid@ibs-ashiddiqi.sch.id'],
            ['name' => 'Ust. Syaiful Anwar, Lc.',  'email' => 'syaiful.anwar@ibs-ashiddiqi.sch.id'],
            ['name' => 'Ust. Hasan Basri, M.Pd.I', 'email' => 'hasan.basri@ibs-ashiddiqi.sch.id'],
        ];

        foreach ($ustadzList as $ustadz) {
            User::updateOrCreate(
                ['email' => $ustadz['email']],
                [
                    'name'      => $ustadz['name'],
                    'password'  => Hash::make('ustadz123'),
                    'role'      => 'ustadz',
                    'is_active' => true,
                ]
            );
        }

        // ── Wali Santri ───────────────────────
        $waliList = [
            ['name' => 'Bapak Hendra Gunawan',   'email' => 'hendra.gunawan@gmail.com'],
            ['name' => 'Ibu Siti Rahayu',        'email' => 'siti.rahayu@gmail.com'],
            ['name' => 'Bapak Agus Salim',       'email' => 'agus.salim@gmail.com'],
            ['name' => 'Bapak Dedi Kurniawan',   'email' => 'dedi.kurniawan@gmail.com'],
            ['name' => 'Ibu Nur Azizah',         'email' => 'nur.azizah@gmail.com'],
            ['name' => 'Bapak Rudi Hartono',     'email' => 'rudi.hartono@gmail.com'],
            ['name' => 'Ibu Dewi Lestari',       'email' => 'dewi.lestari@gmail.com'],
            ['name' => 'Bapak Eko Prasetyo',     'email' => 'eko.prasetyo@gmail.com'],
            ['name' => 'Ibu Fatimah Zahra',      'email' => 'fatimah.zahra@gmail.com'],
            ['name' => 'Bapak Bambang Susilo',   'email' => 'bambang.susilo@gmail.com'],
            ['name' => 'Ibu Mariana Putri',      'email' => 'mariana.putri@gmail.com'],
            ['name' => 'Bapak Supriadi',         'email' => 'supriadi@gmail.com'],
            ['name' => 'Ibu Halimah Tusa\'diyah','email' => 'halimah.tusadiyah@gmail.com'],
            ['name' => 'Bapak Zulkifli',         'email' => 'zulkifli@gmail.com'],
            ['name' => 'Ibu Sri Wahyuni',        'email' => 'sri.wahyuni@gmail.com'],
        ];

        foreach ($waliList as $wali) {
            User::updateOrCreate(
                ['email' => $wali['email']],
                [
                    'name'      => $wali['name'],
                    'password'  => Hash::make('wali123'),
                    'role'      => 'wali_santri',
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('✓ Users seeded: 1 admin, 5 ustadz, 15 wali santri');
    }
}