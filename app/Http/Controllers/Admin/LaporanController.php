<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hafalan;
use App\Models\Kehadiran;
use App\Models\Pelanggaran;
use App\Models\Pencapaian;
use App\Models\Santri;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function hafalan(Request $request)
    {
        $data = Hafalan::with(['santri', 'ustadz'])
            ->when($request->filled('bulan'), fn($q) => $q->whereMonth('tanggal_setoran', (int)$request->bulan))
            ->when($request->filled('tahun'), fn($q) => $q->whereYear('tanggal_setoran', (int)$request->tahun))
            ->latest('tanggal_setoran')
            ->paginate(20)
            ->withQueryString();
        return view('admin.laporan.hafalan', compact('data'));
    }

    public function kehadiran(Request $request)
    {
        $bulan = (int) ($request->get('bulan', now()->month));
        $tahun = (int) ($request->get('tahun', now()->year));

        $rekap = Santri::with('kelas')
            ->orderBy('nama')
            ->get()
            ->map(function ($santri) use ($bulan, $tahun) {
                $data = Kehadiran::where('santri_id', $santri->id)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->selectRaw("status, COUNT(*) as total")
                    ->groupBy('status')
                    ->pluck('total', 'status')
                    ->toArray();
                $santri->hadir = $data['hadir'] ?? 0;
                $santri->izin = $data['izin'] ?? 0;
                $santri->sakit = $data['sakit'] ?? 0;
                $santri->alpha = $data['alpha'] ?? 0;
                $santri->total = array_sum($data);
                $santri->persen = $santri->total > 0 ? round(($santri->hadir / $santri->total) * 100, 1) : 0;
                return $santri;
            });

        return view('admin.laporan.kehadiran', compact('rekap', 'bulan', 'tahun'));
    }

    public function pelanggaran(Request $request)
    {
        $data = Pelanggaran::with(['santri', 'pencatat', 'kategori'])
            ->when($request->filled('bulan'), fn($q) => $q->whereMonth('tanggal', (int)$request->bulan))
            ->when($request->filled('tahun'), fn($q) => $q->whereYear('tanggal', (int)$request->tahun))
            ->latest('tanggal')
            ->paginate(20)
            ->withQueryString();
        return view('admin.laporan.pelanggaran', compact('data'));
    }

    public function pencapaian(Request $request)
    {
        $data = Pencapaian::with(['santri', 'pencatat'])
            ->when($request->filled('bulan'), fn($q) => $q->whereMonth('tanggal', (int)$request->bulan))
            ->when($request->filled('tahun'), fn($q) => $q->whereYear('tanggal', (int)$request->tahun))
            ->latest('tanggal')
            ->paginate(20)
            ->withQueryString();
        return view('admin.laporan.pencapaian', compact('data'));
    }

    public function exportSantri(): StreamedResponse
    {
        $rows = Santri::with(['kelas', 'kamar'])->orderBy('nama')->get();
        return $this->csvResponse('laporan_santri.csv', ['NIS', 'Nama', 'Kelas', 'Kamar', 'Status'], $rows->map(fn($s) => [
            $s->nis, $s->nama, $s->kelas?->nama_kelas, $s->kamar?->nama_kamar, $s->status,
        ])->toArray());
    }
    public function exportHafalan(): StreamedResponse
    {
        $rows = Hafalan::with('santri')->latest('tanggal_setoran')->get();
        return $this->csvResponse('laporan_hafalan.csv', ['Tanggal', 'Santri', 'Surat', 'Juz', 'Halaman', 'Nilai', 'Grade'], $rows->map(fn($r) => [
            optional($r->tanggal_setoran)->format('Y-m-d'),
            $r->santri?->nama, $r->nama_surat, $r->nomor_juz, $r->jumlah_halaman, $r->nilai, $r->grade,
        ])->toArray());
    }
    public function exportKehadiran(): StreamedResponse
    {
        $rows = Kehadiran::with('santri')->latest('tanggal')->get();
        return $this->csvResponse('laporan_kehadiran.csv', ['Tanggal', 'Santri', 'Sesi', 'Status', 'Keterangan'], $rows->map(fn($r) => [
            optional($r->tanggal)->format('Y-m-d'), $r->santri?->nama, $r->sesi, $r->status, $r->keterangan,
        ])->toArray());
    }
    public function exportPelanggaran(): StreamedResponse
    {
        $rows = Pelanggaran::with('santri')->latest('tanggal')->get();
        return $this->csvResponse('laporan_pelanggaran.csv', ['Tanggal', 'Santri', 'Jenis', 'Poin', 'Status Tindak Lanjut'], $rows->map(fn($r) => [
            optional($r->tanggal)->format('Y-m-d'), $r->santri?->nama, $r->jenis_pelanggaran, $r->poin_sanksi, $r->status_tindak_lanjut,
        ])->toArray());
    }

    private function csvResponse(string $filename, array $header, array $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($header, $rows) {
            $fh = fopen('php://output', 'w');
            fputcsv($fh, $header);
            foreach ($rows as $row) {
                fputcsv($fh, $row);
            }
            fclose($fh);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}

