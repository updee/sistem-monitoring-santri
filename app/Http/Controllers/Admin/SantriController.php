<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\Kelas;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Http\Request;

class SantriController extends Controller
{
    public function index(Request $request)
    {
        $query = Santri::with(['kelas', 'kamar', 'wali'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = $request->search;
                $q->where(function ($x) use ($term) {
                    $x->where('nama', 'like', '%' . $term . '%')
                        ->orWhere('nis', 'like', '%' . $term . '%');
                });
            })
            ->when($request->filled('kelas_id'), fn($q) => $q->where('kelas_id', $request->kelas_id))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15);

        $santri = $query->withQueryString();
        $kelasList = Kelas::where('is_active', true)->orderBy('nama_kelas')->get();

        return view('admin.santri.index', compact('santri', 'kelasList'));
    }

    public function create()
    {
        $kelasList = Kelas::where('is_active', true)->orderBy('nama_kelas')->get();
        $kamarList = Kamar::where('is_active', true)->orderBy('nama_kamar')->get();
        $waliList = User::where('role', 'wali_santri')->where('is_active', true)->orderBy('name')->get();
        return view('admin.santri.create', compact('kelasList', 'kamarList', 'waliList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nis'           => ['required', 'string', 'max:20', 'unique:santri,nis'],
            'nama'          => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir'  => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat'        => ['nullable', 'string'],
            'no_telepon'    => ['nullable', 'string', 'max:20'],
            'kelas_id'      => ['nullable', 'exists:kelas,id'],
            'kamar_id'      => ['nullable', 'exists:kamar,id'],
            'wali_id'       => ['nullable', 'exists:users,id'],
            'tanggal_masuk' => ['nullable', 'date'],
            'status'        => ['nullable', 'in:aktif,alumni,keluar'],
            'catatan'       => ['nullable', 'string'],
        ]);

        Santri::create($data);

        return redirect()->route('admin.santri.index')
            ->with('success', 'Santri berhasil ditambahkan.');
    }

    public function show(Santri $santri)
    {
        return redirect()->route('admin.santri.rekap', $santri);
    }

    public function edit(Santri $santri)
    {
        $kelasList = Kelas::where('is_active', true)->orderBy('nama_kelas')->get();
        $kamarList = Kamar::where('is_active', true)->orderBy('nama_kamar')->get();
        $waliList = User::where('role', 'wali_santri')->where('is_active', true)->orderBy('name')->get();
        return view('admin.santri.edit', compact('santri', 'kelasList', 'kamarList', 'waliList'));
    }

    public function update(Request $request, Santri $santri)
    {
        $data = $request->validate([
            'nis'           => ['required', 'string', 'max:20', 'unique:santri,nis,' . $santri->id],
            'nama'          => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir'  => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat'        => ['nullable', 'string'],
            'no_telepon'    => ['nullable', 'string', 'max:20'],
            'kelas_id'      => ['nullable', 'exists:kelas,id'],
            'kamar_id'      => ['nullable', 'exists:kamar,id'],
            'wali_id'       => ['nullable', 'exists:users,id'],
            'tanggal_masuk' => ['nullable', 'date'],
            'tanggal_keluar'=> ['nullable', 'date'],
            'status'        => ['nullable', 'in:aktif,alumni,keluar'],
            'catatan'       => ['nullable', 'string'],
        ]);

        $santri->update($data);

        return redirect()->route('admin.santri.index')
            ->with('success', 'Santri berhasil diperbarui.');
    }

    public function destroy(Santri $santri)
    {
        $santri->delete();

        return redirect()->route('admin.santri.index')
            ->with('success', 'Santri berhasil dihapus.');
    }

    public function rekap(Santri $santri)
    {
        $santri->load(['kelas', 'kamar', 'wali', 'hafalan', 'kehadiran', 'pelanggaran', 'pencapaian', 'izin']);
        return view('admin.santri.rekap', compact('santri'));
    }
}

