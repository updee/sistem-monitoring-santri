@extends('layouts.app')
@section('title', 'Kategori Pelanggaran')
@section('page-title', 'Kategori Pelanggaran')
@section('breadcrumb', '/ <span>Kategori Pelanggaran</span>')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Kategori Pelanggaran</div>
        <div class="page-header-sub">Master kategori pelanggaran & poin default</div>
    </div>
    <a href="{{ route('admin.kategori-pelanggaran.create') }}" class="btn-hijau">+ Tambah Kategori</a>
</div>

<div class="card-custom">
    <div class="table-responsive">
        <table class="table-custom">
            <thead><tr><th>No</th><th>Nama Kategori</th><th>Tingkat</th><th>Poin Default</th><th>Dipakai</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($kategori as $i => $k)
                    <tr>
                        <td>{{ $kategori->firstItem() + $i }}</td>
                        <td>{{ $k->nama_kategori }}</td>
                        <td><span class="badge-custom badge-blue">{{ ucfirst($k->tingkat) }}</span></td>
                        <td>{{ $k->poin_default }}</td>
                        <td>{{ $k->pelanggaran_count }}</td>
                        <td><span class="badge-custom {{ $k->is_active ? 'badge-green' : 'badge-gray' }}">{{ $k->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td class="d-flex gap-1">
                            <a class="btn-view-custom" href="{{ route('admin.kategori-pelanggaran.show', $k) }}">Detail</a>
                            <a class="btn-edit-custom" href="{{ route('admin.kategori-pelanggaran.edit', $k) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.kategori-pelanggaran.destroy', $k) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button class="btn-danger-custom" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4">Belum ada data kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

