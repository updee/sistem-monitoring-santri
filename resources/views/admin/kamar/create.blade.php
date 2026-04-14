@extends('layouts.app')
@section('title', 'Tambah Kamar')
@section('page-title', 'Tambah Kamar')
@section('breadcrumb', '/ Kamar / <span>Tambah</span>')

@section('content')
<div class="page-header">
    <div><div class="page-header-title">Tambah Kamar</div></div>
    <a href="{{ route('admin.kamar.index') }}" class="btn-outline-hijau">Kembali</a>
</div>
<form method="POST" action="{{ route('admin.kamar.store') }}">
    @csrf
    <div class="card-custom">
        <div class="card-body-custom">
            <div class="form-group-custom">
                <label class="form-label-custom">Nama Kamar</label>
                <input class="form-control-custom" name="nama_kamar" value="{{ old('nama_kamar') }}" required>
            </div>
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label-custom">Gedung</label><input class="form-control-custom" name="gedung" value="{{ old('gedung') }}"></div>
                <div class="col-md-4"><label class="form-label-custom">Kapasitas</label><input type="number" min="1" class="form-control-custom" name="kapasitas" value="{{ old('kapasitas', 10) }}" required></div>
            </div>
            <div class="form-group-custom mt-3">
                <label class="form-label-custom">Keterangan</label>
                <textarea class="form-control-custom" rows="3" name="keterangan">{{ old('keterangan') }}</textarea>
            </div>
            <button class="btn-hijau" type="submit">Simpan</button>
        </div>
    </div>
</form>
@endsection

