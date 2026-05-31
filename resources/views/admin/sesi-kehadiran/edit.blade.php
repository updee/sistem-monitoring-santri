@extends('layouts.app')
@section('title', 'Edit Sesi Kehadiran')
@section('page-title', 'Edit Sesi')
@section('breadcrumb', '/ <a href="' . route('admin.sesi-kehadiran.index') . '">Sesi Kehadiran</a> / <span>Edit</span>')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Edit Sesi Kehadiran</div>
        <div class="page-header-sub">Perbarui data sesi "{{ $sesi_kehadiran->nama_sesi }}"</div>
    </div>
    <a href="{{ route('admin.sesi-kehadiran.index') }}" class="btn-outline-hijau">← Kembali</a>
</div>

<div class="card-custom">
    <div class="card-body-custom">
        <form method="POST" action="{{ route('admin.sesi-kehadiran.update', $sesi_kehadiran) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-5">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Nama Sesi <span style="color:#c62828;">*</span></label>
                        <input type="text" name="nama_sesi" class="form-control-custom"
                            value="{{ old('nama_sesi', $sesi_kehadiran->nama_sesi) }}" required>
                        @error('nama_sesi')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Urutan <span style="color:#c62828;">*</span></label>
                        <input type="number" name="urutan" class="form-control-custom"
                            value="{{ old('urutan', $sesi_kehadiran->urutan) }}" min="0" required>
                        @error('urutan')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Status</label>
                        <select name="is_active" class="form-control-custom">
                            <option value="1" {{ old('is_active', $sesi_kehadiran->is_active) ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !old('is_active', $sesi_kehadiran->is_active) ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn-hijau w-100 justify-content-center">Perbarui</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
