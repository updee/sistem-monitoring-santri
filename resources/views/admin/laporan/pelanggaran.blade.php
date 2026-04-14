@extends('layouts.app')
@section('title', 'Laporan Pelanggaran')
@section('page-title', 'Laporan Pelanggaran')
@section('breadcrumb', '/ <span>Laporan Pelanggaran</span>')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Laporan Pelanggaran</div>
        <div class="page-header-sub">Rekap pelanggaran disiplin santri</div>
    </div>
    <a class="btn-outline-hijau" href="{{ route('admin.laporan.export.pelanggaran') }}">Export CSV</a>
</div>

<div class="card-custom">
    <div class="table-responsive">
        <table class="table-custom">
            <thead><tr><th>Tanggal</th><th>Santri</th><th>Jenis</th><th>Poin</th><th>Pencatat</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($data as $row)
                <tr>
                    <td>{{ $row->tanggal?->format('d M Y') }}</td>
                    <td>{{ $row->santri?->nama }}</td>
                    <td>{{ $row->jenis_pelanggaran }}</td>
                    <td>{{ $row->poin_sanksi }}</td>
                    <td>{{ $row->pencatat?->name }}</td>
                    <td>{{ $row->status_tindak_lanjut }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-4">Tidak ada data.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

