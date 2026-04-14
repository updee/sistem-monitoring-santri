@extends('layouts.app')
@section('title', 'Detail Kategori Pelanggaran')
@section('page-title', 'Detail Kategori Pelanggaran')
@section('breadcrumb', '/ Kategori Pelanggaran / <span>Detail</span>')

@section('content')
<div class="page-header">
    <div><div class="page-header-title">{{ $kategori_pelanggaran->nama_kategori }}</div></div>
    <a href="{{ route('admin.kategori-pelanggaran.index') }}" class="btn-outline-hijau">Kembali</a>
</div>
<div class="card-custom">
    <div class="card-body-custom">
        <p><strong>Tingkat:</strong> {{ ucfirst($kategori_pelanggaran->tingkat) }}</p>
        <p><strong>Poin Default:</strong> {{ $kategori_pelanggaran->poin_default }}</p>
        <p><strong>Deskripsi:</strong> {{ $kategori_pelanggaran->deskripsi ?? '-' }}</p>
        <p><strong>Dipakai oleh:</strong> {{ $kategori_pelanggaran->pelanggaran->count() }} catatan pelanggaran</p>
    </div>
</div>
@endsection

