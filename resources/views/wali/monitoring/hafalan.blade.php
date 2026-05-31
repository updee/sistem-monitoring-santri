{{-- resources/views/wali/monitoring/hafalan.blade.php --}}
@extends('layouts.app')
@section('title', 'Progress Hafalan')
@section('page-title', 'Progress Hafalan')
@section('breadcrumb', '/ <span>Progress Hafalan</span>')

@push('styles')
<style>
@media (max-width: 767.98px) {
  .wali-hafalan-table-wrap { display: none; }
  .wali-hafalan-mobile-list { display: grid; gap: 10px; padding: 12px; }
  .wali-hafalan-mobile-card { border: 1px solid var(--border-light); border-radius: 10px; background: #fff; padding: 10px; }
}
@media (min-width: 768px) { .wali-hafalan-mobile-list { display: none; } }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Progress Hafalan Al-Quran</div>
        <div class="page-header-sub">{{ $santri->nama }} — {{ $santri->kelas->nama_kelas ?? '-' }}</div>
    </div>
</div>

{{-- Summary --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-icon gold"><svg viewBox="0 0 24 24" fill="none" stroke="#9a7a1a" stroke-width="2"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg></div>
            <div class="stat-card-value">{{ number_format($persenProgressHafalan, 1) }}<span style="font-size:14px;font-weight:400;color:var(--txt3);">%</span></div>
            <div class="stat-card-label">Progress menuju khatam</div>
            <div class="progress-hijau mt-2"><div class="progress-fill" style="width:{{ min(100, $persenProgressHafalan) }}%;"></div></div>
            <div class="stat-card-change {{ $persenProgressHafalan >= 100 ? 'change-up' : 'change-warn' }}">
                {{ $totalHalaman }} / {{ $targetHalamanHafalan }} halaman tercatat
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-icon green"><svg viewBox="0 0 24 24" fill="none" stroke="#1a5c2e" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
            <div class="stat-card-value">{{ number_format($rataRataNilai, 1) }}</div>
            <div class="stat-card-label">Rata-rata Nilai Setoran</div>
            <div class="stat-card-change {{ $rataRataNilai >= 90 ? 'change-up' : ($rataRataNilai >= 75 ? 'change-warn' : 'change-down') }}">
                Grade {{ $rataRataNilai >= 90 ? 'A' : ($rataRataNilai >= 75 ? 'B' : ($rataRataNilai >= 60 ? 'C' : 'D')) }}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-icon blue"><svg viewBox="0 0 24 24" fill="none" stroke="#1a3c8e" stroke-width="2"><path d="M9 11l3 3L22 4"/></svg></div>
            <div class="stat-card-value">{{ $totalSetoran }}</div>
            <div class="stat-card-label">Total Setoran</div>
            <div class="stat-card-change change-up">{{ $totalMurojaah }} muroja'ah</div>
        </div>
    </div>
</div>

{{-- Riwayat Setoran --}}
<div class="card-custom">
    <div class="card-header-custom"><div class="card-title-custom">Riwayat Setoran Hafalan</div></div>
    <div class="table-responsive wali-hafalan-table-wrap">
        <table class="table-custom">
            <thead>
                <tr><th>No</th><th>Surat / Juz</th><th>Halaman</th><th>Nilai</th><th>Grade</th><th>Kategori</th><th>Jenis</th><th>Dicatat Oleh</th><th>Tanggal</th><th>Catatan</th></tr>
            </thead>
            <tbody>
                @forelse($hafalan as $idx => $hf)
                    <tr>
                        <td style="color:var(--txt3);font-size:12px;">{{ $hafalan->firstItem()+$idx }}</td>
                        <td><div class="td-name-main">{{ $hf->nama_surat }}</div><div class="td-name-sub">Juz {{ $hf->nomor_juz ?? '-' }}</div></td>
                        <td><span style="font-size:12px;">Hal. {{ $hf->halaman_dari }}–{{ $hf->halaman_sampai }}</span><div class="td-name-sub">{{ $hf->jumlah_halaman }} halaman</div></td>
                        <td style="font-size:14px;font-weight:700;">{{ $hf->nilai ? number_format($hf->nilai,1) : '-' }}</td>
                        <td>@if($hf->grade)<span class="badge-custom grade-{{ strtolower($hf->grade) }}">{{ $hf->grade }}</span>@else<span style="color:var(--txt3);">-</span>@endif</td>
                        <td>@if($hf->kategori)<span class="badge-custom {{ $hf->kategori_badge_color }}" style="font-size:10px;">{{ $hf->kategori_label }}</span>@else<span style="color:var(--txt3);">-</span>@endif</td>
                        <td><span class="badge-custom {{ $hf->jenis==='setoran_baru' ? 'badge-green':'badge-blue' }}" style="font-size:10px;">{{ $hf->jenis_label }}</span></td>
                        <td style="font-size:12px;">{{ Str::limit($hf->ustadz?->name ?? '-', 18) }}</td>
                        <td style="font-size:12px;color:var(--txt2);">{{ $hf->tanggal_setoran->format('d M Y') }}</td>
                        <td style="font-size:11px;color:var(--txt3);">{{ $hf->catatan ? Str::limit($hf->catatan,30) : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="10" class="text-center py-5" style="color:var(--txt3);">Belum ada data setoran hafalan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="wali-hafalan-mobile-list">
        @forelse($hafalan as $hf)
            <div class="wali-hafalan-mobile-card">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div class="td-name-main">{{ $hf->nama_surat }}</div>
                    <div class="d-flex gap-1">
                        @if($hf->kategori)<span class="badge-custom {{ $hf->kategori_badge_color }}" style="font-size:10px;">{{ $hf->kategori_label }}</span>@endif
                        <span class="badge-custom {{ $hf->jenis==='setoran_baru' ? 'badge-green':'badge-blue' }}" style="font-size:10px;">{{ $hf->jenis_label }}</span>
                    </div>
                </div>
                <div class="td-name-sub mb-1">Juz {{ $hf->nomor_juz ?? '-' }} · Hal {{ $hf->halaman_dari }}–{{ $hf->halaman_sampai }}</div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span style="font-size:12px;">Nilai {{ $hf->nilai ? number_format($hf->nilai,1) : '-' }}</span>
                    @if($hf->grade)<span class="badge-custom grade-{{ strtolower($hf->grade) }}">{{ $hf->grade }}</span>@endif
                </div>
                <div class="td-name-sub">Dicatat: {{ $hf->ustadz?->name ?? '-' }} · {{ $hf->tanggal_setoran->format('d M Y') }}</div>
            </div>
        @empty
            <div class="text-center py-3" style="color:var(--txt3);">Belum ada data setoran hafalan</div>
        @endforelse
    </div>
    <div class="pagination-custom">
        <div class="pagination-info">{{ $hafalan->firstItem() }}–{{ $hafalan->lastItem() }} dari {{ $hafalan->total() }}</div>
        <div class="d-flex gap-1">
            @foreach($hafalan->getUrlRange(max(1,$hafalan->currentPage()-2),min($hafalan->lastPage(),$hafalan->currentPage()+2)) as $page => $url)
                <a href="{{ $url }}" class="page-link {{ $page===$hafalan->currentPage()?'active':'' }}">{{ $page }}</a>
            @endforeach
        </div>
    </div>
</div>
@endsection

