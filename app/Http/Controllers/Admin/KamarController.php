<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    public function index()
    {
        $kamar = Kamar::withCount('santri')->latest()->paginate(15);
        return view('admin.kamar.index', compact('kamar'));
    }

    public function create()
    {
        return view('admin.kamar.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kamar' => ['required', 'string', 'max:255'],
            'gedung'     => ['nullable', 'string', 'max:255'],
            'kapasitas'  => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string'],
        ]);
        $data['is_active'] = true;

        Kamar::create($data);
        return redirect()->route('admin.kamar.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function show(Kamar $kamar)
    {
        $kamar->load('santri');
        return view('admin.kamar.show', compact('kamar'));
    }

    public function edit(Kamar $kamar)
    {
        return view('admin.kamar.edit', compact('kamar'));
    }

    public function update(Request $request, Kamar $kamar)
    {
        $data = $request->validate([
            'nama_kamar' => ['required', 'string', 'max:255'],
            'gedung'     => ['nullable', 'string', 'max:255'],
            'kapasitas'  => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string'],
            'is_active'  => ['sometimes', 'boolean'],
        ]);

        $kamar->update($data);
        return redirect()->route('admin.kamar.index')->with('success', 'Kamar berhasil diperbarui.');
    }

    public function destroy(Kamar $kamar)
    {
        $kamar->delete();
        return redirect()->route('admin.kamar.index')->with('success', 'Kamar berhasil dihapus.');
    }
}

