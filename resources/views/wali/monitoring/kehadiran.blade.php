{{-- resources/views/wali/monitoring/kehadiran.blade.php --}}
@extends('layouts.app')
@section('title', 'Rekap Kehadiran')
@section('page-title', 'Rekap Kehadiran')
@section('breadcrumb', '/ <span>Kehadiran</span>')

@push('styles')
<style>
@media (max-width: 767.98px) {
  .kehadiran-table-wrap { display: none; }
  .kehadiran-mobile-list { display: grid; gap: 10px; padding: 12px; }
  .kehadiran-mobile-card { border: 1px solid var(--border-light); border-radius: 10px; background: #fff; padding: 10px; }
}
@media (min-width: 768px) { .kehadiran-mobile-list { display: none; } }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Rekap Kehadiran</div>
        <div class="page-header-sub">{{ $santri->nama }}</div>
    </div>
    <form method="GET" class="d-flex gap-2">
        <input type="month" name="periode" class="form-control-custom" value="{{ $periode }}" style="width:160px;">
        <button type="submit" class="btn-hijau">Tampilkan</button>
    </form>
</div>

{{-- Summary Bulan Ini --}}
<div class="row g-3 mb-4">
    @foreach(['hadir'=>['Hadir','green','#1a5c2e'],'izin'=>['Izin','gold','#9a7a1a'],'sakit'=>['Sakit','blue','#1a3c8e'],'alpha'=>['Alpha','red','#c62828']] as $key=>[$lbl,$cls,$clr])
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon {{ $cls }}" style="background:{{ $key==='hadir' ? 'var(--hijau-pale)' : ($key==='izin' ? 'var(--emas-light)' : ($key==='sakit' ? '#e8f0fe' : '#fce8e8')) }};">
                    <svg viewBox="0 0 24 24" fill="none" stroke="{{ $clr }}" stroke-width="2"><path d="M9 11l3 3L22 4"/></svg>
                </div>
                <div class="stat-card-value">{{ $rekap[$key] ?? 0 }}</div>
                <div class="stat-card-label">{{ $lbl }}</div>
            </div>
        </div>
    @endforeach
</div>

{{-- Persentase --}}
@php $total = array_sum($rekap); $persen = $total > 0 ? round(($rekap['hadir'] / $total) * 100) : 0; @endphp
<div class="card-custom mb-4">
    <div class="card-body-custom">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div style="font-size:13px;font-weight:700;">Persentase Kehadiran {{ \Carbon\Carbon::parse($periode)->locale('id')->isoFormat('MMMM Y') }}</div>
            <div style="font-size:18px;font-weight:800;color:{{ $persen >= 85 ? 'var(--hijau)' : ($persen >= 70 ? 'var(--emas-dark)' : '#c62828') }}">{{ $persen }}%</div>
        </div>
        <div class="progress-hijau" style="height:10px;">
            <div class="progress-fill" style="width:{{ $persen }}%;background:{{ $persen >= 85 ? 'var(--hijau)' : ($persen >= 70 ? 'var(--emas)' : '#c62828') }};"></div>
        </div>
        <div style="font-size:11px;color:var(--txt3);margin-top:5px;">{{ $rekap['hadir'] ?? 0 }} hadir dari {{ $total }} hari absensi</div>
    </div>
</div>

{{-- Detail per Tanggal --}}
<div class="card-custom">
    <div class="card-header-custom"><div class="card-title-custom">Detail Kehadiran Harian</div></div>
    <div class="table-responsive kehadiran-table-wrap">
        <table class="table-custom">
            <thead><tr><th>Tanggal</th><th>Sesi</th><th>Status</th><th>Keterangan</th></tr></thead>
            <tbody>
                @forelse($kehadiran as $kh)
                    <tr>
                        <td style="font-size:13px;">{{ $kh->tanggal->locale('id')->isoFormat('dddd, D MMMM Y') }}</td>
                        <td><span class="badge-custom badge-gray" style="font-size:10px;">{{ ucfirst($kh->sesi) }}</span></td>
                        <td><span class="badge-custom status-{{ $kh->status }}">{{ $kh->status_label }}</span></td>
                        <td style="font-size:12px;color:var(--txt3);">{{ $kh->keterangan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-5" style="color:var(--txt3);">Tidak ada data kehadiran periode ini</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="kehadiran-mobile-list">
        @forelse($kehadiran as $kh)
            <div class="kehadiran-mobile-card">
                <div class="td-name-main mb-1">{{ $kh->tanggal->locale('id')->isoFormat('dddd, D MMMM Y') }}</div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge-custom badge-gray" style="font-size:10px;">{{ ucfirst($kh->sesi) }}</span>
                    <span class="badge-custom status-{{ $kh->status }}">{{ $kh->status_label }}</span>
                </div>
                <div class="td-name-sub">{{ $kh->keterangan ?? '-' }}</div>
            </div>
        @empty
            <div class="text-center py-3" style="color:var(--txt3);">Tidak ada data kehadiran periode ini</div>
        @endforelse
    </div>
</div>
@endsection


{{-- ════════════════════════════════════════════════════════════
     resources/views/wali/izin/index.blade.php
     ════════════════════════════════════════════════════════════ --}}
