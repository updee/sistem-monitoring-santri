<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SesiKehadiran;
use Illuminate\Http\Request;

class SesiKehadiranController extends Controller
{
    public function index()
    {
        $sesiList = SesiKehadiran::withCount('kehadiran')->orderBy('urutan')->paginate(15);
        return view('admin.sesi-kehadiran.index', compact('sesiList'));
    }

    public function create()
    {
        return view('admin.sesi-kehadiran.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_sesi' => ['required', 'string', 'max:100', 'unique:sesi_kehadiran,nama_sesi'],
            'urutan'    => ['required', 'integer', 'min:0'],
        ]);
        $data['is_active'] = true;
        SesiKehadiran::create($data);

        return redirect()->route('admin.sesi-kehadiran.index')->with('success', 'Sesi kehadiran berhasil ditambahkan.');
    }

    public function edit(SesiKehadiran $sesi_kehadiran)
    {
        return view('admin.sesi-kehadiran.edit', compact('sesi_kehadiran'));
    }

    public function update(Request $request, SesiKehadiran $sesi_kehadiran)
    {
        $data = $request->validate([
            'nama_sesi' => ['required', 'string', 'max:100', 'unique:sesi_kehadiran,nama_sesi,' . $sesi_kehadiran->id],
            'urutan'    => ['required', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $sesi_kehadiran->update($data);
        return redirect()->route('admin.sesi-kehadiran.index')->with('success', 'Sesi kehadiran berhasil diperbarui.');
    }

    public function destroy(SesiKehadiran $sesi_kehadiran)
    {
        if ($sesi_kehadiran->kehadiran()->count() > 0) {
            return redirect()->route('admin.sesi-kehadiran.index')
                ->with('error', 'Sesi tidak bisa dihapus karena masih dipakai di data kehadiran. Nonaktifkan saja melalui Edit.');
        }
        $sesi_kehadiran->delete();
        return redirect()->route('admin.sesi-kehadiran.index')->with('success', 'Sesi kehadiran berhasil dihapus.');
    }
}
