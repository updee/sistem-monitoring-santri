@extends('layouts.app')
@section('title', 'Laporan Izin')
@section('page-title', 'Laporan Pengajuan Izin')
@section('breadcrumb', '/ <span>Laporan Izin</span>')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Laporan Pengajuan Izin</div>
        <div class="page-header-sub">Rekap data izin santri</div>
    </div>
    <div class="d-flex gap-2">
        <a class="btn-outline-hijau" href="{{ route('admin.laporan.export.izin', request()->query()) }}">Export CSV</a>
    </div>
</div>

<div class="card-custom">
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Tgl Mulai</th>
                    <th>Tgl Kembali</th>
                    <th>Santri</th>
                    <th>Alasan</th>
                    <th>Status</th>
                    <th>Catatan Admin</th>
                </tr>
            </thead>
            <tbody>
            @forelse($data as $row)
                <tr>
                    <td>{{ $row->tanggal_mulai?->format('d M Y') }}</td>
                    <td>{{ $row->tanggal_kembali?->format('d M Y') }}</td>
                    <td>{{ $row->santri?->nama }}</td>
                    <td>{{ Str::limit($row->alasan, 50) }}</td>
                    <td>
                        <span class="badge-custom badge-{{ $row->status === 'menunggu' ? 'gold' : ($row->status === 'disetujui' ? 'green' : 'red') }}">
                            {{ $row->status_label }}
                        </span>
                    </td>
                    <td>{{ Str::limit($row->catatan_admin, 50) ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-4">Tidak ada data.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($data->hasPages())
<div class="d-flex justify-content-center mt-3">
    {{ $data->links('pagination::bootstrap-5') }}
</div>
@endif
@endsection
