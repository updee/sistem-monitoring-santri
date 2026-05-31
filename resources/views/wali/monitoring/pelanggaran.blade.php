@extends('layouts.app')
@section('title','Catatan Pelanggaran')
@section('page-title','Catatan Pelanggaran')
@section('breadcrumb','/ <span>Pelanggaran</span>')

@push('styles')
<style>
@media (max-width: 767.98px) {
    .pelanggaran-desktop-table { display: none; }
    .pelanggaran-mobile-list { display: grid; gap: 10px; }
    .pel-card {
        background: #fff;
        border: 1px solid var(--border-light);
        border-radius: 10px;
        padding: 10px 12px;
    }
    .pel-card-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 8px;
    }
    .pel-title { font-size: 12px; font-weight: 700; color: var(--txt); line-height: 1.3; }
    .pel-sub { font-size: 10px; color: var(--txt3); margin-top: 2px; }
    .pel-meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px 10px;
        margin-top: 6px;
    }
    .pel-meta-item { font-size: 10px; color: var(--txt2); }
    .pel-meta-item strong { display: block; color: var(--txt3); font-size: 9px; text-transform: uppercase; letter-spacing: .04em; }
}
@media (min-width: 768px) {
    .pelanggaran-mobile-list { display: none; }
}
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Catatan Pelanggaran Disiplin</div>
        <div class="page-header-sub">{{ $santri->nama }}</div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card" style="border-left:4px solid {{ $totalPoin>=75?'#c62828':($totalPoin>0?'var(--emas)':'var(--hijau)') }};">
            <div class="stat-card-value" style="color:{{ $totalPoin>=75?'#c62828':($totalPoin>0?'var(--emas-dark)':'var(--hijau)') }}">
                {{ $totalPoin }}
            </div>
            <div class="stat-card-label">Total Akumulasi Poin</div>
            <div class="stat-card-change {{ $totalPoin>=75?'change-down':($totalPoin>0?'change-warn':'change-up') }}">
                {{ $totalPoin>=75?'Kritis — perlu perhatian segera':($totalPoin>0?'Perlu ditingkatkan kedisiplinan':'Baik, tidak ada pelanggaran') }}
            </div>
        </div>
    </div>
    @if($santri->active_sp)
        <div class="col-md-8">
            <div class="alert-danger" style="border-radius:10px;padding:14px 18px;font-size:13px;border-left:4px solid #c62828;">
                <strong>Perhatian: {{ strtoupper($santri->active_sp->jenis_sp) }} Aktif!</strong> Akumulasi poin pelanggaran telah mencapai {{ $santri->active_sp->total_poin }} poin. Mohon segera menghubungi pihak pesantren untuk pembinaan lebih lanjut.
            </div>
        </div>
    @elseif($totalPoin >= 75)
        <div class="col-md-8">
            <div class="alert-danger" style="border-radius:10px;padding:14px 18px;font-size:13px;border-left:4px solid #c62828;">
                <strong>Perhatian!</strong> Akumulasi poin pelanggaran telah mencapai {{ $totalPoin }} poin. Mohon segera menghubungi pihak pesantren untuk pembinaan lebih lanjut.
            </div>
        </div>
    @endif
</div>

