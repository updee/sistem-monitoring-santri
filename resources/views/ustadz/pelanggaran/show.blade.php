@extends('layouts.app')
@section('title','Detail Pelanggaran')
@section('page-title','Detail Pelanggaran')
@section('breadcrumb','/ Pelanggaran / <span>Detail</span>')

@section('content')
<div class="page-header"><div><div class="page-header-title">Detail Pelanggaran</div></div><a href="{{ route('ustadz.pelanggaran.index') }}" class="btn-outline-hijau">Kembali</a></div>
<div class="card-custom"><div class="card-body-custom">
    <p><strong>Santri:</strong> {{ $pelanggaran->santri?->nama }}</p>
    <p><strong>Jenis:</strong> {{ $pelanggaran->jenis_pelanggaran }}</p>
    <p><strong>Poin:</strong> {{ $pelanggaran->poin_sanksi }}</p>
    <p><strong>Tanggal:</strong> {{ $pelanggaran->tanggal?->format('d M Y') }}</p>
    <p><strong>Keterangan:</strong> {{ $pelanggaran->keterangan ?? '-' }}</p>
</div></div>
@endsection

