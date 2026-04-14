@extends('layouts.app')
@section('title','Tambah Pengguna')
@section('page-title','Tambah Pengguna')
@section('breadcrumb','/ Pengguna / <span>Tambah</span>')

@section('content')
<div class="page-header"><div><div class="page-header-title">Tambah Akun Pengguna</div></div><a href="{{ route('admin.users.index') }}" class="btn-outline-hijau">Kembali</a></div>
<form method="POST" action="{{ route('admin.users.store') }}">
@csrf
<div class="card-custom"><div class="card-body-custom">
    @if ($errors->any())
        <div class="alert-danger mb-3" style="padding:10px 12px;border-radius:8px;">
            <strong>Periksa input berikut:</strong>
            <ul style="margin:6px 0 0 16px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-md-6"><label class="form-label-custom">Nama</label><input class="form-control-custom" name="name" value="{{ old('name') }}" required></div>
        <div class="col-md-6"><label class="form-label-custom">Email</label><input type="email" class="form-control-custom" name="email" value="{{ old('email') }}" required></div>
        <div class="col-md-4"><label class="form-label-custom">Role</label><select class="form-control-custom" name="role" required><option value="">-- Pilih Role --</option><option value="admin" {{ old('role')==='admin'?'selected':'' }}>Admin</option><option value="ustadz" {{ old('role')==='ustadz'?'selected':'' }}>Ustadz</option><option value="wali_santri" {{ old('role')==='wali_santri'?'selected':'' }}>Wali Santri</option></select></div>
        <div class="col-md-4"><label class="form-label-custom">No. Telepon</label><input class="form-control-custom" name="no_telepon" value="{{ old('no_telepon') }}"></div>
        <div class="col-md-4"><label class="form-label-custom">Password</label><input type="password" class="form-control-custom" name="password" required></div>
        <div class="col-md-4"><label class="form-label-custom">Konfirmasi Password</label><input type="password" class="form-control-custom" name="password_confirmation" required></div>
    </div>
    <button class="btn-hijau mt-3" type="submit">Simpan</button>
</div></div>
</form>
@endsection

