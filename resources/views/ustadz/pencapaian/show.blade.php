@extends('layouts.app')
@section('title','Detail Pencapaian')
@section('page-title','Detail Pencapaian')
@section('breadcrumb','/ Pencapaian / <span>Detail</span>')

@section('content')
<div class="page-header"><div><div class="page-header-title">Detail Pencapaian</div></div><a href="{{ route('ustadz.pencapaian.index') }}" class="btn-outline-hijau">Kembali</a></div>
<div class="card-custom"><div class="card-body-custom">
    <p><strong>Santri:</strong> {{ $pencapaian->santri?->nama }}</p>
    <p><strong>Judul:</strong> {{ $pencapaian->judul_pencapaian }}</p>
    <p><strong>Tingkat:</strong> {{ $pencapaian->tingkat }}</p>
    <p><strong>Peringkat:</strong> {{ $pencapaian->peringkat }}</p>
    <p><strong>Tanggal:</strong> {{ $pencapaian->tanggal?->format('d M Y') }}</p>
</div></div>
@endsection

