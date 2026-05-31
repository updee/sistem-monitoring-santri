<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriPelanggaran;
use Illuminate\Http\Request;

class KategoriPelanggaranController extends Controller
{
    public function index()
    {
        $kategori = KategoriPelanggaran::withCount('pelanggaran')->latest()->paginate(15);
        return view('admin.kategori-pelanggaran.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategori-pelanggaran.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255', 'unique:kategori_pelanggaran,nama_kategori'],
            'tingkat'       => ['required', 'in:ringan,sedang,berat'],
            'poin_default'  => ['required', 'integer', 'min:0'],
            'deskripsi'     => ['nullable', 'string'],
        ]);
        $data['is_active'] = true;
        KategoriPelanggaran::create($data);

        return redirect()->route('admin.kategori-pelanggaran.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function show(KategoriPelanggaran $kategori_pelanggaran)
    {
        $kategori_pelanggaran->load('pelanggaran.santri');
        return view('admin.kategori-pelanggaran.show', compact('kategori_pelanggaran'));
    }

    public function edit(KategoriPelanggaran $kategori_pelanggaran)
    {
        return view('admin.kategori-pelanggaran.edit', compact('kategori_pelanggaran'));
    }

    public function update(Request $request, KategoriPelanggaran $kategori_pelanggaran)
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255', 'unique:kategori_pelanggaran,nama_kategori,' . $kategori_pelanggaran->id],
            'tingkat'       => ['required', 'in:ringan,sedang,berat'],
            'poin_default'  => ['required', 'integer', 'min:0'],
            'deskripsi'     => ['nullable', 'string'],
            'is_active'     => ['sometimes', 'boolean'],
        ]);

        $kategori_pelanggaran->update($data);
        return redirect()->route('admin.kategori-pelanggaran.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(KategoriPelanggaran $kategori_pelanggaran)
    {
        $kategori_pelanggaran->delete();
        return redirect()->route('admin.kategori-pelanggaran.index')->with('success', 'Kategori berhasil dihapus.');
    }
}

