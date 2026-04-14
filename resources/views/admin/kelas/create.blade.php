@extends('layouts.app')
@section('title','Tambah Kelas')
@section('page-title','Tambah Kelas')
@section('breadcrumb','/ Kelas / <span>Tambah</span>')

@section('content')
<div class="page-header"><div><div class="page-header-title">Tambah Kelas</div></div><a href="{{ route('admin.kelas.index') }}" class="btn-outline-hijau">Kembali</a></div>
<form method="POST" action="{{ route('admin.kelas.store') }}">
@csrf
<div class="card-custom"><div class="card-body-custom">
    <div class="row g-3">
        <div class="col-md-4"><label class="form-label-custom">Nama Kelas</label><input class="form-control-custom" name="nama_kelas" value="{{ old('nama_kelas') }}" required></div>
        <div class="col-md-3"><label class="form-label-custom">Tingkat</label><input class="form-control-custom" name="tingkat" value="{{ old('tingkat') }}" required></div>
        <div class="col-md-5"><label class="form-label-custom">Ustadz</label><select class="form-control-custom" name="ustadz_id"><option value="">- Pilih -</option>@foreach($ustadz as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach</select></div>
    </div>
    <button class="btn-hijau mt-3" type="submit">Simpan</button>
</div></div>
</form>
@endsection

