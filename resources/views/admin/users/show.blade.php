@extends('layouts.app')
@section('title','Detail Pengguna')
@section('page-title','Detail Pengguna')
@section('breadcrumb','/ Pengguna / <span>Detail</span>')

@section('content')
<div class="page-header"><div><div class="page-header-title">{{ $user->name }}</div></div><a href="{{ route('admin.users.index') }}" class="btn-outline-hijau">Kembali</a></div>
<div class="card-custom"><div class="card-body-custom">
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Role:</strong> {{ $user->role_label }}</p>
    <p><strong>Telepon:</strong> {{ $user->no_telepon ?? '-' }}</p>
    <p><strong>Status:</strong> {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</p>
</div></div>
@endsection

