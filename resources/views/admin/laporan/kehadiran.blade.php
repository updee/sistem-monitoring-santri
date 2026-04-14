{{-- resources/views/admin/laporan/kehadiran.blade.php --}}
@extends('layouts.app')
@section('title','Rekap Kehadiran')
@section('page-title','Rekap Kehadiran')
@section('breadcrumb','/ Laporan / <span>Kehadiran</span>')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Rekap Kehadiran Santri</div>
        <div class="page-header-sub">
            {{ \Carbon\Carbon::create($tahun,$bulan)->locale('id')->isoFormat('MMMM Y') }}
        </div>
    </div>
    <div class="d-flex gap-2 align-items-end">
        <form method="GET" class="d-flex gap-2">
            <select name="bulan" class="form-control-custom" style="width:130px;">
                @for($m=1;$m<=12;$m++)
                    <option value="{{ $m }}" {{ $m==$bulan?'selected':'' }}>
                        {{ \Carbon\Carbon::create(null,$m)->locale('id')->isoFormat('MMMM') }}
                    </option>
                @endfor
            </select>
            <select name="tahun" class="form-control-custom" style="width:90px;">
                @for($y=now()->year;$y>=2023;$y--)
                    <option value="{{ $y }}" {{ $y==$tahun?'selected':'' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="btn-hijau">Tampilkan</button>
        </form>
        <a href="{{ route('admin.laporan.export.kehadiran',['bulan'=>$bulan,'tahun'=>$tahun]) }}" class="btn-outline-hijau">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Excel
        </a>
        <button class="btn-outline-hijau" data-action="print">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Print
        </button>
    </div>
</div>

<div class="card-custom">
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>No</th><th>Santri</th><th>Kelas</th>
                    <th class="text-center" style="color:#1a5c2e;">Hadir</th>
                    <th class="text-center" style="color:#9a7a1a;">Izin</th>
                    <th class="text-center" style="color:#1a3c8e;">Sakit</th>
                    <th class="text-center" style="color:#c62828;">Alpha</th>
                    <th class="text-center">Total</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekap as $idx => $s)
                    <tr>
                        <td style="color:var(--txt3);font-size:12px;">{{ $idx+1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="td-avatar">{{ strtoupper(substr($s->nama,0,2)) }}</div>
                                <div>
                                    <div class="td-name-main">{{ $s->nama }}</div>
                                    <div class="td-name-sub">NIS: {{ $s->nis }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:12px;">{{ $s->kelas->nama_kelas??'-' }}</td>
                        <td class="text-center" style="font-weight:700;color:#1a5c2e;">{{ $s->hadir }}</td>
                        <td class="text-center" style="font-weight:700;color:#9a7a1a;">{{ $s->izin }}</td>
                        <td class="text-center" style="font-weight:700;color:#1a3c8e;">{{ $s->sakit }}</td>
                        <td class="text-center" style="font-weight:700;color:#c62828;">{{ $s->alpha }}</td>
                        <td class="text-center" style="font-size:12px;">{{ $s->total }}</td>
                        <td style="min-width:120px;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress-hijau flex-grow-1">
                                    <div class="progress-fill" style="width:{{ $s->persen }}%;background:{{ $s->persen>=85?'var(--hijau)':($s->persen>=70?'var(--emas)':'#c62828') }};"></div>
                                </div>
                                <span style="font-size:11px;font-weight:700;min-width:38px;color:{{ $s->persen>=85?'var(--hijau)':($s->persen>=70?'var(--emas-dark)':'#c62828') }}">
                                    {{ $s->persen }}%
                                </span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection


{{-- ════════════════════════════════════════════════
     resources/views/admin/laporan/hafalan.blade.php
     ════════════════════════════════════════════════ --}}
