{{-- resources/views/admin/santri/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Tambah Santri')
@section('page-title', 'Tambah Santri')
@section('breadcrumb', '/ Data Santri / <span>Tambah</span>')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-9">
<div class="page-header">
    <div><div class="page-header-title">Tambah Data Santri</div><div class="page-header-sub">Isi semua data santri baru</div></div>
    <a href="{{ route('admin.santri.index') }}" class="btn-outline-hijau">← Kembali</a>
</div>

<form method="POST" action="{{ route('admin.santri.store') }}" enctype="multipart/form-data">
@csrf

{{-- Data Pribadi --}}
<div class="card-custom mb-4">
    <div class="card-header-custom"><div class="card-title-custom">Data Pribadi Santri</div></div>
    <div class="card-body-custom">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="form-group-custom">
                    <label class="form-label-custom">NIS <span style="color:#e53935;">*</span></label>
                    <input type="text" name="nis" class="form-control-custom {{ $errors->has('nis')?'is-invalid':'' }}"
                        value="{{ old('nis') }}" placeholder="Nomor Induk Santri" required>
                    @error('nis')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group-custom">
                    <label class="form-label-custom">Nama Lengkap <span style="color:#e53935;">*</span></label>
                    <input type="text" name="nama" class="form-control-custom {{ $errors->has('nama')?'is-invalid':'' }}"
                        value="{{ old('nama') }}" placeholder="Nama lengkap santri" required>
                    @error('nama')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group-custom">
                    <label class="form-label-custom">Jenis Kelamin <span style="color:#e53935;">*</span></label>
                    <select name="jenis_kelamin" class="form-control-custom" required>
                        <option value="">Pilih</option>
                        <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected':'' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected':'' }}>Perempuan</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group-custom">
                    <label class="form-label-custom">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control-custom" value="{{ old('tempat_lahir') }}" placeholder="Kota kelahiran">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group-custom">
                    <label class="form-label-custom">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control-custom" value="{{ old('tanggal_lahir') }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group-custom">
                    <label class="form-label-custom">No. Telepon Santri</label>
                    <input type="text" name="no_telepon" class="form-control-custom" value="{{ old('no_telepon') }}" placeholder="Opsional">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group-custom">
                    <label class="form-label-custom">Alamat Asal</label>
                    <textarea name="alamat" class="form-control-custom" rows="2" placeholder="Alamat lengkap santri">{{ old('alamat') }}</textarea>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group-custom">
                    <label class="form-label-custom">Foto Santri</label>
                    <input type="file" name="foto" class="form-control-custom" accept="image/*" style="height:auto;padding:7px 12px;">
                    <div style="font-size:10px;color:var(--txt3);margin-top:4px;">JPG/PNG, maks 2MB</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Data Kepesantrenan --}}
<div class="card-custom mb-4">
    <div class="card-header-custom"><div class="card-title-custom">Data Kepesantrenan</div></div>
    <div class="card-body-custom">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="form-group-custom">
                    <label class="form-label-custom">Kelas / Halaqah</label>
                    <select name="kelas_id" class="form-control-custom">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected':'' }}>
                                {{ $kelas->nama_kelas }} (Tingkat {{ $kelas->tingkat }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group-custom">
                    <label class="form-label-custom">Kamar</label>
                    <select name="kamar_id" class="form-control-custom">
                        <option value="">-- Pilih Kamar --</option>
                        @foreach($kamarList as $kamar)
                            <option value="{{ $kamar->id }}" {{ old('kamar_id') == $kamar->id ? 'selected':'' }}>
                                {{ $kamar->nama_kamar }} — Sisa {{ $kamar->sisa_kapasitas }} tempat
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group-custom">
                    <label class="form-label-custom">Wali Santri</label>
                    <select name="wali_id" class="form-control-custom">
                        <option value="">-- Pilih Wali --</option>
                        @foreach($waliList as $wali)
                            <option value="{{ $wali->id }}" {{ old('wali_id') == $wali->id ? 'selected':'' }}>
                                {{ $wali->name }} ({{ $wali->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group-custom">
                    <label class="form-label-custom">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" class="form-control-custom" value="{{ old('tanggal_masuk', date('Y-m-d')) }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group-custom">
                    <label class="form-label-custom">Status</label>
                    <select name="status" class="form-control-custom">
                        <option value="aktif" {{ old('status','aktif') === 'aktif' ? 'selected':'' }}>Aktif</option>
                        <option value="alumni" {{ old('status') === 'alumni' ? 'selected':'' }}>Alumni</option>
                        <option value="keluar" {{ old('status') === 'keluar' ? 'selected':'' }}>Keluar</option>
                    </select>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group-custom">
                    <label class="form-label-custom">Catatan</label>
                    <textarea name="catatan" class="form-control-custom" rows="2" placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-3 justify-content-end">
    <a href="{{ route('admin.santri.index') }}" class="btn-outline-hijau">Batal</a>
    <button type="submit" class="btn-hijau" style="padding:10px 28px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:16px;height:16px;"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
        Simpan Data Santri
    </button>
</div>
</form>
</div>
</div>
@endsection
