<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Santri;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KehadiranController extends Controller
{
    /**
     * Ustadz hanya boleh mengelola absensi untuk kelas yang diampunya,
     * atau kelas yang belum ditetapkan walikelas (ustadz_id null).
     */
    protected function authorizeKelasForUstadz(int $kelasId): Kelas
    {
        $kelas = Kelas::where('id', $kelasId)->where('is_active', true)->firstOrFail();

        if ($kelas->ustadz_id !== null && (int) $kelas->ustadz_id !== (int) Auth::id()) {
            abort(403, 'Anda tidak berhak menginput kehadiran untuk kelas ini.');
        }

        return $kelas;
    }

    protected function authorizeKehadiranForUstadz(Kehadiran $kehadiran): void
    {
        $kehadiran->loadMissing('santri.kelas');
        $kelas = $kehadiran->santri?->kelas;

        if (! $kelas) {
            abort(403, 'Data kehadiran tidak terkait kelas yang valid.');
        }

        if ($kelas->ustadz_id !== null && (int) $kelas->ustadz_id !== (int) Auth::id()) {
            abort(403, 'Anda tidak berhak mengubah kehadiran untuk santri di kelas ini.');
        }
    }

    public function index()
    {
        $rekap = Santri::aktif()
            ->with('kelas')
            ->withCount([
                'kehadiran as total_hadir' => fn($q) => $q->where('status', 'hadir')
                    ->whereMonth('tanggal', now()->month),
                'kehadiran as total_alpha' => fn($q) => $q->where('status', 'alpha')
                    ->whereMonth('tanggal', now()->month),
            ])
            ->orderBy('nama')
            ->paginate(15);

        return view('ustadz.kehadiran.index', compact('rekap'));
    }

    /** Form input absensi harian (bulk) */
    public function input(Request $request)
    {
        $tanggal   = $request->get('tanggal', Carbon::today()->toDateString());
        $sesi      = $request->get('sesi', 'pagi');
        $kelasId   = $request->get('kelas_id');
        $kelasList = Kelas::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('ustadz_id')
                    ->orWhere('ustadz_id', Auth::id());
            })
            ->orderBy('nama_kelas')
            ->get();

        $santriList = collect();
        if ($kelasId) {
            $this->authorizeKelasForUstadz((int) $kelasId);
            $santriList = Santri::aktif()
                ->where('kelas_id', $kelasId)
                ->orderBy('nama')
                ->get()
                ->map(function ($santri) use ($tanggal, $sesi) {
                    $existing = Kehadiran::where('santri_id', $santri->id)
                        ->where('tanggal', $tanggal)
                        ->where('sesi', $sesi)
                        ->first();
                    $santri->status_absen = $existing?->status ?? 'hadir';
                    $santri->keterangan   = $existing?->keterangan ?? '';
                    $santri->sudah_absen  = (bool) $existing;
                    return $santri;
                });
        }

        return view('ustadz.kehadiran.input', compact(
            'tanggal', 'sesi', 'kelasId', 'kelasList', 'santriList'
        ));
    }

    /** Simpan absensi massal */
    public function storeBulk(Request $request)
    {
        $request->validate([
            'tanggal'          => ['required', 'date', 'before_or_equal:today'],
            'sesi'             => ['required', 'in:pagi,siang,malam'],
            'kelas_id'         => ['required', 'exists:kelas,id'],
            'absensi'          => ['required', 'array'],
            'absensi.*.status' => ['required', 'in:hadir,izin,sakit,alpha'],
        ]);

        $kelasId = (int) $request->kelas_id;
        $this->authorizeKelasForUstadz($kelasId);

        $allowedSantriIds = Santri::aktif()
            ->where('kelas_id', $kelasId)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $submittedIds = collect($request->absensi)
            ->keys()
            ->map(fn ($id) => (int) $id)
            ->all();

        $invalidIds = array_diff($submittedIds, $allowedSantriIds);
        if ($invalidIds !== []) {
            return back()
                ->withErrors(['absensi' => 'Daftar santri tidak sesuai kelas yang dipilih.'])
                ->withInput();
        }

        $tanggal   = $request->tanggal;
        $sesi      = $request->sesi;
        $ustadzId  = Auth::id();

        DB::transaction(function () use ($request, $tanggal, $sesi, $ustadzId) {
            foreach ($request->absensi as $santriId => $data) {
                Kehadiran::updateOrCreate(
                    [
                        'santri_id' => $santriId,
                        'tanggal'   => $tanggal,
                        'sesi'      => $sesi,
                    ],
                    [
                        'ustadz_id'  => $ustadzId,
                        'status'     => $data['status'],
                        'keterangan' => $data['keterangan'] ?? null,
                    ]
                );
            }
        });

        return redirect()->route('ustadz.kehadiran.input', [
                'tanggal'  => $tanggal,
                'sesi'     => $sesi,
                'kelas_id' => $request->kelas_id,
            ])
            ->with('success', 'Absensi berhasil disimpan untuk ' . count($request->absensi) . ' santri.');
    }

    /** Rekap kehadiran per santri per bulan */
    public function rekap(Request $request)
    {
        $bulan  = $request->get('bulan', now()->month);
        $tahun  = $request->get('tahun', now()->year);

        $rekap = Santri::aktif()
            ->with('kelas')
            ->get()
            ->map(function ($santri) use ($bulan, $tahun) {
                $data = Kehadiran::where('santri_id', $santri->id)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->selectRaw("status, COUNT(*) as total")
                    ->groupBy('status')
                    ->pluck('total', 'status')
                    ->toArray();

                $santri->hadir  = $data['hadir']  ?? 0;
                $santri->izin   = $data['izin']   ?? 0;
                $santri->sakit  = $data['sakit']  ?? 0;
                $santri->alpha  = $data['alpha']  ?? 0;
                $santri->total  = array_sum($data);
                $santri->persen = $santri->total > 0
                    ? round(($santri->hadir / $santri->total) * 100, 1) : 0;
                return $santri;
            });

        return view('ustadz.kehadiran.rekap', compact('rekap', 'bulan', 'tahun'));
    }

    public function edit($id)
    {
        $kehadiran = Kehadiran::findOrFail($id);
        $this->authorizeKehadiranForUstadz($kehadiran);
        return view('ustadz.kehadiran.edit', compact('kehadiran'));
    }

    public function update(Request $request, $id)
    {
        $kehadiran = Kehadiran::findOrFail($id);
        $this->authorizeKehadiranForUstadz($kehadiran);
        $request->validate([
            'status'     => ['required', 'in:hadir,izin,sakit,alpha'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        $kehadiran->update($request->only('status', 'keterangan'));

        return redirect()->route('ustadz.kehadiran.index')
            ->with('success', 'Data kehadiran berhasil diperbarui.');
    }
}
