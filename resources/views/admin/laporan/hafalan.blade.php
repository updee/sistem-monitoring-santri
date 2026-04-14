@extends('layouts.app')
@section('title', 'Laporan Hafalan')
@section('page-title', 'Laporan Hafalan')
@section('breadcrumb', '/ <span>Laporan Hafalan</span>')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Laporan Hafalan</div>
        <div class="page-header-sub">Daftar setoran hafalan terbaru</div>
    </div>
    <a class="btn-outline-hijau" href="{{ route('admin.laporan.export.hafalan') }}">Export CSV</a>
</div>

<div class="card-custom">
    <div class="table-responsive">
        <table class="table-custom">
            <thead><tr><th>Tanggal</th><th>Santri</th><th>Surat</th><th>Juz</th><th>Halaman</th><th>Nilai</th><th>Grade</th></tr></thead>
            <tbody>
            @forelse($data as $row)
                <tr>
                    <td>{{ $row->tanggal_setoran?->format('d M Y') }}</td>
                    <td>{{ $row->santri?->nama }}</td>
                    <td>{{ $row->nama_surat }}</td>
                    <td>{{ $row->nomor_juz }}</td>
                    <td>{{ $row->jumlah_halaman }}</td>
                    <td>{{ $row->nilai }}</td>
                    <td>{{ $row->grade }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-4">Tidak ada data.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

