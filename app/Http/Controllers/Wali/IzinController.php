<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Izin;
use App\Models\Santri;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    protected function getSantri(Request $request): Santri
    {
        $santri = Santri::where('wali_id', $request->user()->id)->first();
        abort_if(! $santri, 403, 'Akun Anda belum dihubungkan ke data santri.');
        return $santri;
    }

    public function index(Request $request)
    {
        if (! view()->exists('wali.izin.index')) {
            abort(501, 'View wali.izin.index belum tersedia.');
        }

        $santri = $this->getSantri($request);
        $izinList = Izin::where('santri_id', $santri->id)->latest()->paginate(15);

        return view('wali.izin.index', compact('santri', 'izinList'));
    }

    public function create(Request $request)
    {
        $santri = $this->getSantri($request);
        return view('wali.izin.create', compact('santri'));
    }

    public function store(Request $request)
    {
        $santri = $this->getSantri($request);

        $data = $request->validate([
            'tanggal_mulai'        => ['required', 'date'],
            'tanggal_kembali'      => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'alasan'               => ['required', 'string'],
            'nama_penjemput'       => ['nullable', 'string', 'max:255'],
            'no_telepon_penjemput' => ['nullable', 'string', 'max:20'],
        ]);

        Izin::create(array_merge($data, [
            'santri_id'  => $santri->id,
            'pengaju_id' => $request->user()->id,
            'status'     => 'menunggu',
        ]));

        return redirect()->route('wali_santri.izin.index')->with('success', 'Pengajuan izin berhasil dikirim.');
    }

    public function show(Request $request, Izin $izin)
    {
        if (! view()->exists('wali.izin.show')) {
            abort(501, 'View wali.izin.show belum tersedia.');
        }

        $santri = $this->getSantri($request);
        abort_if($izin->santri_id !== $santri->id, 403);

        return view('wali.izin.show', compact('santri', 'izin'));
    }
}

