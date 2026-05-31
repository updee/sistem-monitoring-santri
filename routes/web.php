<?php
// ══════════════════════════════════════════════════════════════════════════
// routes/web.php
//
// PENTING: File ini HARUS menggantikan routes/web.php yang ada
// Jangan pakai Auth::routes() karena akan override ke view default Laravel UI
// ══════════════════════════════════════════════════════════════════════════

use Illuminate\Support\Facades\Route;

// Controllers Auth
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Controllers Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\SantriController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\KamarController;
use App\Http\Controllers\Admin\KategoriPelanggaranController;
use App\Http\Controllers\Admin\SesiKehadiranController;
use App\Http\Controllers\Admin\IzinController as AdminIzinController;
use App\Http\Controllers\Admin\LaporanController;

// Controllers Ustadz
use App\Http\Controllers\Ustadz\DashboardController as UstadzDashboard;
use App\Http\Controllers\Ustadz\HafalanController;
use App\Http\Controllers\Ustadz\KehadiranController;
use App\Http\Controllers\Ustadz\PelanggaranController;
use App\Http\Controllers\Ustadz\PencapaianController;
use App\Http\Controllers\Ustadz\IzinController as UstadzIzinController;

// Controllers Wali
use App\Http\Controllers\Wali\DashboardController as WaliDashboard;
use App\Http\Controllers\Wali\MonitoringController;
use App\Http\Controllers\Wali\IzinController as WaliIzinController;

// ── Root redirect ─────────────────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        return match(auth()->user()->role) {
            'admin'       => redirect()->route('admin.dashboard'),
            'ustadz'      => redirect()->route('ustadz.dashboard'),
            'wali_santri' => redirect()->route('wali_santri.dashboard'),
            default       => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});

// ════════════════════════════════════════════════════════════════════════════
// AUTH ROUTES — KUSTOM (tidak pakai Auth::routes())
// ════════════════════════════════════════════════════════════════════════════
Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    // Register (hanya untuk Wali Santri)
    Route::get('/register',  [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

// Logout
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ════════════════════════════════════════════════════════════════════════════
// ADMIN ROUTES
// ════════════════════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Santri
    Route::get('santri/import/template', [SantriController::class, 'downloadTemplate'])->name('santri.import.template');
    Route::post('santri/import', [SantriController::class, 'import'])->name('santri.import');
    Route::resource('santri', SantriController::class);
    Route::get('santri/{santri}/rekap', [SantriController::class, 'rekap'])->name('santri.rekap');

    // Users
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');

    // Master data
    Route::resource('kelas',  KelasController::class);
    Route::resource('kamar',  KamarController::class);
    Route::resource('kategori-pelanggaran', KategoriPelanggaranController::class);
    Route::resource('sesi-kehadiran', SesiKehadiranController::class)->except(['show']);

    // Izin
    Route::get('izin',                  [AdminIzinController::class, 'index'])->name('izin.index');
    Route::get('izin/{izin}',           [AdminIzinController::class, 'show'])->name('izin.show');
    Route::patch('izin/{izin}/setujui', [AdminIzinController::class, 'setujui'])->name('izin.setujui');
    Route::patch('izin/{izin}/tolak',   [AdminIzinController::class, 'tolak'])->name('izin.tolak');

    // Laporan & Ekspor
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/',           [LaporanController::class, 'index'])->name('index');
        Route::get('hafalan',     [LaporanController::class, 'hafalan'])->name('hafalan');
        Route::get('kehadiran',   [LaporanController::class, 'kehadiran'])->name('kehadiran');
        Route::get('pelanggaran', [LaporanController::class, 'pelanggaran'])->name('pelanggaran');
        Route::get('pencapaian',  [LaporanController::class, 'pencapaian'])->name('pencapaian');
        Route::get('izin',        [LaporanController::class, 'izin'])->name('izin');

        Route::get('export/santri',      [LaporanController::class, 'exportSantri'])->name('export.santri');
        Route::get('export/hafalan',     [LaporanController::class, 'exportHafalan'])->name('export.hafalan');
        Route::get('export/kehadiran',   [LaporanController::class, 'exportKehadiran'])->name('export.kehadiran');
        Route::get('export/pelanggaran', [LaporanController::class, 'exportPelanggaran'])->name('export.pelanggaran');
        Route::get('export/pencapaian',  [LaporanController::class, 'exportPencapaian'])->name('export.pencapaian');
        Route::get('export/izin',        [LaporanController::class, 'exportIzin'])->name('export.izin');
    });

    Route::get('pelanggaran/print-sp/{suratPanggilan}', [LaporanController::class, 'printSp'])->name('pelanggaran.print-sp');
});

// ════════════════════════════════════════════════════════════════════════════
// USTADZ ROUTES
// ════════════════════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:ustadz'])
    ->prefix('ustadz')
    ->name('ustadz.')
    ->group(function () {

    Route::get('/dashboard', [UstadzDashboard::class, 'index'])->name('dashboard');

    // Hafalan
    Route::resource('hafalan', HafalanController::class);
    Route::get('hafalan-santri/{santri}', [HafalanController::class, 'bySantri'])->name('hafalan.by-santri');

    // Kehadiran
    Route::get('kehadiran',              [KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::get('kehadiran/input',        [KehadiranController::class, 'input'])->name('kehadiran.input');
    Route::post('kehadiran/store-bulk',  [KehadiranController::class, 'storeBulk'])->name('kehadiran.store-bulk');
    Route::get('kehadiran/rekap',        [KehadiranController::class, 'rekap'])->name('kehadiran.rekap');
    Route::get('kehadiran/{id}/edit',    [KehadiranController::class, 'edit'])->name('kehadiran.edit');
    Route::patch('kehadiran/{id}',       [KehadiranController::class, 'update'])->name('kehadiran.update');

    // Pelanggaran
    Route::resource('pelanggaran', PelanggaranController::class);
    Route::get('pelanggaran/print-sp/{suratPanggilan}', [PelanggaranController::class, 'printSp'])->name('pelanggaran.print-sp');

    // Pencapaian
    Route::resource('pencapaian', PencapaianController::class);

    // Izin (hanya lihat)
    Route::get('izin',       [UstadzIzinController::class, 'index'])->name('izin.index');
    Route::get('izin/{izin}',[UstadzIzinController::class, 'show'])->name('izin.show');
});

// ════════════════════════════════════════════════════════════════════════════
// WALI SANTRI ROUTES
// ════════════════════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:wali_santri'])
    ->prefix('wali')
    ->name('wali_santri.')
    ->group(function () {

    Route::get('/dashboard', [WaliDashboard::class, 'index'])->name('dashboard');

    // Monitoring
    Route::get('hafalan',     [MonitoringController::class, 'hafalan'])->name('hafalan');
    Route::get('kehadiran',   [MonitoringController::class, 'kehadiran'])->name('kehadiran');
    Route::get('pelanggaran', [MonitoringController::class, 'pelanggaran'])->name('pelanggaran');
    Route::get('pencapaian',  [MonitoringController::class, 'pencapaian'])->name('pencapaian');

    // Izin
    Route::get('izin',          [WaliIzinController::class, 'index'])->name('izin.index');
    Route::get('izin/create',   [WaliIzinController::class, 'create'])->name('izin.create');
    Route::post('izin',         [WaliIzinController::class, 'store'])->name('izin.store');
    Route::get('izin/{izin}',   [WaliIzinController::class, 'show'])->name('izin.show');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->middleware('auth')
    ->name('home');
