{{-- resources/views/ustadz/hafalan/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Hafalan Al-Quran')
@section('page-title', 'Hafalan Al-Quran')
@section('breadcrumb', '/ <span>Hafalan Al-Quran</span>')

@push('styles')
<style>
@media (max-width: 767.98px) {
  .hafalan-table-wrap { display: none; }
  .hafalan-mobile-list { display: grid; gap: 10px; padding: 12px; }
  .hafalan-mobile-card { border: 1px solid var(--border-light); border-radius: 10px; background: #fff; padding: 10px; }
}
@media (min-width: 768px) { .hafalan-mobile-list { display: none; } }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Catatan Hafalan Al-Quran</div>
        <div class="page-header-sub">Riwayat setoran dan muroja'ah seluruh santri</div>
    </div>
    <a href="{{ route('ustadz.hafalan.create') }}" class="btn-hijau">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Catat Setoran Baru
    </a>
</div>

{{-- Filter --}}
<div class="card-custom mb-4">
    <div class="card-body-custom">
        <form method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label-custom">Cari Santri</label>
                    <input type="text" name="search" class="form-control-custom" placeholder="Nama santri..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label-custom">Jenis Setoran</label>
                    <select name="jenis" class="form-control-custom">
                        <option value="">Semua Jenis</option>
                        <option value="setoran_baru" {{ request('jenis') === 'setoran_baru' ? 'selected' : '' }}>Setoran Baru</option>
                        <option value="murojaah"     {{ request('jenis') === 'murojaah'     ? 'selected' : '' }}>Muroja'ah</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label-custom">Grade</label>
                    <select name="grade" class="form-control-custom">
                        <option value="">Semua Grade</option>
                        <option value="A" {{ request('grade') === 'A' ? 'selected' : '' }}>A (≥90)</option>
                        <option value="B" {{ request('grade') === 'B' ? 'selected' : '' }}>B (≥75)</option>
                        <option value="C" {{ request('grade') === 'C' ? 'selected' : '' }}>C (≥60)</option>
                        <option value="D" {{ request('grade') === 'D' ? 'selected' : '' }}>D (&lt;60)</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn-hijau flex-grow-1 justify-content-center">Filter</button>
                    <a href="{{ route('ustadz.hafalan.index') }}" class="btn-outline-hijau">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card-custom">
    <div class="table-responsive hafalan-table-wrap">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Santri</th>
                    <th>Surat / Juz</th>
                    <th>Halaman</th>
                    <th>Nilai</th>
                    <th>Grade</th>
                    <th>Jenis</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hafalan as $idx => $hf)
                    <tr>
                        <td style="color:var(--txt3);font-size:12px;">{{ $hafalan->firstItem() + $idx }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="td-avatar">{{ strtoupper(substr($hf->santri->nama, 0, 2)) }}</div>
                                <div>
                                    <div class="td-name-main">{{ Str::limit($hf->santri->nama, 20) }}</div>
                                    <div class="td-name-sub">{{ $hf->santri->kelas->nama_kelas ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="td-name-main">{{ $hf->nama_surat }}</div>
                            <div class="td-name-sub">Juz {{ $hf->nomor_juz ?? '-' }}</div>
                        </td>
                        <td>
                            <span style="font-size:12px;">
                                Hal. {{ $hf->halaman_dari }}–{{ $hf->halaman_sampai }}
                            </span>
                            <div class="td-name-sub">{{ $hf->jumlah_halaman }} halaman</div>
                        </td>
                        <td style="font-size:14px;font-weight:700;color:var(--txt);">
                            {{ $hf->nilai ? number_format($hf->nilai, 1) : '-' }}
                        </td>
                        <td>
                            @if($hf->grade)
                                <span class="badge-custom grade-{{ strtolower($hf->grade) }}">{{ $hf->grade }}</span>
                            @else
                                <span style="color:var(--txt3);">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-custom {{ $hf->jenis === 'setoran_baru' ? 'badge-green' : 'badge-blue' }}">
                                {{ $hf->jenis_label }}
                            </span>
                        </td>
                        <td style="font-size:12px;color:var(--txt2);">
                            {{ $hf->tanggal_setoran->format('d M Y') }}
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('ustadz.hafalan.edit', $hf) }}" class="btn-edit-custom">Edit</a>
                                <form method="POST" action="{{ route('ustadz.hafalan.destroy', $hf) }}"
                                    onsubmit="return confirm('Hapus data setoran ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger-custom">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5" style="color:var(--txt3);">
                            Belum ada data hafalan. <a href="{{ route('ustadz.hafalan.create') }}" style="color:var(--hijau);font-weight:600;">Catat sekarang →</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="hafalan-mobile-list">
        @forelse($hafalan as $hf)
            <div class="hafalan-mobile-card">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="td-avatar">{{ strtoupper(substr($hf->santri->nama, 0, 2)) }}</div>
                    <div>
                        <div class="td-name-main">{{ $hf->santri->nama }}</div>
                        <div class="td-name-sub">{{ $hf->tanggal_setoran->format('d M Y') }}</div>
                    </div>
                    <span class="badge-custom {{ $hf->jenis === 'setoran_baru' ? 'badge-green' : 'badge-blue' }} ms-auto">{{ $hf->jenis_label }}</span>
                </div>
                <div class="td-name-sub mb-2">{{ $hf->nama_surat }} · Juz {{ $hf->nomor_juz ?? '-' }} · {{ $hf->jumlah_halaman }} hal</div>
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span style="font-size:12px;">Nilai: {{ $hf->nilai ? number_format($hf->nilai,1) : '-' }}</span>
                    @if($hf->grade)<span class="badge-custom grade-{{ strtolower($hf->grade) }}">{{ $hf->grade }}</span>@endif
                </div>
                <div class="d-flex gap-1">
                    <a href="{{ route('ustadz.hafalan.edit', $hf) }}" class="btn-edit-custom">Edit</a>
                    <form method="POST" action="{{ route('ustadz.hafalan.destroy', $hf) }}" onsubmit="return confirm('Hapus data setoran ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger-custom">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-3" style="color:var(--txt3);">Belum ada data hafalan</div>
        @endforelse
    </div>
    <div class="pagination-custom">
        <div class="pagination-info">{{ $hafalan->firstItem() }}–{{ $hafalan->lastItem() }} dari {{ $hafalan->total() }} setoran</div>
        <div class="d-flex gap-1">
            @foreach($hafalan->getUrlRange(max(1,$hafalan->currentPage()-2), min($hafalan->lastPage(),$hafalan->currentPage()+2)) as $page => $url)
                <a href="{{ $url }}" class="page-link {{ $page === $hafalan->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
        </div>
    </div>
</div>
@endsection


{{-- ════════════════════════════════════════════════════════════
     resources/views/ustadz/hafalan/create.blade.php
     ════════════════════════════════════════════════════════════ --}}
{{-- (Save as separate file: ustadz/hafalan/create.blade.php) --}}
