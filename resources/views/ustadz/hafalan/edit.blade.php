@extends('layouts.app')
@section('title', 'Edit Hafalan')
@section('page-title', 'Edit Hafalan')
@section('breadcrumb', '/ Hafalan / <span>Edit</span>')

@section('content')
<div class="page-header"><div><div class="page-header-title">Edit Hafalan</div></div><a href="{{ route('ustadz.hafalan.index') }}" class="btn-outline-hijau">Kembali</a></div>
<form method="POST" action="{{ route('ustadz.hafalan.update', $hafalan) }}">
@csrf @method('PUT')
<div class="card-custom"><div class="card-body-custom">
    <div class="row g-3">
        <div class="col-md-4"><label class="form-label-custom">Santri</label><select class="form-control-custom" name="santri_id">@foreach($santriList as $s)<option value="{{ $s->id }}" {{ old('santri_id', $hafalan->santri_id)==$s->id?'selected':'' }}>{{ $s->nama }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label-custom">Surat</label><input class="form-control-custom" name="nama_surat" value="{{ old('nama_surat', $hafalan->nama_surat) }}" required></div>
        <div class="col-md-2"><label class="form-label-custom">Juz</label><input type="number" class="form-control-custom" name="nomor_juz" value="{{ old('nomor_juz', $hafalan->nomor_juz) }}"></div>
        <div class="col-md-2"><label class="form-label-custom">Nilai</label><input type="number" class="form-control-custom" name="nilai" value="{{ old('nilai', $hafalan->nilai) }}"></div>
    </div>
    <div class="row g-3 mt-2">
        <div class="col-md-3"><label class="form-label-custom">Halaman Dari</label><input type="number" class="form-control-custom" name="halaman_dari" value="{{ old('halaman_dari', $hafalan->halaman_dari) }}"></div>
        <div class="col-md-3"><label class="form-label-custom">Halaman Sampai</label><input type="number" class="form-control-custom" name="halaman_sampai" value="{{ old('halaman_sampai', $hafalan->halaman_sampai) }}"></div>
        <div class="col-md-3"><label class="form-label-custom">Jenis</label><select class="form-control-custom" name="jenis"><option value="setoran_baru" {{ old('jenis', $hafalan->jenis)==='setoran_baru'?'selected':'' }}>Setoran Baru</option><option value="murojaah" {{ old('jenis', $hafalan->jenis)==='murojaah'?'selected':'' }}>Murojaah</option></select></div>
        <div class="col-md-3"><label class="form-label-custom">Tanggal</label><input type="date" class="form-control-custom" name="tanggal_setoran" value="{{ old('tanggal_setoran', $hafalan->tanggal_setoran?->format('Y-m-d')) }}"></div>
    </div>
    <button class="btn-hijau mt-3" type="submit">Update</button>
</div></div>
</form>
@endsection
