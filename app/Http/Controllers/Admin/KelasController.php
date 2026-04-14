<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with('ustadz')->latest()->paginate(15);
        $kamar = Kamar::latest()->take(20)->get();
        return view('admin.kelas.index', compact('kelas', 'kamar'));
    }

    public function create()
    {
        $ustadz = User::where('role', 'ustadz')->where('is_active', true)->get();
        return view('admin.kelas.create', compact('ustadz'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255'],
            'tingkat'    => ['required', 'string', 'max:50'],
            'ustadz_id'  => ['nullable', 'exists:users,id'],
        ]);
        $data['is_active'] = true;

        Kelas::create($data);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function show(Kelas $kela)
    {
        return view('admin.kelas.show', ['kelas' => $kela]);
    }

    public function edit(Kelas $kela)
    {
        $ustadz = User::where('role', 'ustadz')->where('is_active', true)->get();
        return view('admin.kelas.edit', ['kelas' => $kela, 'ustadz' => $ustadz]);
    }

    public function update(Request $request, Kelas $kela)
    {
        $data = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255'],
            'tingkat'    => ['required', 'string', 'max:50'],
            'ustadz_id'  => ['nullable', 'exists:users,id'],
            'is_active'  => ['sometimes', 'boolean'],
        ]);

        $kela->update($data);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}

