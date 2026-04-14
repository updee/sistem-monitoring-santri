@extends('layouts.app')
@section('title','Detail Kelas')
@section('page-title','Detail Kelas')
@section('breadcrumb','/ Kelas / <span>Detail</span>')

@section('content')
<div class="page-header"><div><div class="page-header-title">{{ $kelas->nama_kelas }}</div></div><a href="{{ route('admin.kelas.index') }}" class="btn-outline-hijau">Kembali</a></div>
<div class="card-custom"><div class="card-body-custom">
    <p><strong>Tingkat:</strong> {{ $kelas->tingkat }}</p>
    <p><strong>Ustadz:</strong> {{ $kelas->ustadz?->name ?? '-' }}</p>
    <p><strong>Jumlah Santri:</strong> {{ $kelas->jumlah_santri }}</p>
</div></div>
@endsection

