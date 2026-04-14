@extends('layouts.app')
@section('title', 'Edit Kamar')
@section('page-title', 'Edit Kamar')
@section('breadcrumb', '/ Kamar / <span>Edit</span>')

@section('content')
<div class="page-header">
    <div><div class="page-header-title">Edit Kamar</div></div>
    <a href="{{ route('admin.kamar.index') }}" class="btn-outline-hijau">Kembali</a>
</div>
<form method="POST" action="{{ route('admin.kamar.update', $kamar) }}">
    @csrf @method('PUT')
    <div class="card-custom">
        <div class="card-body-custom">
            <div class="form-group-custom">
                <label class="form-label-custom">Nama Kamar</label>
                <input class="form-control-custom" name="nama_kamar" value="{{ old('nama_kamar', $kamar->nama_kamar) }}" required>
            </div>
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label-custom">Gedung</label><input class="form-control-custom" name="gedung" value="{{ old('gedung', $kamar->gedung) }}"></div>
                <div class="col-md-4"><label class="form-label-custom">Kapasitas</label><input type="number" min="1" class="form-control-custom" name="kapasitas" value="{{ old('kapasitas', $kamar->kapasitas) }}" required></div>
                <div class="col-md-4"><label class="form-label-custom">Status</label>
                    <select name="is_active" class="form-control-custom">
                        <option value="1" {{ old('is_active', $kamar->is_active) ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !old('is_active', $kamar->is_active) ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="form-group-custom mt-3">
                <label class="form-label-custom">Keterangan</label>
                <textarea class="form-control-custom" rows="3" name="keterangan">{{ old('keterangan', $kamar->keterangan) }}</textarea>
            </div>
            <button class="btn-hijau" type="submit">Update</button>
        </div>
    </div>
</form>
@endsection

