@extends('layouts.app')
@section('title', 'Edit Kehadiran')
@section('page-title', 'Edit Kehadiran')
@section('breadcrumb', '/ Kehadiran / <span>Edit</span>')

@section('content')
<div class="page-header"><div><div class="page-header-title">Edit Kehadiran</div></div><a href="{{ route('ustadz.kehadiran.index') }}" class="btn-outline-hijau">Kembali</a></div>
<form method="POST" action="{{ route('ustadz.kehadiran.update', $kehadiran->id) }}">
@csrf @method('PATCH')
<div class="card-custom"><div class="card-body-custom">
    <div class="row g-3">
        <div class="col-md-4"><label class="form-label-custom">Status</label><select class="form-control-custom" name="status">@foreach(['hadir','izin','sakit','alpha'] as $s)<option value="{{ $s }}" {{ old('status', $kehadiran->status)===$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
        <div class="col-md-8"><label class="form-label-custom">Keterangan</label><input class="form-control-custom" name="keterangan" value="{{ old('keterangan', $kehadiran->keterangan) }}"></div>
    </div>
    <button class="btn-hijau mt-3" type="submit">Update</button>
</div></div>
</form>
@endsection

