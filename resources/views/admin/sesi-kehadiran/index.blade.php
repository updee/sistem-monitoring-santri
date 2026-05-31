@extends('layouts.app')
@section('title', 'Sesi Kehadiran')
@section('page-title', 'Sesi Kehadiran')
@section('breadcrumb', '/ <span>Sesi Kehadiran</span>')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Master Sesi Kehadiran</div>
        <div class="page-header-sub">Kelola nama sesi untuk pencatatan kehadiran santri</div>
    </div>
    <a href="{{ route('admin.sesi-kehadiran.create') }}" class="btn-hijau">+ Tambah Sesi</a>
</div>

<div class="card-custom">
    <div class="table-responsive">
        <table class="table-custom">
            <thead><tr><th>No</th><th>Nama Sesi</th><th>Urutan</th><th>Dipakai</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($sesiList as $i => $s)
                    <tr>
                        <td>{{ $sesiList->firstItem() + $i }}</td>
                        <td style="font-weight:600;">{{ $s->nama_sesi }}</td>
                        <td>{{ $s->urutan }}</td>
                        <td>{{ $s->kehadiran_count }} data</td>
                        <td><span class="badge-custom {{ $s->is_active ? 'badge-green' : 'badge-gray' }}">{{ $s->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td class="d-flex gap-1">
                            <a class="btn-edit-custom" href="{{ route('admin.sesi-kehadiran.edit', $s) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.sesi-kehadiran.destroy', $s) }}" onsubmit="return confirm('Hapus sesi ini?')">
                                @csrf @method('DELETE')
                                <button class="btn-danger-custom" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4">Belum ada data sesi kehadiran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