<div class="card-custom">
    <div class="card-header-custom"><div class="card-title-custom">Riwayat Pelanggaran</div></div>
    <div class="table-responsive pelanggaran-desktop-table">
        <table class="table-custom">
            <thead>
                <tr><th>No</th><th>Jenis Pelanggaran</th><th>Tingkat</th><th>Poin</th><th>Tanggal</th><th>Tindak Lanjut</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($pelanggaran as $idx => $p)
                    <tr>
                        <td style="color:var(--txt3);font-size:12px;">{{ $pelanggaran->firstItem()+$idx }}</td>
                        <td>
                            <div class="td-name-main">{{ $p->jenis_pelanggaran }}</div>
                            @if($p->keterangan)<div class="td-name-sub">{{ Str::limit($p->keterangan,50) }}</div>@endif
                        </td>
                        <td>
                            @php $t=$p->kategori?->tingkat; @endphp
                            <span class="badge-custom {{ ['ringan'=>'badge-gold','sedang'=>'badge-purple','berat'=>'badge-red'][$t??'']??'badge-gray' }}" style="font-size:10px;">{{ ucfirst($t??'-') }}</span>
                        </td>
                        <td style="font-size:16px;font-weight:800;color:{{ $p->poin_sanksi>=20?'#c62828':($p->poin_sanksi>=10?'var(--emas-dark)':'var(--txt)') }};">{{ $p->poin_sanksi }}</td>
                        <td style="font-size:12px;">{{ $p->tanggal->locale('id')->isoFormat('D MMMM Y') }}</td>
                        <td><span class="badge-custom {{ $p->status_tindak_lanjut==='sudah'?'badge-green':'badge-gold' }}" style="font-size:10px;">{{ $p->status_tindak_lanjut==='sudah'?'Selesai':'Belum' }}</span></td>
                        <td>
                            <button type="button" class="btn-view-custom" data-bs-toggle="modal" data-bs-target="#detailPelanggaran{{ $p->id }}">
                                Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5" style="color:var(--txt3);">
                            Alhamdulillah, tidak ada catatan pelanggaran
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-body-custom pelanggaran-mobile-list">
        @forelse($pelanggaran as $idx => $p)
            @php $t = $p->kategori?->tingkat; @endphp
            <div class="pel-card">
                <div class="pel-card-head">
                    <div>
                        <div class="pel-title">{{ $p->jenis_pelanggaran }}</div>
                        <div class="pel-sub">{{ $p->tanggal->locale('id')->isoFormat('D MMMM Y') }}</div>
                    </div>
                    <span class="badge-custom {{ ['ringan'=>'badge-gold','sedang'=>'badge-purple','berat'=>'badge-red'][$t??'']??'badge-gray' }}" style="font-size:10px;">
                        {{ ucfirst($t ?? '-') }}
                    </span>
                </div>

                <div class="pel-meta">
                    <div class="pel-meta-item">
                        <strong>Poin</strong>
                        {{ $p->poin_sanksi }}
                    </div>
                    <div class="pel-meta-item">
                        <strong>Status</strong>
                        {{ $p->status_tindak_lanjut==='sudah'?'Selesai':'Belum' }}
                    </div>
                </div>

                <div class="mt-2 d-flex justify-content-end">
                    <button type="button" class="btn-view-custom" data-bs-toggle="modal" data-bs-target="#detailPelanggaran{{ $p->id }}">
                        Detail
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-4" style="color:var(--txt3);font-size:12px;">
                Alhamdulillah, tidak ada catatan pelanggaran
            </div>
        @endforelse
    </div>
</div>

@foreach($pelanggaran as $p)
    @php $t = $p->kategori?->tingkat; @endphp
    <div class="modal fade" id="detailPelanggaran{{ $p->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pelanggaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="font-size:13px;">
                    <p><strong>Santri:</strong> {{ $santri->nama }}</p>
                    <p><strong>Jenis Pelanggaran:</strong> {{ $p->jenis_pelanggaran }}</p>
                    <p><strong>Tingkat:</strong> {{ ucfirst($t ?? '-') }}</p>
                    <p><strong>Poin Sanksi:</strong> {{ $p->poin_sanksi }}</p>
                    <p><strong>Tanggal Kejadian:</strong> {{ $p->tanggal->locale('id')->isoFormat('D MMMM Y') }}</p>
                    <p><strong>Status Tindak Lanjut:</strong> {{ $p->status_tindak_lanjut==='sudah'?'Sudah ditindaklanjuti':'Belum ditindaklanjuti' }}</p>
                    <p><strong>Keterangan:</strong><br>{{ $p->keterangan ?: '-' }}</p>
                    <p><strong>Tindak Lanjut:</strong><br>{{ $p->tindak_lanjut ?: '-' }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-outline-hijau" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

