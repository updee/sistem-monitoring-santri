@extends('layouts.app')
@section('title', 'Edit Kategori Pelanggaran')
@section('page-title', 'Edit Kategori Pelanggaran')
@section('breadcrumb', '/ Kategori Pelanggaran / <span>Edit</span>')

@section('content')
<div class="page-header">
    <div><div class="page-header-title">Edit Kategori Pelanggaran</div></div>
    <a href="{{ route('admin.kategori-pelanggaran.index') }}" class="btn-outline-hijau">Kembali</a>
</div>
<form method="POST" action="{{ route('admin.kategori-pelanggaran.update', $kategori_pelanggaran) }}">
    @csrf @method('PUT')
    <div class="card-custom"><div class="card-body-custom">
        <div class="form-group-custom"><label class="form-label-custom">Nama Kategori</label><input class="form-control-custom" name="nama_kategori" value="{{ old('nama_kategori', $kategori_pelanggaran->nama_kategori) }}" required></div>
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label-custom">Tingkat</label>
                <select class="form-control-custom" name="tingkat" required>
                    @foreach(['ringan','sedang','berat'] as $t)
                        <option value="{{ $t }}" {{ old('tingkat', $kategori_pelanggaran->tingkat)===$t?'selected':'' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4"><label class="form-label-custom">Poin Default</label><input type="number" min="0" class="form-control-custom" name="poin_default" value="{{ old('poin_default', $kategori_pelanggaran->poin_default) }}" required></div>
            <div class="col-md-4"><label class="form-label-custom">Status</label>
                <select class="form-control-custom" name="is_active">
                    <option value="1" {{ old('is_active', $kategori_pelanggaran->is_active)?'selected':'' }}>Aktif</option>
                    <option value="0" {{ !old('is_active', $kategori_pelanggaran->is_active)?'selected':'' }}>Nonaktif</option>
                </select>
            </div>
        </div>
        <div class="form-group-custom mt-3"><label class="form-label-custom">Deskripsi</label><textarea class="form-control-custom" rows="3" name="deskripsi">{{ old('deskripsi', $kategori_pelanggaran->deskripsi) }}</textarea></div>
        <button class="btn-hijau" type="submit">Update</button>
    </div></div>
</form>
@endsection

