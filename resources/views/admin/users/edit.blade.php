@extends('layouts.app')
@section('title','Edit Pengguna')
@section('page-title','Edit Pengguna')
@section('breadcrumb','/ Pengguna / <span>Edit</span>')

@section('content')
<div class="page-header"><div><div class="page-header-title">Edit Akun Pengguna</div></div><a href="{{ route('admin.users.index') }}" class="btn-outline-hijau">Kembali</a></div>
<form method="POST" action="{{ route('admin.users.update', $user) }}">
@csrf @method('PUT')
<div class="card-custom"><div class="card-body-custom">
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label-custom">Nama</label><input class="form-control-custom" name="name" value="{{ old('name', $user->name) }}" required></div>
        <div class="col-md-6"><label class="form-label-custom">Email</label><input type="email" class="form-control-custom" name="email" value="{{ old('email', $user->email) }}" required></div>
        <div class="col-md-4"><label class="form-label-custom">Role</label><select class="form-control-custom" name="role" required>@foreach(['admin'=>'Admin','ustadz'=>'Ustadz','wali_santri'=>'Wali Santri'] as $k=>$v)<option value="{{ $k }}" {{ old('role',$user->role)===$k?'selected':'' }}>{{ $v }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label-custom">No. Telepon</label><input class="form-control-custom" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}"></div>
        <div class="col-md-4"><label class="form-label-custom">Status</label><select class="form-control-custom" name="is_active"><option value="1" {{ old('is_active',$user->is_active)?'selected':'' }}>Aktif</option><option value="0" {{ !old('is_active',$user->is_active)?'selected':'' }}>Nonaktif</option></select></div>
    </div>
    <div class="row g-3 mt-2">
        <div class="col-md-6"><label class="form-label-custom">Password Baru (opsional)</label><input type="password" class="form-control-custom" name="password"></div>
        <div class="col-md-6"><label class="form-label-custom">Konfirmasi Password Baru</label><input type="password" class="form-control-custom" name="password_confirmation"></div>
    </div>
    <button class="btn-hijau" type="submit">Update</button>
</div></div>
</form>
@endsection

