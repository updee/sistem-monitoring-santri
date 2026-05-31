{{-- resources/views/ustadz/pelanggaran/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Pelanggaran')
@section('page-title', 'Detail Pelanggaran')
@section('breadcrumb', '/ <a href="' . route('ustadz.pelanggaran.index') . '">Pelanggaran</a> / <span>Detail</span>')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Detail Pelanggaran</div>
        <div class="page-header-sub">{{ $pelanggaran->santri->nama ?? '-' }} — {{ $pelanggaran->tanggal->isoFormat('D MMMM Y') }}</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('ustadz.pelanggaran.edit', $pelanggaran) }}" class="btn-edit-custom">Edit</a>
        <a href="{{ route('ustadz.pelanggaran.index') }}" class="btn-outline-hijau">← Kembali</a>
    </div>
</div>

<div class="card-custom">
    <div class="card-body-custom">
        <div class="row g-3">
            <div class="col-md-6">
                <div style="margin-bottom:14px;">
                    <div style="font-size:11px;color:var(--txt3);font-weight:600;text-transform:uppercase;">Santri</div>
                    <div style="font-size:14px;font-weight:700;">{{ $pelanggaran->santri->nama ?? '-' }}</div>
                    <div style="font-size:12px;color:var(--txt3);">NIS: {{ $pelanggaran->santri->nis ?? '-' }} · {{ $pelanggaran->santri->kelas->nama_kelas ?? '-' }}</div>
                </div>
                <div style="margin-bottom:14px;">
                    <div style="font-size:11px;color:var(--txt3);font-weight:600;text-transform:uppercase;">Jenis Pelanggaran</div>
                    <div style="font-size:14px;font-weight:600;">{{ $pelanggaran->jenis_pelanggaran }}</div>
                </div>
                <div style="margin-bottom:14px;">
                    <div style="font-size:11px;color:var(--txt3);font-weight:600;text-transform:uppercase;">Kategori</div>
                    <div>
                        @if($pelanggaran->kategori)
                            <span class="badge-custom {{ ['ringan'=>'badge-gold','sedang'=>'badge-purple','berat'=>'badge-red'][$pelanggaran->kategori->tingkat ?? ''] ?? 'badge-gray' }}">
                                {{ $pelanggaran->kategori->nama_kategori }} ({{ ucfirst($pelanggaran->kategori->tingkat) }})
                            </span>
                        @else
                            -
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div style="margin-bottom:14px;">
                    <div style="font-size:11px;color:var(--txt3);font-weight:600;text-transform:uppercase;">Tanggal</div>
                    <div style="font-size:14px;">{{ $pelanggaran->tanggal->isoFormat('dddd, D MMMM Y') }}</div>
                </div>
                <div style="margin-bottom:14px;">
                    <div style="font-size:11px;color:var(--txt3);font-weight:600;text-transform:uppercase;">Poin Sanksi</div>
                    <div style="font-size:20px;font-weight:800;color:#c62828;">{{ $pelanggaran->poin_sanksi }}</div>
                </div>
                <div style="margin-bottom:14px;">
                    <div style="font-size:11px;color:var(--txt3);font-weight:600;text-transform:uppercase;">Status Tindak Lanjut</div>
                    <span class="badge-custom {{ $pelanggaran->status_tindak_lanjut === 'sudah' ? 'badge-green' : 'badge-gold' }}">
                        {{ $pelanggaran->status_tindak_lanjut === 'sudah' ? 'Selesai' : 'Belum' }}
                    </span>
                </div>
                <div style="margin-bottom:14px;">
                    <div style="font-size:11px;color:var(--txt3);font-weight:600;text-transform:uppercase;">Pencatat</div>
                    <div>{{ $pelanggaran->pencatat->name ?? '-' }}</div>
                </div>
            </div>
        </div>
        @if($pelanggaran->keterangan)
        <div style="margin-top:10px;padding-top:14px;border-top:1px solid var(--border-light);">
            <div style="font-size:11px;color:var(--txt3);font-weight:600;text-transform:uppercase;margin-bottom:4px;">Keterangan</div>
            <div style="font-size:13px;">{{ $pelanggaran->keterangan }}</div>
        </div>
        @endif
        @if($pelanggaran->tindak_lanjut)
        <div style="margin-top:10px;padding-top:14px;border-top:1px solid var(--border-light);">
            <div style="font-size:11px;color:var(--txt3);font-weight:600;text-transform:uppercase;margin-bottom:4px;">Tindak Lanjut</div>
            <div style="font-size:13px;">{{ $pelanggaran->tindak_lanjut }}</div>
        </div>
        @endif
    </div>
</div>
@endsection
