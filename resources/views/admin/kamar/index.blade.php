@extends('layouts.app')
@section('title', 'Data Kamar')
@section('page-title', 'Data Kamar')
@section('breadcrumb', '/ <span>Kamar</span>')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Data Kamar / Asrama</div>
        <div class="page-header-sub">Kelola kamar, kapasitas, dan status aktif</div>
    </div>
    <a href="{{ route('admin.kamar.create') }}" class="btn-hijau">+ Tambah Kamar</a>
</div>

<div class="card-custom">
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>No</th><th>Nama Kamar</th><th>Gedung</th><th>Kapasitas</th><th>Terisi</th><th>Status</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kamar as $i => $k)
                    <tr>
                        <td>{{ $kamar->firstItem() + $i }}</td>
                        <td>{{ $k->nama_kamar }}</td>
                        <td>{{ $k->gedung ?? '-' }}</td>
                        <td>{{ $k->kapasitas }}</td>
                        <td>{{ $k->santri_count }}</td>
                        <td><span class="badge-custom {{ $k->is_active ? 'badge-green' : 'badge-gray' }}">{{ $k->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td class="d-flex gap-1">
                            <a href="{{ route('admin.kamar.show', $k) }}" class="btn-view-custom">Detail</a>
                            <a href="{{ route('admin.kamar.edit', $k) }}" class="btn-edit-custom">Edit</a>
                            <form method="POST" action="{{ route('admin.kamar.destroy', $k) }}" onsubmit="return confirm('Hapus kamar ini?')">
                                @csrf @method('DELETE')
                                <button class="btn-danger-custom" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4">Belum ada data kamar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

