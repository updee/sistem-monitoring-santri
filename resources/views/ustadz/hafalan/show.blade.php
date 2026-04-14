@extends('layouts.app')
@section('title', 'Detail Hafalan')
@section('page-title', 'Detail Hafalan')
@section('breadcrumb', '/ Hafalan / <span>Detail</span>')

@section('content')
<div class="page-header"><div><div class="page-header-title">Detail Hafalan</div></div><a href="{{ route('ustadz.hafalan.index') }}" class="btn-outline-hijau">Kembali</a></div>
<div class="card-custom"><div class="card-body-custom">
    <p><strong>Santri:</strong> {{ $hafalan->santri?->nama }}</p>
    <p><strong>Surat:</strong> {{ $hafalan->nama_surat }}</p>
    <p><strong>Juz:</strong> {{ $hafalan->nomor_juz }}</p>
    <p><strong>Nilai:</strong> {{ $hafalan->nilai }}</p>
    <p><strong>Tanggal:</strong> {{ $hafalan->tanggal_setoran?->format('d M Y') }}</p>
</div></div>
@endsection

