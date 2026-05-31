<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use App\Models\Hafalan;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class HafalanController extends Controller
{
    public function index(Request $request)
    {
        $hafalan = Hafalan::with(['santri', 'ustadz'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->whereHas('santri', fn($s) => $s->where('nama', 'like', '%'.$request->search.'%'));
            })
            ->when($request->filled('jenis'), fn($q) => $q->where('jenis', $request->jenis))
            ->when($request->filled('grade'), fn($q) => $q->where('grade', $request->grade))
            ->when($request->filled('kategori'), fn($q) => $q->where('kategori', $request->kategori))
            ->latest('tanggal_setoran')
            ->paginate(15)
            ->withQueryString();

        return view('ustadz.hafalan.index', compact('hafalan'));
    }

    public function create()
    {
        $santriList = Santri::aktif()->orderBy('nama')->get();
        return view('ustadz.hafalan.create', compact('santriList'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateHafalan($request);
        $validated['ustadz_id'] = Auth::id();

        // Bersihkan field yang tidak relevan dengan kategori yang dipilih
        $validated = $this->cleanCategoryFields($validated);

        Hafalan::create($validated);

        // Simpan & Tambah Lagi
        if ($request->filled('simpan_dan_tambah_lagi')) {
            return redirect()->route('ustadz.hafalan.create')
                ->with('success', 'Data setoran hafalan berhasil disimpan. Silakan input setoran berikutnya.');
        }

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
        $validated = $this->validateHafalan($request);

        // Bersihkan field yang tidak relevan dengan kategori yang dipilih
        $validated = $this->cleanCategoryFields($validated);

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

    // ── Private Helpers ──────────────────────────────────────────

    private function validateHafalan(Request $request): array
    {
        return $request->validate([
            // Identitas
            'santri_id'         => ['required', 'exists:santri,id'],
            'tanggal_setoran'   => ['required', 'date', 'before_or_equal:today'],
            // Kategori & Jenis
            'kategori'          => ['nullable', 'in:wisuda,zaidah,ujian,harian'],
            'jenis'             => ['required', 'in:setoran_baru,murojaah'],
            // Detail Materi
            'nama_surat'        => ['required', 'string', 'max:100'],
            'nomor_juz'         => ['nullable', 'integer', 'min:1', 'max:30'],
            'halaman_dari'      => ['nullable', 'integer', 'min:1'],
            'halaman_sampai'    => ['nullable', 'integer', 'min:1', 'gte:halaman_dari'],
            'ayat_dari'         => ['nullable', 'integer', 'min:1'],
            'ayat_sampai'       => ['nullable', 'integer', 'min:1', 'gte:ayat_dari'],
            // Wisuda
            'target_wisuda'     => ['nullable', 'required_if:kategori,wisuda', 'string', 'max:100'],
            'sesi_wisuda'       => ['nullable', 'required_if:kategori,wisuda', 'in:setoran_bertahap,tasmi'],
            'status_wisuda'     => ['nullable', 'required_if:kategori,wisuda', 'in:lulus,perbaikan,belum_lulus'],
            'catatan_perbaikan' => ['nullable', 'string', 'max:1000'],
            // Zaidah
            'zaidah_ke'         => ['nullable', 'integer', 'min:1'],
            'keterangan_zaidah' => ['nullable', 'string', 'max:500'],
            // Ujian
            'jenis_ujian'       => ['nullable', 'required_if:kategori,ujian', 'in:pekanan,bulanan,tengah_semester,semester'],
            'model_ujian'       => ['nullable', 'required_if:kategori,ujian', 'in:tasmi,sambung_ayat,acak_halaman'],
            'status_ujian'      => ['nullable', 'required_if:kategori,ujian', 'in:lulus,remedial'],
            'jadwal_remedial'   => ['nullable', 'required_if:status_ujian,remedial', 'date'],
            // Penilaian
            'nilai'             => ['nullable', 'numeric', 'min:0', 'max:100'],
            'salah_ringan'      => ['nullable', 'integer', 'min:0'],
            'salah_berat'       => ['nullable', 'integer', 'min:0'],
            'kelancaran'        => ['nullable', 'integer', 'min:1', 'max:5'],
            'tajwid_makhraj'    => ['nullable', 'integer', 'min:1', 'max:5'],
            // Catatan
            'catatan'           => ['nullable', 'string', 'max:500'],
        ], [
            'santri_id.required'              => 'Santri wajib dipilih.',
            'nama_surat.required'             => 'Nama surat wajib diisi.',
            'halaman_sampai.gte'              => 'Halaman sampai harus lebih besar dari halaman mulai.',
            'ayat_sampai.gte'                 => 'Ayat sampai harus lebih besar dari ayat mulai.',
            'tanggal_setoran.required'        => 'Tanggal setoran wajib diisi.',
            'tanggal_setoran.before_or_equal' => 'Tanggal setoran tidak boleh melebihi hari ini.',
            'target_wisuda.required_if'       => 'Target wisuda wajib diisi untuk kategori Wisuda.',
            'sesi_wisuda.required_if'         => 'Sesi wisuda wajib dipilih untuk kategori Wisuda.',
            'status_wisuda.required_if'       => 'Status wisuda wajib dipilih untuk kategori Wisuda.',
            'jenis_ujian.required_if'         => 'Jenis ujian wajib dipilih untuk kategori Ujian.',
            'model_ujian.required_if'         => 'Model ujian wajib dipilih untuk kategori Ujian.',
            'status_ujian.required_if'        => 'Status ujian wajib dipilih untuk kategori Ujian.',
            'jadwal_remedial.required_if'     => 'Jadwal remedial wajib diisi jika status ujian Remedial.',
        ]);
    }

    /**
     * Null-kan field yang tidak relevan dengan kategori yang dipilih,
     * agar tidak ada data "sisa" dari switch kategori di form.
     */
    private function cleanCategoryFields(array $data): array
    {
        $kat = $data['kategori'] ?? null;

        if ($kat !== 'wisuda') {
            $data['target_wisuda']     = null;
            $data['sesi_wisuda']       = null;
            $data['status_wisuda']     = null;
            $data['catatan_perbaikan'] = null;
        }
        if ($kat !== 'zaidah') {
            $data['zaidah_ke']         = null;
            $data['keterangan_zaidah'] = null;
        }
        if ($kat !== 'ujian') {
            $data['jenis_ujian']     = null;
            $data['model_ujian']     = null;
            $data['status_ujian']    = null;
            $data['jadwal_remedial'] = null;
        }
        // Jika ujian tapi tidak remedial, hapus jadwal
        if ($kat === 'ujian' && ($data['status_ujian'] ?? null) !== 'remedial') {
            $data['jadwal_remedial'] = null;
        }

        return $data;
    }
}
