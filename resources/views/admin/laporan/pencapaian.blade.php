@extends('layouts.app')
@section('title', 'Laporan Pencapaian')
@section('page-title', 'Laporan Pencapaian')
@section('breadcrumb', '/ <span>Laporan Pencapaian</span>')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Laporan Pencapaian</div>
        <div class="page-header-sub">Rekap pencapaian santri</div>
    </div>
    <a class="btn-outline-hijau" href="{{ route('admin.laporan.export.santri') }}">Export Santri CSV</a>
</div>

<div class="card-custom">
    <div class="table-responsive">
        <table class="table-custom">
            <thead><tr><th>Tanggal</th><th>Santri</th><th>Judul</th><th>Tingkat</th><th>Peringkat</th><th>Pencatat</th></tr></thead>
            <tbody>
            @forelse($data as $row)
                <tr>
                    <td>{{ $row->tanggal?->format('d M Y') }}</td>
                    <td>{{ $row->santri?->nama }}</td>
                    <td>{{ $row->judul_pencapaian }}</td>
                    <td>{{ $row->tingkat }}</td>
                    <td>{{ $row->peringkat }}</td>
                    <td>{{ $row->pencatat?->name }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-4">Tidak ada data.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

