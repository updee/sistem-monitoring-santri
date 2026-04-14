{{-- ════════════════════════════════════════════════
     resources/views/admin/laporan/index.blade.php
     ════════════════════════════════════════════════ --}}
@extends('layouts.app')
@section('title','Laporan & Ekspor')
@section('page-title','Laporan & Ekspor')
@section('breadcrumb','/ <span>Laporan</span>')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Laporan & Ekspor Data</div>
        <div class="page-header-sub">Unduh laporan sistem dalam format Excel</div>
    </div>
</div>

<div class="row g-3">
    @foreach([
        ['title'=>'Data Santri','icon'=>'M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 7a4 4 0 100-8 4 4 0 000 8z','desc'=>'Seluruh data santri aktif beserta informasi kelas, kamar, dan wali santri.','route'=>'admin.laporan.export.santri','color'=>'green','params'=>[]],
        ['title'=>'Laporan Hafalan','icon'=>'M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2zM22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z','desc'=>'Rekap setoran hafalan santri per bulan beserta nilai dan grade.','route'=>'admin.laporan.export.hafalan','color'=>'gold','params'=>['bulan','tahun']],
        ['title'=>'Rekap Kehadiran','icon'=>'M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11','desc'=>'Rekapitulasi kehadiran santri per bulan (hadir, izin, sakit, alpha).','route'=>'admin.laporan.export.kehadiran','color'=>'blue','params'=>['bulan','tahun']],
        ['title'=>'Laporan Pelanggaran','icon'=>'M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z','desc'=>'Catatan pelanggaran disiplin santri beserta poin sanksi per bulan.','route'=>'admin.laporan.export.pelanggaran','color'=>'red','params'=>['bulan','tahun']],
    ] as $lap)
    <div class="col-md-6">
        <div class="card-custom h-100">
            <div class="card-body-custom">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="stat-card-icon {{ $lap['color'] }}" style="flex-shrink:0;margin-bottom:0;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="{{ ['green'=>'#1a5c2e','gold'=>'#9a7a1a','blue'=>'#1a3c8e','red'=>'#c62828'][$lap['color']] }}" stroke-width="2"><path d="{{ $lap['icon'] }}"/></svg>
                    </div>
                    <div>
                        <div style="font-size:15px;font-weight:700;color:var(--txt);">{{ $lap['title'] }}</div>
                        <div style="font-size:12px;color:var(--txt3);margin-top:3px;line-height:1.5;">{{ $lap['desc'] }}</div>
                    </div>
                </div>
                @if(in_array('bulan', $lap['params']))
                    <form method="GET" action="{{ route($lap['route']) }}" class="d-flex gap-2 align-items-end">
                        <div style="flex:1;">
                            <label class="form-label-custom">Bulan</label>
                            <select name="bulan" class="form-control-custom">
                                @for($m=1;$m<=12;$m++)
                                    <option value="{{ $m }}" {{ $m == now()->month ? 'selected':'' }}>
                                        {{ \Carbon\Carbon::create(null,$m)->locale('id')->isoFormat('MMMM') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div style="flex:1;">
                            <label class="form-label-custom">Tahun</label>
                            <select name="tahun" class="form-control-custom">
                                @for($y=now()->year;$y>=2023;$y--)
                                    <option value="{{ $y }}" {{ $y == now()->year ? 'selected':'' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <button type="submit" class="btn-hijau" style="white-space:nowrap;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Unduh Excel
                        </button>
                    </form>
                @else
                    <a href="{{ route($lap['route']) }}" class="btn-hijau">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Unduh Excel
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
