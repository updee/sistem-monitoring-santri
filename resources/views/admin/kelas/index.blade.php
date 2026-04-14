@extends('layouts.app')
@section('title','Kelas & Halaqah')
@section('page-title','Kelas & Halaqah')
@section('breadcrumb','/ <span>Kelas & Kamar</span>')

@section('content')
<div class="row g-4">
    <div class="col-lg-7">
        <div class="page-header">
            <div><div class="page-header-title">Data Kelas / Halaqah</div></div>
            <a href="{{ route('admin.kelas.create') }}" class="btn-hijau" style="font-size:12px;padding:7px 14px;">+ Tambah Kelas</a>
        </div>
        <div class="card-custom">
            <div class="table-responsive">
                <table class="table-custom">
                    <thead><tr><th>No</th><th>Nama Kelas</th><th>Tingkat</th><th>Ustadz</th><th>Santri</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @forelse($kelas as $idx => $k)
                            <tr>
                                <td>{{ $kelas->firstItem()+$idx }}</td>
                                <td>{{ $k->nama_kelas }}</td>
                                <td>Tingkat {{ $k->tingkat }}</td>
                                <td>{{ $k->ustadz?->name ?? '-' }}</td>
                                <td>{{ $k->jumlah_santri }}</td>
                                <td class="d-flex gap-1">
                                    <a href="{{ route('admin.kelas.show',$k) }}" class="btn-view-custom">Detail</a>
                                    <a href="{{ route('admin.kelas.edit',$k) }}" class="btn-edit-custom">Edit</a>
                                    <form method="POST" action="{{ route('admin.kelas.destroy',$k) }}" onsubmit="return confirm('Hapus kelas ini?')">@csrf @method('DELETE')<button type="submit" class="btn-danger-custom">Hapus</button></form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-4">Belum ada data kelas</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="page-header">
            <div><div class="page-header-title">Data Kamar</div></div>
            <a href="{{ route('admin.kamar.index') }}" class="btn-outline-hijau" style="font-size:12px;padding:7px 14px;">Kelola Kamar</a>
        </div>
        <div class="card-custom"><div class="card-body-custom">
            @forelse($kamar as $km)
                <div class="d-flex justify-content-between mb-2"><span>{{ $km->nama_kamar }}</span><span>{{ $km->kapasitas }} org</span></div>
            @empty
                <div class="text-muted">Belum ada data kamar.</div>
            @endforelse
        </div></div>
    </div>
</div>
@endsection
