<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use App\Models\Izin;

class IzinController extends Controller
{
    public function index()
    {
        $izinAktif = Izin::with(['santri.kelas', 'pengaju'])
            ->where('status', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_kembali', '>=', now())
            ->latest('tanggal_mulai')
            ->get();
        return view('ustadz.izin.index', compact('izinAktif'));
    }

    public function show(Izin $izin)
    {
        if (! view()->exists('ustadz.izin.show')) {
            abort(501, 'View ustadz.izin.show belum tersedia.');
        }
        $izin->load(['santri', 'pengaju', 'approver']);
        return view('ustadz.izin.show', compact('izin'));
    }
}

