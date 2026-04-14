<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\Hafalan;
use App\Models\Kehadiran;
use App\Models\Pelanggaran;
use App\Models\Izin;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $bulan = now()->month;
        $tahun = now()->year;

        // ── Statistik Utama ──────────────────────────────────────────────────
        $stats = [
            'total_santri'      => Santri::aktif()->count(),
            'total_ustadz'      => User::where('role', 'ustadz')->where('is_active', true)->count(),
            'total_wali'        => User::where('role', 'wali_santri')->where('is_active', true)->count(),
            'izin_menunggu'     => Izin::menunggu()->count(),
        ];

        // ── Kehadiran Hari Ini ───────────────────────────────────────────────
        $today        = Carbon::today()->toDateString();
        $kehadiranHariIni = Kehadiran::whereDate('tanggal', $today)
            ->selectRaw("status, COUNT(*) as total")
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // ── Grafik Kehadiran 7 Hari Terakhir ────────────────────────────────
        $grafikKehadiran = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl  = Carbon::now()->subDays($i);
            $hadir = Kehadiran::whereDate('tanggal', $tgl->toDateString())
                ->where('status', 'hadir')->count();
            $total = Kehadiran::whereDate('tanggal', $tgl->toDateString())->count();
            $grafikKehadiran[] = [
                'label'  => $tgl->format('d M'),
                'hadir'  => $hadir,
                'total'  => $total,
            ];
        }

        // ── Hafalan Terbaru ──────────────────────────────────────────────────
        $hafalanTerbaru = Hafalan::with(['santri', 'ustadz'])
            ->latest('tanggal_setoran')
            ->take(5)
            ->get();

        // ── Pelanggaran Bulan Ini ────────────────────────────────────────────
        $pelanggaranBulanIni = Pelanggaran::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->count();

        // ── Santri dengan poin pelanggaran tertinggi ─────────────────────────
        $santriRawan = Santri::aktif()
            ->withSum('pelanggaran', 'poin_sanksi')
            ->orderByDesc('pelanggaran_sum_poin_sanksi')
            ->take(5)
            ->get();

        // ── Izin Terbaru menunggu ────────────────────────────────────────────
        $izinMenunggu = Izin::with(['santri', 'pengaju'])
            ->menunggu()
            ->latest()
            ->take(5)
            ->get();

        // ── Progress Hafalan per Kelas ───────────────────────────────────────
        $progressHafalan = Santri::aktif()
            ->with('kelas')
            ->withSum('hafalan', 'jumlah_halaman')
            ->get()
            ->groupBy('kelas.nama_kelas')
            ->map(fn($santri) => round($santri->avg('hafalan_sum_jumlah_halaman'), 1));

        return view('admin.dashboard', compact(
            'stats',
            'kehadiranHariIni',
            'grafikKehadiran',
            'hafalanTerbaru',
            'pelanggaranBulanIni',
            'santriRawan',
            'izinMenunggu',
            'progressHafalan',
        ));
    }
}
