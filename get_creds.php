<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$admin = App\Models\User::where('role', 'admin')->first();
$ustadz = App\Models\User::where('role', 'ustadz')->first();
$wali = App\Models\User::where('role', 'wali_santri')->first();

echo "Admin: " . ($admin->email ?? 'N/A') . "\n";
echo "Ustadz: " . ($ustadz->email ?? 'N/A') . "\n";
echo "Wali: " . ($wali->email ?? 'N/A') . "\n";
