{{-- resources/views/ustadz/pencapaian/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Tambah Pencapaian')
@section('page-title', 'Tambah Pencapaian')
@section('breadcrumb', '/ Pencapaian / <span>Tambah</span>')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="page-header">
    <div><div class="page-header-title">Tambah Pencapaian</div><div class="page-header-sub">Dokumentasikan prestasi santri</div></div>
    <a href="{{ route('ustadz.pencapaian.index') }}" class="btn-outline-hijau">← Kembali</a>
</div>
<form method="POST" action="{{ isset($pencapaian) ? route('ustadz.pencapaian.update', $pencapaian) : route('ustadz.pencapaian.store') }}" enctype="multipart/form-data">
@csrf
@if(isset($pencapaian)) @method('PUT') @endif
<div class="card-custom mb-4">
    <div class="card-header-custom"><div class="card-title-custom">Data Pencapaian</div></div>
    <div class="card-body-custom">
        <div class="form-group-custom">
            <label class="form-label-custom">Pilih Santri <span style="color:#e53935;">*</span></label>
            <select name="santri_id" class="form-control-custom" required>
                <option value="">-- Pilih Santri --</option>
                @foreach($santriList as $s)
                    <option value="{{ $s->id }}" {{ old('santri_id', $pencapaian->santri_id ?? null) == $s->id ? 'selected':'' }}>
                        {{ $s->nama }} ({{ $s->nis }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group-custom">
            <label class="form-label-custom">Judul Pencapaian <span style="color:#e53935;">*</span></label>
            <input type="text" name="judul_pencapaian" class="form-control-custom"
                value="{{ old('judul_pencapaian', $pencapaian->judul_pencapaian ?? '') }}" placeholder="cth: Juara 1 MTQ Tingkat Kabupaten" required>
        </div>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="form-group-custom">
                    <label class="form-label-custom">Jenis <span style="color:#e53935;">*</span></label>
                    <select name="jenis" class="form-control-custom" required>
                        <option value="">Pilih jenis</option>
                        @foreach(['Akademik','Non-Akademik','Hafalan Al-Quran','Olahraga','Seni & Budaya','Lainnya'] as $j)
                            <option value="{{ $j }}" {{ old('jenis', $pencapaian->jenis ?? '') === $j ? 'selected':'' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group-custom">
                    <label class="form-label-custom">Tingkat <span style="color:#e53935;">*</span></label>
                    <select name="tingkat" class="form-control-custom" required>
                        <option value="">Pilih tingkat</option>
                        @foreach(['pesantren' => 'Pesantren','kabupaten' => 'Kabupaten','provinsi' => 'Provinsi','nasional' => 'Nasional','internasional' => 'Internasional'] as $val => $lbl)
                            <option value="{{ $val }}" {{ old('tingkat', $pencapaian->tingkat ?? '') === $val ? 'selected':'' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group-custom">
                    <label class="form-label-custom">Peringkat</label>
                    <select name="peringkat" class="form-control-custom">
                        @foreach(['juara_1'=>'Juara 1','juara_2'=>'Juara 2','juara_3'=>'Juara 3','harapan'=>'Harapan','peserta'=>'Peserta'] as $val => $lbl)
                            <option value="{{ $val }}" {{ old('peringkat', $pencapaian->peringkat ?? 'peserta') === $val ? 'selected':'' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-group-custom">
                    <label class="form-label-custom">Tanggal <span style="color:#e53935;">*</span></label>
                    <input type="date" name="tanggal" class="form-control-custom" value="{{ old('tanggal', isset($pencapaian) ? $pencapaian->tanggal?->format('Y-m-d') : date('Y-m-d')) }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group-custom">
                    <label class="form-label-custom">Penyelenggara</label>
                    <input type="text" name="penyelenggara" class="form-control-custom" value="{{ old('penyelenggara', $pencapaian->penyelenggara ?? '') }}" placeholder="Nama lembaga/instansi">
                </div>
            </div>
        </div>
        <div class="form-group-custom">
            <label class="form-label-custom">Keterangan</label>
            <textarea name="keterangan" class="form-control-custom" rows="3" placeholder="Deskripsi pencapaian...">{{ old('keterangan', $pencapaian->keterangan ?? '') }}</textarea>
        </div>
        <div class="form-group-custom">
            <label class="form-label-custom">Foto Sertifikat/Piagam</label>
            <input type="file" name="foto_sertifikat" class="form-control-custom" accept="image/*" style="height:auto;padding:7px 12px;">
            <div style="font-size:10px;color:var(--txt3);margin-top:4px;">Format: JPG, PNG. Maks 2MB</div>
        </div>
    </div>
</div>
<div class="d-flex gap-3 justify-content-end">
    <a href="{{ route('ustadz.pencapaian.index') }}" class="btn-outline-hijau">Batal</a>
    <button type="submit" class="btn-hijau" style="padding:10px 28px;">Simpan Pencapaian</button>
</div>
</form>
</div>
</div>
@endsection
