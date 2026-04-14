{{-- resources/views/wali/izin/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Riwayat Izin')
@section('page-title', 'Riwayat Izin')
@section('breadcrumb', '/ <span>Riwayat Izin</span>')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Riwayat Pengajuan Izin</div>
        <div class="page-header-sub">{{ $santri?->nama ?? 'Santri belum terhubung' }}</div>
    </div>
    <a href="{{ route('wali_santri.izin.create') }}" class="btn-hijau">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:16px;height:16px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Ajukan Izin Baru
    </a>
</div>

<div class="row g-3">
    @forelse($izinList as $iz)
        <div class="col-lg-6">
            <div class="card-custom">
                <div class="card-header-custom" style="background:{{ $iz->status === 'menunggu' ? '#fffbf0' : ($iz->status === 'disetujui' ? 'var(--hijau-pale)' : '#fce8e8') }};">
                    <div>
                        <div style="font-size:13px;font-weight:700;color:var(--txt);">
                            {{ $iz->tanggal_mulai->format('d M Y') }} — {{ $iz->tanggal_kembali->format('d M Y') }}
                        </div>
                        <div style="font-size:11px;color:var(--txt3);">{{ $iz->durasi }} hari · Diajukan {{ $iz->created_at->diffForHumans() }}</div>
                    </div>
                    <span class="badge-custom badge-{{ $iz->status === 'menunggu' ? 'gold' : ($iz->status === 'disetujui' ? 'green' : 'red') }}">
                        {{ $iz->status_label }}
                    </span>
                </div>
                <div class="card-body-custom">
                    <div style="background:#f9fbf9;border-radius:8px;padding:10px 14px;margin-bottom:12px;">
                        <div style="font-size:10px;font-weight:700;color:var(--txt3);margin-bottom:3px;">Alasan</div>
                        <div style="font-size:13px;color:var(--txt2);line-height:1.5;">{{ $iz->alasan }}</div>
                    </div>
                    @if($iz->nama_penjemput)
                        <div style="font-size:12px;color:var(--txt2);margin-bottom:6px;">
                            <strong>Penjemput:</strong> {{ $iz->nama_penjemput }}
                            @if($iz->no_telepon_penjemput) · {{ $iz->no_telepon_penjemput }} @endif
                        </div>
                    @endif
                    @if($iz->catatan_admin)
                        <div style="background:{{ $iz->status === 'disetujui' ? 'var(--hijau-pale)' : '#fce8e8' }};border-radius:7px;padding:8px 12px;">
                            <div style="font-size:10px;font-weight:700;color:{{ $iz->status === 'disetujui' ? 'var(--hijau)':'#c62828' }};margin-bottom:2px;">
                                Catatan Admin
                            </div>
                            <div style="font-size:12px;color:var(--txt2);">{{ $iz->catatan_admin }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card-custom text-center py-5" style="color:var(--txt3);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="opacity:0.3;margin:0 auto 12px;display:block;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Belum ada riwayat pengajuan izin.
                <div class="mt-2"><a href="{{ route('wali_santri.izin.create') }}" style="color:var(--hijau);font-weight:600;">Ajukan izin pertama →</a></div>
            </div>
        </div>
    @endforelse
</div>
@endsection


{{-- ════════════════════════════════════════════════════════════
     resources/views/wali/izin/create.blade.php
     ════════════════════════════════════════════════════════════ --}}
