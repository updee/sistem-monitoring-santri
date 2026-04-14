{{-- resources/views/wali/izin/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Ajukan Izin')
@section('page-title', 'Ajukan Izin')
@section('breadcrumb', '/ <span>Ajukan Izin</span>')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">
<div class="page-header">
    <div><div class="page-header-title">Ajukan Izin Pulang</div><div class="page-header-sub">{{ $santri->nama }}</div></div>
    <a href="{{ route('wali_santri.izin.index') }}" class="btn-outline-hijau">← Kembali</a>
</div>
<div class="alert-hijau mb-4">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <div style="font-size:12px;">Pengajuan izin akan diproses oleh Admin. Pastikan data sudah benar sebelum mengirim.</div>
</div>
<form method="POST" action="{{ route('wali_santri.izin.store') }}">
@csrf
<div class="card-custom mb-4">
    <div class="card-header-custom"><div class="card-title-custom">Detail Pengajuan Izin</div></div>
    <div class="card-body-custom">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-group-custom">
                    <label class="form-label-custom">Tanggal Mulai Izin <span style="color:#e53935;">*</span></label>
                    <input type="date" name="tanggal_mulai" class="form-control-custom {{ $errors->has('tanggal_mulai') ? 'is-invalid':'' }}"
                        value="{{ old('tanggal_mulai') }}" min="{{ date('Y-m-d') }}" required>
                    @error('tanggal_mulai')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group-custom">
                    <label class="form-label-custom">Tanggal Kembali <span style="color:#e53935;">*</span></label>
                    <input type="date" name="tanggal_kembali" class="form-control-custom {{ $errors->has('tanggal_kembali') ? 'is-invalid':'' }}"
                        value="{{ old('tanggal_kembali') }}" min="{{ date('Y-m-d') }}" required>
                    @error('tanggal_kembali')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group-custom">
                    <label class="form-label-custom">Nama Penjemput</label>
                    <input type="text" name="nama_penjemput" class="form-control-custom"
                        value="{{ old('nama_penjemput', auth()->user()->name) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group-custom">
                    <label class="form-label-custom">No. Telepon Penjemput</label>
                    <input type="text" name="no_telepon_penjemput" class="form-control-custom"
                        value="{{ old('no_telepon_penjemput', auth()->user()->no_telepon) }}" placeholder="0812-xxxx-xxxx">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group-custom">
                    <label class="form-label-custom">Alasan Izin <span style="color:#e53935;">*</span></label>
                    <textarea name="alasan" class="form-control-custom {{ $errors->has('alasan') ? 'is-invalid':'' }}"
                        rows="4" placeholder="Jelaskan alasan pengajuan izin secara lengkap..." required>{{ old('alasan') }}</textarea>
                    @error('alasan')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>
</div>
<div class="d-flex gap-3 justify-content-end">
    <a href="{{ route('wali_santri.izin.index') }}" class="btn-outline-hijau">Batal</a>
    <button type="submit" class="btn-hijau" style="padding:10px 28px;">Kirim Pengajuan Izin</button>
</div>
</form>
</div>
</div>
@endsection


{{-- ════════════════════════════════════════════════════════════
     resources/views/admin/santri/create.blade.php
     ════════════════════════════════════════════════════════════ --}}
