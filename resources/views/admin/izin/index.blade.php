{{-- resources/views/admin/izin/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Pengajuan Izin')
@section('page-title', 'Pengajuan Izin')
@section('breadcrumb', '/ <span>Pengajuan Izin</span>')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Kelola Pengajuan Izin</div>
        <div class="page-header-sub">
            @if($jumlahMenunggu > 0)
                <span style="color:var(--emas-dark);font-weight:600;">{{ $jumlahMenunggu }} pengajuan menunggu persetujuan Anda</span>
            @else
                Semua pengajuan izin sudah diproses
            @endif
        </div>
    </div>
</div>

{{-- Filter Tab --}}
<div class="card-custom mb-4">
    <div class="card-body-custom" style="padding:12px 18px;">
        <div class="d-flex gap-2 flex-wrap">
            @foreach(['semua' => 'Semua', 'menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'] as $val => $label)
                <a href="{{ route('admin.izin.index', ['status' => $val]) }}"
                    class="{{ $status === $val ? 'btn-hijau' : 'btn-outline-hijau' }}"
                    style="padding:6px 14px;font-size:12px;">
                    {{ $label }}
                    @if($val === 'menunggu' && $jumlahMenunggu > 0)
                        <span style="background:{{ $status === 'menunggu' ? 'rgba(255,255,255,0.25)' : 'var(--emas)' }};color:{{ $status === 'menunggu' ? '#fff' : 'var(--hijau)' }};font-size:10px;font-weight:800;padding:1px 6px;border-radius:8px;margin-left:4px;">{{ $jumlahMenunggu }}</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>

{{-- Izin Cards --}}
<div class="row g-3">
    @forelse($izinList as $izin)
        <div class="col-lg-6">
            <div class="card-custom">
                {{-- Header --}}
                <div class="card-header-custom" style="background:{{ $izin->status === 'menunggu' ? '#fffbf0' : ($izin->status === 'disetujui' ? 'var(--hijau-pale)' : '#fce8e8') }};">
                    <div class="d-flex align-items-center gap-3">
                        <div class="td-avatar" style="width:38px;height:38px;font-size:13px;">
                            {{ strtoupper(substr($izin->santri->nama, 0, 2)) }}
                        </div>
                        <div>
                            <div class="td-name-main">{{ $izin->santri->nama }}</div>
                            <div class="td-name-sub">{{ $izin->santri->kelas->nama_kelas ?? '-' }} · NIS {{ $izin->santri->nis }}</div>
                        </div>
                    </div>
                    <span class="badge-custom badge-{{ $izin->status === 'menunggu' ? 'gold' : ($izin->status === 'disetujui' ? 'green' : 'red') }}">
                        {{ $izin->status_label }}
                    </span>
                </div>

                {{-- Body --}}
                <div class="card-body-custom">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div style="font-size:10px;font-weight:700;color:var(--txt3);margin-bottom:2px;">Tanggal Mulai</div>
                            <div style="font-size:13px;font-weight:600;">{{ $izin->tanggal_mulai->format('d M Y') }}</div>
                        </div>
                        <div class="col-6">
                            <div style="font-size:10px;font-weight:700;color:var(--txt3);margin-bottom:2px;">Tanggal Kembali</div>
                            <div style="font-size:13px;font-weight:600;">{{ $izin->tanggal_kembali->format('d M Y') }}</div>
                        </div>
                        <div class="col-6">
                            <div style="font-size:10px;font-weight:700;color:var(--txt3);margin-bottom:2px;">Durasi</div>
                            <div style="font-size:13px;font-weight:600;">{{ $izin->durasi }} hari</div>
                        </div>
                        <div class="col-6">
                            <div style="font-size:10px;font-weight:700;color:var(--txt3);margin-bottom:2px;">Diajukan Oleh</div>
                            <div style="font-size:13px;font-weight:600;">{{ $izin->pengaju->name ?? '-' }}</div>
                        </div>
                    </div>

                    <div style="background:#f9fbf9;border-radius:7px;padding:10px 12px;margin-bottom:12px;">
                        <div style="font-size:10px;font-weight:700;color:var(--txt3);margin-bottom:3px;">Alasan Izin</div>
                        <div style="font-size:13px;color:var(--txt2);line-height:1.5;">{{ $izin->alasan }}</div>
                    </div>

                    @if($izin->status === 'menunggu')
                        {{-- Approval Form --}}
                        <div class="row g-2">
                            <div class="col-12">
                                <input type="text" id="catatan_{{ $izin->id }}" class="form-control-custom"
                                    placeholder="Catatan admin (opsional)..." style="height:36px;">
                            </div>
                            <div class="col-6">
                                <form method="POST" action="{{ route('admin.izin.tolak', $izin) }}" id="formTolak{{ $izin->id }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="catatan_admin" id="catatanTolak{{ $izin->id }}">
                                    <button type="submit" class="btn-danger-custom w-100" style="height:36px;font-size:12px;"
                                        onclick="document.getElementById('catatanTolak{{ $izin->id }}').value = document.getElementById('catatan_{{ $izin->id }}').value; return confirm('Tolak izin ini?')">
                                        Tolak Izin
                                    </button>
                                </form>
                            </div>
                            <div class="col-6">
                                <form method="POST" action="{{ route('admin.izin.setujui', $izin) }}" id="formSetujui{{ $izin->id }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="catatan_admin" id="catatanSetujui{{ $izin->id }}">
                                    <button type="submit" class="btn-hijau w-100 justify-content-center" style="height:36px;"
                                        onclick="document.getElementById('catatanSetujui{{ $izin->id }}').value = document.getElementById('catatan_{{ $izin->id }}').value;">
                                        Setujui Izin
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif($izin->catatan_admin)
                        <div style="font-size:12px;color:var(--txt3);">
                            <strong>Catatan:</strong> {{ $izin->catatan_admin }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card-custom text-center py-5" style="color:var(--txt3);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="opacity:0.3;margin:0 auto 12px;display:block;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Tidak ada pengajuan izin
                @if($status !== 'semua') dengan status "{{ $status }}"@endif
            </div>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($izinList->hasPages())
    <div class="d-flex justify-content-center gap-1 mt-4">
        @foreach($izinList->getUrlRange(max(1,$izinList->currentPage()-2), min($izinList->lastPage(),$izinList->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}" class="page-link {{ $page === $izinList->currentPage() ? 'active' : '' }}">{{ $page }}</a>
        @endforeach
    </div>
@endif
@endsection


{{-- ════════════════════════════════════════════════════════════
     resources/views/ustadz/kehadiran/input.blade.php
     ════════════════════════════════════════════════════════════ --}}
{{-- (Save as: resources/views/ustadz/kehadiran/input.blade.php) --}}
