@extends('layouts.app')
@section('title','Pencapaian')
@section('page-title','Pencapaian')
@section('breadcrumb','/ <span>Pencapaian</span>')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Pencapaian & Prestasi</div>
        <div class="page-header-sub">{{ $santri->nama }}</div>
    </div>
</div>

<div class="row g-3">
    @forelse($pencapaian as $pr)
        <div class="col-md-6 col-lg-4">
            <div class="card-custom h-100">
                <div class="card-body-custom">
                    <div style="font-size:13px;font-weight:700;color:var(--txt);line-height:1.3;">
                        {{ $pr->judul_pencapaian }}
                    </div>
                    <div style="font-size:11px;color:var(--txt3);margin-top:6px;">
                        {{ $pr->tingkat_label }} · {{ $pr->tanggal->locale('id')->isoFormat('D MMMM Y') }}
                    </div>
                    @if($pr->keterangan)
                        <div style="font-size:11px;color:var(--txt2);background:#f9fbf9;border-radius:6px;padding:7px 10px;margin-top:10px;line-height:1.5;">
                            {{ $pr->keterangan }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card-custom text-center py-5" style="color:var(--txt3);">
                Belum ada data pencapaian yang tercatat.
            </div>
        </div>
    @endforelse
</div>
@endsection

