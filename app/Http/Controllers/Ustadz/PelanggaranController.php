<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use App\Models\KategoriPelanggaran;
use App\Models\Pelanggaran;
use App\Models\Santri;
use Illuminate\Http\Request;

class PelanggaranController extends Controller
{
    private function buildKategoriPoinOptions()
    {
        $base = KategoriPelanggaran::where('is_active', true)
            ->orderBy('poin_default')
            ->orderBy('id')
            ->get()
            ->groupBy('tingkat')
            ->map(fn($items) => $items->first());

        $options = collect([
            [
                'value' => (($base->get('ringan')?->id ?? 0) . '|5|ringan'),
                'label' => 'Ringan - 5 poin',
            ],
            [
                'value' => (($base->get('sedang')?->id ?? 0) . '|15|sedang'),
                'label' => 'Sedang - 15 poin',
            ],
            [
                'value' => (($base->get('berat')?->id ?? 0) . '|50|berat'),
                'label' => 'Berat - 50 poin',
            ],
            [
                'value' => '0|100|sangat_berat',
                'label' => 'Sangat Berat - 100 poin',
            ],
        ]);

        return $options;
    }

    private function kategoriValueByTingkat($kategoriPoinOptions, string $tingkat): ?string
    {
        $found = collect($kategoriPoinOptions)->first(function ($opt) use ($tingkat) {
            return str_ends_with((string) $opt['value'], '|' . $tingkat);
        });
        return $found['value'] ?? null;
    }

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
        $kategoriPoinOptions = $this->buildKategoriPoinOptions();
        $ringanValue = $this->kategoriValueByTingkat($kategoriPoinOptions, 'ringan');
        $sedangValue = $this->kategoriValueByTingkat($kategoriPoinOptions, 'sedang');
        $beratValue = $this->kategoriValueByTingkat($kategoriPoinOptions, 'berat');
        $sangatBeratValue = collect($kategoriPoinOptions)->firstWhere('label', 'Sangat Berat - 100 poin')['value'] ?? null;

        $jenisPelanggaranList = [
            ['text' => 'Buang sampah sembarangan',      'label' => 'Buang sampah sembarangan - Ringan - 5 poin',  'kategori_poin' => $ringanValue],
            ['text' => 'Terlambat hadir',               'label' => 'Terlambat hadir - Ringan - 5 poin',           'kategori_poin' => $ringanValue],
            ['text' => 'Keluar tanpa izin',             'label' => 'Keluar tanpa izin - Sedang - 15 poin',        'kategori_poin' => $sedangValue],
            ['text' => 'Tidak mengikuti kegiatan wajib','label' => 'Tidak mengikuti kegiatan wajib - Sedang - 15 poin', 'kategori_poin' => $sedangValue],
            ['text' => 'Membawa HP tanpa izin',         'label' => 'Membawa HP tanpa izin - Berat - 50 poin',     'kategori_poin' => $beratValue],
            ['text' => 'Berkelahi dengan teman',        'label' => 'Berkelahi dengan teman - Sangat Berat - 100 poin', 'kategori_poin' => $sangatBeratValue],
            ['text' => 'Berkata tidak sopan',           'label' => 'Berkata tidak sopan - Ringan - 5 poin',       'kategori_poin' => $ringanValue],
        ];
        return view('ustadz.pelanggaran.create', compact('santriList', 'kategoriPoinOptions', 'jenisPelanggaranList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'santri_id'            => ['required', 'exists:santri,id'],
            'kategori_poin'        => ['required', 'string'],
            'jenis_pelanggaran'    => ['nullable', 'string', 'max:255'],
            'jenis_pelanggaran_manual' => ['nullable', 'string', 'max:255'],
            'tanggal'              => ['required', 'date', 'before_or_equal:today'],
            'keterangan'           => ['nullable', 'string'],
            'status_tindak_lanjut' => ['required', 'in:belum,sudah'],
            'tindak_lanjut'        => ['nullable', 'string'],
        ]);

        [$kategoriIdRaw, $poinRaw] = array_pad(explode('|', (string) $data['kategori_poin']), 2, null);
        $kategoriId = (int) $kategoriIdRaw;
        $poin = (int) $poinRaw;
        if ($poin <= 0 || $poin > 100) {
            return back()->withErrors(['kategori_poin' => 'Kategori & poin tidak valid.'])->withInput();
        }
        $data['kategori_id'] = $kategoriId > 0 ? $kategoriId : null;
        $data['poin_sanksi'] = $poin;
        unset($data['kategori_poin']);

        $finalJenis = trim((string) ($data['jenis_pelanggaran_manual'] ?? '')) ?: trim((string) ($data['jenis_pelanggaran'] ?? ''));
        if ($finalJenis === '') {
            return back()->withErrors(['jenis_pelanggaran' => 'Jenis pelanggaran wajib dipilih atau diisi manual.'])->withInput();
        }
        $data['jenis_pelanggaran'] = $finalJenis;
        unset($data['jenis_pelanggaran_manual']);

