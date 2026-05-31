{{-- resources/views/ustadz/pelanggaran/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Pelanggaran Disiplin')
@section('page-title', 'Pelanggaran Disiplin')
@section('breadcrumb', '/ <span>Pelanggaran</span>')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Catatan Pelanggaran Disiplin</div>
        <div class="page-header-sub">Rekap pelanggaran dan poin sanksi santri</div>
    </div>
    <a href="{{ route('ustadz.pelanggaran.create') }}" class="btn-hijau">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Catat Pelanggaran
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
                    <label class="form-label-custom">Kategori / Tingkat</label>
                    <select name="tingkat" class="form-control-custom">
                        <option value="">Semua Tingkat</option>
                        <option value="ringan" {{ request('tingkat') === 'ringan' ? 'selected' : '' }}>Ringan</option>
                        <option value="sedang" {{ request('tingkat') === 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="berat"  {{ request('tingkat') === 'berat'  ? 'selected' : '' }}>Berat</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label-custom">Bulan</label>
                    <input type="month" name="bulan" class="form-control-custom" value="{{ request('bulan', date('Y-m')) }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn-hijau flex-grow-1 justify-content-center">Filter</button>
                    <a href="{{ route('ustadz.pelanggaran.index') }}" class="btn-outline-hijau">Reset</a>
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
                    <th>No</th>
                    <th>Santri</th>
                    <th>Jenis Pelanggaran</th>
                    <th>Tingkat</th>
                    <th>Poin Sanksi</th>
                    <th>Total Poin</th>
                    <th>Tanggal</th>
                    <th>Tindak Lanjut</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pelanggaran as $idx => $p)
                    <tr>
                        <td style="color:var(--txt3);font-size:12px;">{{ $pelanggaran->firstItem() + $idx }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="td-avatar">{{ strtoupper(substr($p->santri->nama, 0, 2)) }}</div>
                                <div>
                                    <div class="td-name-main">{{ Str::limit($p->santri->nama, 20) }}</div>
                                    <div class="td-name-sub">{{ $p->santri->kelas->nama_kelas ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="td-name-main">{{ $p->jenis_pelanggaran }}</div>
                            @if($p->keterangan)
                                <div class="td-name-sub">{{ Str::limit($p->keterangan, 30) }}</div>
                            @endif
                        </td>
                        <td>
                            @php
                                $tingkat = $p->kategori?->tingkat;
                                $tingkatClass = match($tingkat) { 'ringan' => 'badge-gold', 'sedang' => 'badge-purple', 'berat' => 'badge-red', default => 'badge-gray' };
                            @endphp
                            <span class="badge-custom {{ $tingkatClass }}">{{ ucfirst($tingkat ?? '-') }}</span>
                        </td>
                        <td style="font-size:15px;font-weight:800;color:var(--emas-dark);">
                            {{ $p->poin_sanksi }}
                        </td>
                        <td style="font-size:13px;font-weight:700;">
                            @php
                                $totalPoin = $p->santri->total_poin_pelanggaran ?? 0;
                                $activeSp = $p->santri->active_sp;
                            @endphp
                            <div style="color:{{ $totalPoin >= 75 ? '#c62828' : 'var(--txt)' }}">{{ $totalPoin }}</div>
                            @if($activeSp)
                                <span class="badge-custom badge-red mt-1" style="font-size:10px;">{{ $activeSp->jenis_sp }}</span>
                            @endif
                        </td>
                        <td style="font-size:12px;color:var(--txt2);">{{ $p->tanggal->format('d M Y') }}</td>
                        <td>
                            <span class="badge-custom {{ $p->status_tindak_lanjut === 'sudah' ? 'badge-green' : 'badge-gold' }}">
                                {{ $p->status_tindak_lanjut === 'sudah' ? 'Selesai' : 'Belum' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="{{ route('ustadz.pelanggaran.edit', $p) }}" class="btn-edit-custom">Edit</a>
                                <form method="POST" action="{{ route('ustadz.pelanggaran.destroy', $p) }}"
                                    onsubmit="return confirm('Hapus catatan pelanggaran ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger-custom">Hapus</button>
                                </form>
                                @if($activeSp)
                                    <a href="{{ route('ustadz.pelanggaran.print-sp', $activeSp->id) }}" target="_blank" class="btn-view-custom mt-1" style="width:100%;text-align:center;">Cetak {{ $activeSp->jenis_sp }}</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5" style="color:var(--txt3);">
                            Tidak ada data pelanggaran ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-custom">
        <div class="pagination-info">{{ $pelanggaran->firstItem() }}–{{ $pelanggaran->lastItem() }} dari {{ $pelanggaran->total() }} data</div>
        <div class="d-flex gap-1">
            @foreach($pelanggaran->getUrlRange(max(1,$pelanggaran->currentPage()-2), min($pelanggaran->lastPage(),$pelanggaran->currentPage()+2)) as $page => $url)
                <a href="{{ $url }}" class="page-link {{ $page === $pelanggaran->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
        </div>
    </div>
</div>
@endsection


{{-- ════════════════════════════════════════════════════════════
     resources/views/ustadz/pelanggaran/create.blade.php
     ════════════════════════════════════════════════════════════ --}}
