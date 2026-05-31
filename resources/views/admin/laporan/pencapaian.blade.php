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
    <div class="d-flex gap-2">
        <a class="btn-outline-hijau" href="{{ route('admin.laporan.export.pencapaian', request()->query()) }}">Export CSV</a>
    </div>
</div>

<div class="card-custom mb-3">
    <div class="card-body-custom" style="padding:12px 18px;">
        <form method="GET" action="{{ route('admin.laporan.pencapaian') }}" class="d-flex gap-2 align-items-center flex-wrap">
            <select name="bulan" class="form-control-custom" style="width:130px; font-size:12px; padding:6px 12px; height:auto;">
                <option value="">Semua Bulan</option>
                @for($m=1;$m<=12;$m++)
                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected':'' }}>
                        {{ \Carbon\Carbon::create(null,$m)->locale('id')->isoFormat('MMMM') }}
                    </option>
                @endfor
            </select>
            <select name="tahun" class="form-control-custom" style="width:100px; font-size:12px; padding:6px 12px; height:auto;">
                <option value="">Semua Tahun</option>
                @for($y=now()->year;$y>=2023;$y--)
                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected':'' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="btn-hijau" style="padding:6px 14px; font-size:12px;">Filter</button>
        </form>
    </div>
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