        $data['pencatat_id'] = $request->user()->id;

        Pelanggaran::create($data);
        return redirect()->route('ustadz.pelanggaran.index')->with('success', 'Pelanggaran berhasil dicatat.');
    }

    public function show(Pelanggaran $pelanggaran)
    {
        if (! view()->exists('ustadz.pelanggaran.show')) {
            abort(501, 'View ustadz.pelanggaran.show belum tersedia.');
        }
        return view('ustadz.pelanggaran.show', compact('pelanggaran'));
    }

    public function edit(Pelanggaran $pelanggaran)
    {
        $santriList = Santri::aktif()->with('kelas')->orderBy('nama')->get();
        $kategoriPoinOptions = $this->buildKategoriPoinOptions();
        $ringanValue = $this->kategoriValueByTingkat($kategoriPoinOptions, 'ringan');
        $sedangValue = $this->kategoriValueByTingkat($kategoriPoinOptions, 'sedang');
        $beratValue = $this->kategoriValueByTingkat($kategoriPoinOptions, 'berat');
        $sangatBeratValue = collect($kategoriPoinOptions)->firstWhere('label', 'Sangat Berat - 100 poin')['value'] ?? null;
        $jenisPelanggaranList = [
            ['text' => 'Buang sampah sembarangan',      'label' => 'Buang sampah sembarangan - Ringan - 5 poin',  'kategori_poin' => $ringanValue],
            ['text' => 'Terlambat hadir',               'label' => 'Terlambat hadir - Ringan - 5 poin',           'kategori_poin' => $ringanValue],
            ['text' => 'Keluar tanpa izin',             'label' => 'Keluar tanpa izin - Sedang - 15 poin',        'kategori_poin' => $sedangValue],
            ['text' => 'Tidak mengikuti kegiatan wajib','label' => 'Tidak mengikuti kegiatan wajib - Sedang - 15 poin', 'kategori_poin' => $sedangValue],
            ['text' => 'Membawa HP tanpa izin',         'label' => 'Membawa HP tanpa izin - Berat - 50 poin',     'kategori_poin' => $beratValue],
            ['text' => 'Berkelahi dengan teman',        'label' => 'Berkelahi dengan teman - Sangat Berat - 100 poin', 'kategori_poin' => $sangatBeratValue],
            ['text' => 'Berkata tidak sopan',           'label' => 'Berkata tidak sopan - Ringan - 5 poin',       'kategori_poin' => $ringanValue],
        ];
        return view('ustadz.pelanggaran.create', compact('pelanggaran', 'santriList', 'kategoriPoinOptions', 'jenisPelanggaranList'));
    }

    public function update(Request $request, Pelanggaran $pelanggaran)
    {
        $data = $request->validate([
            'santri_id'            => ['required', 'exists:santri,id'],
            'kategori_poin'        => ['required', 'string'],
            'jenis_pelanggaran'    => ['nullable', 'string', 'max:255'],
            'jenis_pelanggaran_manual' => ['nullable', 'string', 'max:255'],
            'tanggal'              => ['required', 'date', 'before_or_equal:today'],
            'keterangan'           => ['nullable', 'string'],
            'status_tindak_lanjut' => ['required', 'in:belum,sudah'],
            'tindak_lanjut'        => ['nullable', 'string'],
        ]);

        [$kategoriIdRaw, $poinRaw] = array_pad(explode('|', (string) $data['kategori_poin']), 2, null);
        $kategoriId = (int) $kategoriIdRaw;
        $poin = (int) $poinRaw;
        if ($poin <= 0 || $poin > 100) {
            return back()->withErrors(['kategori_poin' => 'Kategori & poin tidak valid.'])->withInput();
        }
        $data['kategori_id'] = $kategoriId > 0 ? $kategoriId : null;
        $data['poin_sanksi'] = $poin;
        unset($data['kategori_poin']);

        $finalJenis = trim((string) ($data['jenis_pelanggaran_manual'] ?? '')) ?: trim((string) ($data['jenis_pelanggaran'] ?? ''));
        if ($finalJenis === '') {
            return back()->withErrors(['jenis_pelanggaran' => 'Jenis pelanggaran wajib dipilih atau diisi manual.'])->withInput();
        }
        $data['jenis_pelanggaran'] = $finalJenis;
        unset($data['jenis_pelanggaran_manual']);

        $pelanggaran->update($data);
        return redirect()->route('ustadz.pelanggaran.index')->with('success', 'Pelanggaran berhasil diperbarui.');
    }

    public function destroy(Pelanggaran $pelanggaran)
    {
        $pelanggaran->delete();
        return redirect()->route('ustadz.pelanggaran.index')->with('success', 'Pelanggaran berhasil dihapus.');
    }
}

