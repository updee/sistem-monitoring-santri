<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    protected function getSantri(Request $request): ?Santri
    {
        return Santri::where('wali_id', $request->user()->id)->first();
    }

    public function hafalan(Request $request)
    {
        if (! view()->exists('wali.monitoring.hafalan')) {
            abort(501, 'View wali.monitoring.hafalan belum tersedia.');
        }
        $santri = $this->getSantri($request);
        abort_if(! $santri, 403, 'Akun Anda belum dihubungkan ke data santri.');
        $hafalan = $santri->hafalan()->with('ustadz')->paginate(20);
        $totalHalaman = (int) $santri->total_halaman_hafalan;
        $targetHalamanHafalan = max(1, (int) config('hafalan.target_halaman', 604));
        $persenProgressHafalan = min(100.0, round(($totalHalaman / $targetHalamanHafalan) * 100, 1));
        $rataRataNilai = (float) ($santri->hafalan()->whereNotNull('nilai')->avg('nilai') ?? 0);
        $totalSetoran = (int) $santri->hafalan()->count();
        $totalMurojaah = (int) $santri->hafalan()->where('jenis', 'murojaah')->count();
        return view('wali.monitoring.hafalan', compact(
            'santri',
            'hafalan',
            'totalHalaman',
            'persenProgressHafalan',
            'targetHalamanHafalan',
            'rataRataNilai',
            'totalSetoran',
            'totalMurojaah',
        ));
    }

    public function kehadiran(Request $request)
    {
        if (! view()->exists('wali.monitoring.kehadiran')) {
            abort(501, 'View wali.monitoring.kehadiran belum tersedia.');
        }
        $santri = $this->getSantri($request);
        abort_if(! $santri, 403, 'Akun Anda belum dihubungkan ke data santri.');
        $periode = $request->get('periode', now()->format('Y-m'));
        [$tahun, $bulan] = array_map('intval', explode('-', $periode));
        $kehadiran = $santri->kehadiran()
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal', 'desc')
            ->get();
        $rekap = [
            'hadir' => $kehadiran->where('status', 'hadir')->count(),
            'izin' => $kehadiran->where('status', 'izin')->count(),
            'sakit' => $kehadiran->where('status', 'sakit')->count(),
            'alpha' => $kehadiran->where('status', 'alpha')->count(),
        ];
        return view('wali.monitoring.kehadiran', compact('santri', 'kehadiran', 'rekap', 'periode'));
    }

    public function pelanggaran(Request $request)
    {
        $santri = $this->getSantri($request);
        abort_if(! $santri, 403, 'Akun Anda belum dihubungkan ke data santri.');
        $pelanggaran = $santri->pelanggaran()->with('kategori')->paginate(20);
        $totalPoin = (int) $santri->pelanggaran()->sum('poin_sanksi');
        return view('wali.monitoring.pelanggaran', compact('santri', 'pelanggaran', 'totalPoin'));
    }

    public function pencapaian(Request $request)
    {
        $santri = $this->getSantri($request);
        abort_if(! $santri, 403, 'Akun Anda belum dihubungkan ke data santri.');
        $pencapaian = $santri->pencapaian()->paginate(20);
        return view('wali.monitoring.pencapaian', compact('santri', 'pencapaian'));
    }
}

