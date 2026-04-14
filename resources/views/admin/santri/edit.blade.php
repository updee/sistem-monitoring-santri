@extends('layouts.app')
@section('title', 'Edit Santri')
@section('page-title', 'Edit Santri')
@section('breadcrumb', '/ Data Santri / <span>Edit</span>')

@section('content')
<div class="page-header"><div><div class="page-header-title">Edit Data Santri</div></div><a href="{{ route('admin.santri.index') }}" class="btn-outline-hijau">Kembali</a></div>
<form method="POST" action="{{ route('admin.santri.update', $santri) }}">
@csrf @method('PUT')
<div class="card-custom"><div class="card-body-custom">
    <div class="row g-3">
        <div class="col-md-3"><label class="form-label-custom">NIS</label><input class="form-control-custom" name="nis" value="{{ old('nis', $santri->nis) }}" required></div>
        <div class="col-md-5"><label class="form-label-custom">Nama</label><input class="form-control-custom" name="nama" value="{{ old('nama', $santri->nama) }}" required></div>
        <div class="col-md-4"><label class="form-label-custom">Jenis Kelamin</label><select class="form-control-custom" name="jenis_kelamin" required><option value="L" {{ old('jenis_kelamin',$santri->jenis_kelamin)==='L'?'selected':'' }}>L</option><option value="P" {{ old('jenis_kelamin',$santri->jenis_kelamin)==='P'?'selected':'' }}>P</option></select></div>
        <div class="col-md-4"><label class="form-label-custom">Kelas</label><select class="form-control-custom" name="kelas_id"><option value="">-</option>@foreach($kelasList as $k)<option value="{{ $k->id }}" {{ old('kelas_id',$santri->kelas_id)==$k->id?'selected':'' }}>{{ $k->nama_kelas }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label-custom">Kamar</label><select class="form-control-custom" name="kamar_id"><option value="">-</option>@foreach($kamarList as $k)<option value="{{ $k->id }}" {{ old('kamar_id',$santri->kamar_id)==$k->id?'selected':'' }}>{{ $k->nama_kamar }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label-custom">Wali</label><select class="form-control-custom" name="wali_id"><option value="">-</option>@foreach($waliList as $w)<option value="{{ $w->id }}" {{ old('wali_id',$santri->wali_id)==$w->id?'selected':'' }}>{{ $w->name }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label-custom">Status</label><select class="form-control-custom" name="status">@foreach(['aktif','alumni','keluar'] as $s)<option value="{{ $s }}" {{ old('status',$santri->status)===$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
    </div>
    <button class="btn-hijau mt-3" type="submit">Update</button>
</div></div>
</form>
@endsection

