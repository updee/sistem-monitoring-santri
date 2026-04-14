<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use App\Models\Hafalan;
use App\Models\Kehadiran;
use App\Models\Santri;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = request()->user();
        $kelas = $user->kelas()->first();

        $santriQuery = Santri::aktif();
        if ($kelas) {
            $santriQuery->where('kelas_id', $kelas->id);
        }

        $totalSantri = $santriQuery->count();

        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $kehadiranBaseQuery = Kehadiran::query()
            ->whereDate('tanggal', $today)
            // Prioritaskan data yang benar-benar diinput ustadz login.
            ->where(function ($q) use ($user, $kelas) {
                $q->where('ustadz_id', $user->id);
                if ($kelas) {
                    $q->orWhereHas('santri', fn($sq) => $sq->where('kelas_id', $kelas->id));
                }
            });

        $kehadiranHariIni = (clone $kehadiranBaseQuery)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $bulan = now()->month;
        $tahun = now()->year;
        $setoranBulanIni = Hafalan::where('ustadz_id', $user->id)
            ->whereMonth('tanggal_setoran', $bulan)
            ->whereYear('tanggal_setoran', $tahun)
            ->count();

        $totalHalamanBulanIni = Hafalan::where('ustadz_id', $user->id)
            ->whereMonth('tanggal_setoran', $bulan)
            ->whereYear('tanggal_setoran', $tahun)
            ->sum('jumlah_halaman');

        $hafalanTerbaru = Hafalan::with('santri')
            ->where('ustadz_id', $user->id)
            ->latest('tanggal_setoran')
            ->take(6)
            ->get();

        $progressSantri = $santriQuery
            ->withSum('hafalan', 'jumlah_halaman')
            ->withAvg('hafalan', 'nilai')
            ->take(12)
            ->get();

        $santriAlphaHariIni = (clone $kehadiranBaseQuery)
            ->with('santri')
            ->where('status', 'alpha')
            ->take(10)
            ->get();

        return view('ustadz.dashboard', compact(
            'totalSantri',
            'kehadiranHariIni',
            'setoranBulanIni',
            'totalHalamanBulanIni',
            'hafalanTerbaru',
            'progressSantri',
            'santriAlphaHariIni',
        ));
    }
}

