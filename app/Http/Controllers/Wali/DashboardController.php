<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Izin;
use App\Models\Pencapaian;
use App\Models\Santri;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $santri = Santri::with(['kelas', 'kamar'])
            ->where('wali_id', $user->id)
            ->first();

        $targetHalamanHafalan = max(1, (int) config('hafalan.target_halaman', 604));

        if (! $santri) {
            return view('wali.dashboard', [
                'santri' => null,
                'totalHalaman' => 0,
                'persenProgressHafalan' => 0.0,
                'targetHalamanHafalan' => $targetHalamanHafalan,
                'setoranBulanIni' => 0,
                'persenKehadiran' => 0,
                'totalPoin' => 0,
                'totalPrestasi' => 0,
                'hafalanTerbaru' => collect(),
                'izinAktif' => null,
                'izinTerakhir' => collect(),
                'pencapaianTerbaru' => collect(),
                'pelanggaranTerbaru' => collect(),
            ]);
        }

        $totalHalaman = (int) $santri->total_halaman_hafalan;
        $persenProgressHafalan = min(100.0, round(($totalHalaman / $targetHalamanHafalan) * 100, 1));
        $setoranBulanIni = (int) $santri->hafalan()
            ->whereMonth('tanggal_setoran', now()->month)
            ->whereYear('tanggal_setoran', now()->year)
            ->count();

        $persenKehadiran = (float) $santri->persentase_kehadiran_bulan_ini;
        $totalPoin = (int) $santri->total_poin_pelanggaran;
        $totalPrestasi = (int) $santri->pencapaian()->count();

        $hafalanTerbaru = $santri->hafalan()->latest('tanggal_setoran')->take(5)->get();
        $izinAktif = $santri->izin()->where('status', 'disetujui')->whereDate('tanggal_kembali', '>=', now())->latest()->first();
        $izinTerakhir = $santri->izin()->latest()->take(5)->get();
        $pencapaianTerbaru = $santri->pencapaian()->latest('tanggal')->take(5)->get();
        $pelanggaranTerbaru = $santri->pelanggaran()->with('kategori')->latest('tanggal')->take(5)->get();

        return view('wali.dashboard', compact(
            'santri',
            'totalHalaman',
            'persenProgressHafalan',
            'targetHalamanHafalan',
            'setoranBulanIni',
            'persenKehadiran',
            'totalPoin',
            'totalPrestasi',
            'hafalanTerbaru',
            'izinAktif',
            'izinTerakhir',
            'pencapaianTerbaru',
            'pelanggaranTerbaru',
        ));
    }
}

