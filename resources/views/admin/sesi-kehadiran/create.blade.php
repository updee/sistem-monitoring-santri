@extends('layouts.app')
@section('title', 'Tambah Sesi Kehadiran')
@section('page-title', 'Tambah Sesi')
@section('breadcrumb', '/ <a href="' . route('admin.sesi-kehadiran.index') . '">Sesi Kehadiran</a> / <span>Tambah</span>')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Tambah Sesi Kehadiran</div>
        <div class="page-header-sub">Tambahkan nama sesi baru untuk absensi</div>
    </div>
    <a href="{{ route('admin.sesi-kehadiran.index') }}" class="btn-outline-hijau">← Kembali</a>
</div>

<div class="card-custom">
    <div class="card-body-custom">
        <form method="POST" action="{{ route('admin.sesi-kehadiran.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Nama Sesi <span style="color:#c62828;">*</span></label>
                        <input type="text" name="nama_sesi" class="form-control-custom" value="{{ old('nama_sesi') }}" required
                            placeholder="Contoh: Subuh, Pagi, Sore, Malam">
                        @error('nama_sesi')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Urutan <span style="color:#c62828;">*</span></label>
                        <input type="number" name="urutan" class="form-control-custom" value="{{ old('urutan', 0) }}" min="0" required>
                        @error('urutan')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn-hijau w-100 justify-content-center">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
