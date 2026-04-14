<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use App\Models\Pencapaian;
use App\Models\Santri;
use Illuminate\Http\Request;

class PencapaianController extends Controller
{
    public function index(Request $request)
    {
        $pencapaian = Pencapaian::with(['santri.kelas'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = $request->search;
                $q->where('judul_pencapaian', 'like', '%' . $term . '%')
                    ->orWhereHas('santri', fn($s) => $s->where('nama', 'like', '%' . $term . '%'));
            })
            ->latest('tanggal')
            ->paginate(20)
            ->withQueryString();
        return view('ustadz.pencapaian.index', compact('pencapaian'));
    }

    public function create()
    {
        $santriList = Santri::aktif()->orderBy('nama')->get();
        return view('ustadz.pencapaian.create', compact('santriList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'santri_id'        => ['required', 'exists:santri,id'],
            'judul_pencapaian' => ['required', 'string', 'max:255'],
            'jenis'            => ['required', 'string', 'max:100'],
            'tingkat'          => ['required', 'in:pesantren,kabupaten,provinsi,nasional,internasional'],
            'peringkat'        => ['nullable', 'in:juara_1,juara_2,juara_3,harapan,peserta'],
            'tanggal'          => ['required', 'date', 'before_or_equal:today'],
            'penyelenggara'    => ['nullable', 'string', 'max:255'],
            'keterangan'       => ['nullable', 'string'],
        ]);
        $data['pencatat_id'] = $request->user()->id;
        Pencapaian::create($data);
        return redirect()->route('ustadz.pencapaian.index')->with('success', 'Pencapaian berhasil ditambahkan.');
    }

    public function show(Pencapaian $pencapaian)
    {
        if (! view()->exists('ustadz.pencapaian.show')) {
            abort(501, 'View ustadz.pencapaian.show belum tersedia.');
        }
        return view('ustadz.pencapaian.show', compact('pencapaian'));
    }

    public function edit(Pencapaian $pencapaian)
    {
        $santriList = Santri::aktif()->orderBy('nama')->get();
        return view('ustadz.pencapaian.create', compact('pencapaian', 'santriList'));
    }

    public function update(Request $request, Pencapaian $pencapaian)
    {
        $data = $request->validate([
            'santri_id'        => ['required', 'exists:santri,id'],
            'judul_pencapaian' => ['required', 'string', 'max:255'],
            'jenis'            => ['required', 'string', 'max:100'],
            'tingkat'          => ['required', 'in:pesantren,kabupaten,provinsi,nasional,internasional'],
            'peringkat'        => ['nullable', 'in:juara_1,juara_2,juara_3,harapan,peserta'],
            'tanggal'          => ['required', 'date', 'before_or_equal:today'],
            'penyelenggara'    => ['nullable', 'string', 'max:255'],
            'keterangan'       => ['nullable', 'string'],
        ]);
        $pencapaian->update($data);
        return redirect()->route('ustadz.pencapaian.index')->with('success', 'Pencapaian berhasil diperbarui.');
    }

    public function destroy(Pencapaian $pencapaian)
    {
        $pencapaian->delete();
        return redirect()->route('ustadz.pencapaian.index')->with('success', 'Pencapaian berhasil dihapus.');
    }
}

