@extends('layouts.app')
@section('title', 'Laporan Hafalan')
@section('page-title', 'Laporan Hafalan')
@section('breadcrumb', '/ <span>Laporan Hafalan</span>')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Laporan Hafalan</div>
        <div class="page-header-sub">Daftar setoran hafalan terbaru (read-only)</div>
    </div>
    <a class="btn-outline-hijau" href="{{ route('admin.laporan.export.hafalan') }}">Export CSV</a>
</div>

{{-- Filter --}}
<div class="card-custom mb-4">
    <div class="card-body-custom">
        <form method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label-custom">Bulan</label>
                    <select name="bulan" class="form-control-custom">
                        <option value="">Semua</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->locale('id')->isoFormat('MMMM') }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label-custom">Tahun</label>
                    <input type="number" name="tahun" class="form-control-custom" placeholder="{{ date('Y') }}" value="{{ request('tahun') }}" min="2020" max="{{ date('Y') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label-custom">Kategori</label>
                    <select name="kategori" class="form-control-custom">
                        <option value="">Semua</option>
                        <option value="wisuda" {{ request('kategori') === 'wisuda' ? 'selected' : '' }}>🎓 Wisuda</option>
                        <option value="zaidah" {{ request('kategori') === 'zaidah' ? 'selected' : '' }}>📖 Zaidah</option>
                        <option value="ujian"  {{ request('kategori') === 'ujian'  ? 'selected' : '' }}>📝 Ujian</option>
                        <option value="harian" {{ request('kategori') === 'harian' ? 'selected' : '' }}>📅 Harian</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn-hijau flex-grow-1 justify-content-center">Filter</button>
                    <a href="{{ route('admin.laporan.hafalan') }}" class="btn-outline-hijau">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card-custom">
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Santri</th>
                    <th>Surat</th>
                    <th>Juz</th>
                    <th>Halaman</th>
                    <th>Kategori</th>
                    <th>Jenis</th>
                    <th>Nilai</th>
                    <th>Grade</th>
                    <th>Penyimak</th>
                </tr>
            </thead>
            <tbody>
            @forelse($data as $row)
                <tr>
                    <td style="font-size:12px;">{{ $row->tanggal_setoran?->format('d M Y') }}</td>
                    <td>
                        <div class="td-name-main">{{ $row->santri?->nama }}</div>
                        <div class="td-name-sub">{{ $row->santri?->kelas?->nama_kelas ?? '-' }}</div>
                    </td>
                    <td>{{ $row->nama_surat }}</td>
                    <td>{{ $row->nomor_juz }}</td>
                    <td>{{ $row->jumlah_halaman }}</td>
                    <td>
                        @if($row->kategori)
                            <span class="badge-custom {{ $row->kategori_badge_color }}" style="font-size:10px;">{{ $row->kategori_label }}</span>
                        @else
                            <span style="color:var(--txt3);">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge-custom {{ $row->jenis === 'setoran_baru' ? 'badge-green' : 'badge-blue' }}" style="font-size:10px;">{{ $row->jenis_label }}</span>
                    </td>
                    <td style="font-weight:700;">{{ $row->nilai ? number_format($row->nilai, 1) : '-' }}</td>
                    <td>
                        @if($row->grade)
                            <span class="badge-custom grade-{{ strtolower($row->grade) }}">{{ $row->grade }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td style="font-size:11px;">{{ Str::limit($row->ustadz?->name ?? '-', 18) }}</td>
                </tr>
            @empty
                <tr><td colspan="10" class="text-center py-4">Tidak ada data.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($data->total() > 0)
    <div class="pagination-custom">
        <div class="pagination-info">{{ $data->firstItem() }}–{{ $data->lastItem() }} dari {{ $data->total() }}</div>
        <div class="d-flex gap-1">
            @foreach($data->getUrlRange(max(1,$data->currentPage()-2), min($data->lastPage(),$data->currentPage()+2)) as $page => $url)
                <a href="{{ $url }}" class="page-link {{ $page === $data->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
