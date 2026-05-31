<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use App\Models\KategoriPelanggaran;
use App\Models\Pelanggaran;
use App\Models\Santri;
use App\Models\SuratPanggilan;
use Illuminate\Http\Request;

class PelanggaranController extends Controller
{
    public function index(Request $request)
    {
        $pelanggaran = Pelanggaran::with(['santri.kelas', 'kategori'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = $request->search;
                $q->whereHas('santri', fn($s) => $s->where('nama', 'like', '%' . $term . '%'));
            })
            ->when($request->filled('tingkat'), fn($q) => $q->whereHas('kategori', fn($k) => $k->where('tingkat', $request->tingkat)))
            ->when($request->filled('bulan'), function ($q) use ($request) {
                [$y, $m] = explode('-', $request->bulan);
                $q->whereYear('tanggal', (int) $y)->whereMonth('tanggal', (int) $m);
            })
            ->latest('tanggal')
            ->paginate(20)
            ->withQueryString();

        return view('ustadz.pelanggaran.index', compact('pelanggaran'));
    }

    public function create()
    {
        $santriList = Santri::aktif()->with('kelas')->orderBy('nama')->get();
        $kategoriList = KategoriPelanggaran::where('is_active', true)
            ->orderBy('tingkat')
            ->orderBy('nama_kategori')
            ->get();
        return view('ustadz.pelanggaran.create', compact('santriList', 'kategoriList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'santri_id'            => ['required', 'exists:santri,id'],
            'kategori_id'          => ['required', 'exists:kategori_pelanggaran,id'],
            'jenis_pelanggaran'    => ['required', 'string', 'max:255'],
            'poin_sanksi'          => ['required', 'integer', 'min:1', 'max:200'],
            'tanggal'              => ['required', 'date', 'before_or_equal:today'],
            'keterangan'           => ['nullable', 'string'],
            'status_tindak_lanjut' => ['required', 'in:belum,sudah'],
            'tindak_lanjut'        => ['nullable', 'string'],
        ]);

        $data['pencatat_id'] = $request->user()->id;

        Pelanggaran::create($data);
        return redirect()->route('ustadz.pelanggaran.index')->with('success', 'Pelanggaran berhasil dicatat.');
    }

    public function show(Pelanggaran $pelanggaran)
    {
        $pelanggaran->load(['santri.kelas', 'kategori', 'pencatat']);
        return view('ustadz.pelanggaran.show', compact('pelanggaran'));
    }

    public function edit(Pelanggaran $pelanggaran)
    {
        $santriList = Santri::aktif()->with('kelas')->orderBy('nama')->get();
        $kategoriList = KategoriPelanggaran::where('is_active', true)
            ->orderBy('tingkat')
            ->orderBy('nama_kategori')
            ->get();
        return view('ustadz.pelanggaran.edit', compact('pelanggaran', 'santriList', 'kategoriList'));
    }

    public function update(Request $request, Pelanggaran $pelanggaran)
    {
        $data = $request->validate([
            'santri_id'            => ['required', 'exists:santri,id'],
            'kategori_id'          => ['required', 'exists:kategori_pelanggaran,id'],
            'jenis_pelanggaran'    => ['required', 'string', 'max:255'],
            'poin_sanksi'          => ['required', 'integer', 'min:1', 'max:200'],
            'tanggal'              => ['required', 'date', 'before_or_equal:today'],
            'keterangan'           => ['nullable', 'string'],
            'status_tindak_lanjut' => ['required', 'in:belum,sudah'],
            'tindak_lanjut'        => ['nullable', 'string'],
        ]);

        $pelanggaran->update($data);
        return redirect()->route('ustadz.pelanggaran.index')->with('success', 'Pelanggaran berhasil diperbarui.');
    }

    public function destroy(Pelanggaran $pelanggaran)
    {
        $pelanggaran->delete();
        return redirect()->route('ustadz.pelanggaran.index')->with('success', 'Data pelanggaran berhasil dihapus.');
    }

    public function printSp(SuratPanggilan $suratPanggilan)
    {
        $suratPanggilan->load('santri.kelas');
        return view('print-sp', compact('suratPanggilan'));
    }
}
