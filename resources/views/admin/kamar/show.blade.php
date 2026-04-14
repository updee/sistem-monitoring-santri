@extends('layouts.app')
@section('title', 'Detail Kamar')
@section('page-title', 'Detail Kamar')
@section('breadcrumb', '/ Kamar / <span>Detail</span>')

@section('content')
<div class="page-header">
    <div><div class="page-header-title">{{ $kamar->nama_kamar }}</div></div>
    <a href="{{ route('admin.kamar.index') }}" class="btn-outline-hijau">Kembali</a>
</div>
<div class="card-custom">
    <div class="card-body-custom">
        <p><strong>Gedung:</strong> {{ $kamar->gedung ?? '-' }}</p>
        <p><strong>Kapasitas:</strong> {{ $kamar->kapasitas }}</p>
        <p><strong>Penghuni:</strong> {{ $kamar->santri->count() }}</p>
        <p><strong>Keterangan:</strong> {{ $kamar->keterangan ?? '-' }}</p>
    </div>
</div>
@endsection

