@extends('layouts.app')
@section('title','Edit Kelas')
@section('page-title','Edit Kelas')
@section('breadcrumb','/ Kelas / <span>Edit</span>')

@section('content')
<div class="page-header"><div><div class="page-header-title">Edit Kelas</div></div><a href="{{ route('admin.kelas.index') }}" class="btn-outline-hijau">Kembali</a></div>
<form method="POST" action="{{ route('admin.kelas.update', $kelas) }}">
@csrf @method('PUT')
<div class="card-custom"><div class="card-body-custom">
    <div class="row g-3">
        <div class="col-md-4"><label class="form-label-custom">Nama Kelas</label><input class="form-control-custom" name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required></div>
        <div class="col-md-3"><label class="form-label-custom">Tingkat</label><input class="form-control-custom" name="tingkat" value="{{ old('tingkat', $kelas->tingkat) }}" required></div>
        <div class="col-md-5"><label class="form-label-custom">Ustadz</label><select class="form-control-custom" name="ustadz_id"><option value="">- Pilih -</option>@foreach($ustadz as $u)<option value="{{ $u->id }}" {{ old('ustadz_id',$kelas->ustadz_id)==$u->id?'selected':'' }}>{{ $u->name }}</option>@endforeach</select></div>
    </div>
    <button class="btn-hijau mt-3" type="submit">Update</button>
</div></div>
</form>
@endsection

