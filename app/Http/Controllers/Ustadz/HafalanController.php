<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use App\Models\Hafalan;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HafalanController extends Controller
{
    public function index()
    {
        $hafalan = Hafalan::with(['santri', 'ustadz'])
            ->latest('tanggal_setoran')
            ->paginate(15);

        return view('ustadz.hafalan.index', compact('hafalan'));
    }

    public function create()
    {
        $santriList = Santri::aktif()->orderBy('nama')->get();
        return view('ustadz.hafalan.create', compact('santriList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'santri_id'       => ['required', 'exists:santri,id'],
            'nama_surat'      => ['required', 'string', 'max:100'],
            'nomor_juz'       => ['nullable', 'integer', 'min:1', 'max:30'],
            'halaman_dari'    => ['nullable', 'integer', 'min:1'],
            'halaman_sampai'  => ['nullable', 'integer', 'min:1', 'gte:halaman_dari'],
            'nilai'           => ['nullable', 'numeric', 'min:0', 'max:100'],
            'jenis'           => ['required', 'in:setoran_baru,murojaah'],
            'tanggal_setoran' => ['required', 'date', 'before_or_equal:today'],
            'catatan'         => ['nullable', 'string', 'max:500'],
        ], [
            'santri_id.required'       => 'Santri wajib dipilih.',
            'nama_surat.required'      => 'Nama surat wajib diisi.',
            'halaman_sampai.gte'       => 'Halaman sampai harus lebih besar dari halaman mulai.',
            'tanggal_setoran.required' => 'Tanggal setoran wajib diisi.',
            'tanggal_setoran.before_or_equal' => 'Tanggal setoran tidak boleh melebihi hari ini.',
        ]);

        $validated['ustadz_id'] = Auth::id();

        Hafalan::create($validated);

        return redirect()->route('ustadz.hafalan.index')
            ->with('success', 'Data setoran hafalan berhasil disimpan.');
    }

    public function show(Hafalan $hafalan)
    {
        $hafalan->load(['santri', 'ustadz']);
        return view('ustadz.hafalan.show', compact('hafalan'));
    }

    public function edit(Hafalan $hafalan)
    {
        $santriList = Santri::aktif()->orderBy('nama')->get();
        return view('ustadz.hafalan.edit', compact('hafalan', 'santriList'));
    }

    public function update(Request $request, Hafalan $hafalan)
    {
        $validated = $request->validate([
            'santri_id'       => ['required', 'exists:santri,id'],
            'nama_surat'      => ['required', 'string', 'max:100'],
            'nomor_juz'       => ['nullable', 'integer', 'min:1', 'max:30'],
            'halaman_dari'    => ['nullable', 'integer', 'min:1'],
            'halaman_sampai'  => ['nullable', 'integer', 'min:1', 'gte:halaman_dari'],
            'nilai'           => ['nullable', 'numeric', 'min:0', 'max:100'],
            'jenis'           => ['required', 'in:setoran_baru,murojaah'],
            'tanggal_setoran' => ['required', 'date', 'before_or_equal:today'],
            'catatan'         => ['nullable', 'string', 'max:500'],
        ]);

        $hafalan->update($validated);

        return redirect()->route('ustadz.hafalan.index')
            ->with('success', 'Data setoran hafalan berhasil diperbarui.');
    }

    public function destroy(Hafalan $hafalan)
    {
        $hafalan->delete();
        return redirect()->route('ustadz.hafalan.index')
            ->with('success', 'Data setoran hafalan berhasil dihapus.');
    }

    /** Tampilkan riwayat hafalan satu santri */
    public function bySantri(Santri $santri)
    {
        $hafalan = Hafalan::with('ustadz')
            ->where('santri_id', $santri->id)
            ->latest('tanggal_setoran')
            ->paginate(20);

        $totalHalaman = (int) $santri->total_halaman_hafalan;

        $rataRataNilai = Hafalan::where('santri_id', $santri->id)
            ->whereNotNull('nilai')
            ->avg('nilai');

        return view('ustadz.hafalan.by-santri', compact(
            'santri', 'hafalan', 'totalHalaman', 'rataRataNilai'
        ));
    }
}
