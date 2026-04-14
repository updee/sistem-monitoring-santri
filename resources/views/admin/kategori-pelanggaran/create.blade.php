@extends('layouts.app')
@section('title', 'Tambah Kategori Pelanggaran')
@section('page-title', 'Tambah Kategori Pelanggaran')
@section('breadcrumb', '/ Kategori Pelanggaran / <span>Tambah</span>')

@section('content')
<div class="page-header">
    <div><div class="page-header-title">Tambah Kategori Pelanggaran</div></div>
    <a href="{{ route('admin.kategori-pelanggaran.index') }}" class="btn-outline-hijau">Kembali</a>
</div>
<form method="POST" action="{{ route('admin.kategori-pelanggaran.store') }}">
    @csrf
    <div class="card-custom"><div class="card-body-custom">
        <div class="form-group-custom"><label class="form-label-custom">Nama Kategori</label><input class="form-control-custom" name="nama_kategori" value="{{ old('nama_kategori') }}" required></div>
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label-custom">Tingkat</label>
                <select class="form-control-custom" name="tingkat" required>
                    <option value="ringan">Ringan</option><option value="sedang">Sedang</option><option value="berat">Berat</option>
                </select>
            </div>
            <div class="col-md-4"><label class="form-label-custom">Poin Default</label><input type="number" min="0" class="form-control-custom" name="poin_default" value="{{ old('poin_default', 5) }}" required></div>
        </div>
        <div class="form-group-custom mt-3"><label class="form-label-custom">Deskripsi</label><textarea class="form-control-custom" rows="3" name="deskripsi">{{ old('deskripsi') }}</textarea></div>
        <button class="btn-hijau" type="submit">Simpan</button>
    </div></div>
</form>
@endsection

