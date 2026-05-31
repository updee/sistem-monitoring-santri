<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Izin;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    public function index()
    {
        $status = request('status', 'semua');
        $bulan  = request('bulan');
        $tahun  = request('tahun');

        $query = Izin::with(['santri.kelas', 'pengaju', 'approver'])->latest();
        
        if (in_array($status, ['menunggu', 'disetujui', 'ditolak'], true)) {
            $query->where('status', $status);
        }

        if ($bulan) {
            $query->whereMonth('tanggal_mulai', (int) $bulan);
        }
        
        if ($tahun) {
            $query->whereYear('tanggal_mulai', (int) $tahun);
        }

        $izinList = $query->paginate(15)->withQueryString();
        $jumlahMenunggu = Izin::where('status', 'menunggu')->count();

        return view('admin.izin.index', compact('izinList', 'jumlahMenunggu', 'status', 'bulan', 'tahun'));
    }

    public function show(Izin $izin)
    {
        if (! view()->exists('admin.izin.show')) {
            // minimal fallback
            return redirect()->route('admin.izin.index');
        }
        $izin->load(['santri', 'pengaju', 'approver']);
        return view('admin.izin.show', compact('izin'));
    }

    public function setujui(Request $request, Izin $izin)
    {
        $izin->update([
            'status'        => 'disetujui',
            'approver_id'   => $request->user()->id,
            'catatan_admin' => $request->input('catatan_admin'),
            'diproses_pada' => now(),
        ]);

        return redirect()->back()->with('success', 'Izin berhasil disetujui.');
    }

    public function tolak(Request $request, Izin $izin)
    {
        $izin->update([
            'status'        => 'ditolak',
            'approver_id'   => $request->user()->id,
            'catatan_admin' => $request->input('catatan_admin'),
            'diproses_pada' => now(),
        ]);

        return redirect()->back()->with('success', 'Izin berhasil ditolak.');
    }
}

