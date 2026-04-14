{{-- resources/views/ustadz/hafalan/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Catat Setoran Hafalan')
@section('page-title', 'Catat Setoran Hafalan')
@section('breadcrumb', '/ Hafalan / <span>Catat Baru</span>')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="page-header">
            <div>
                <div class="page-header-title">Catat Setoran Hafalan</div>
                <div class="page-header-sub">Input setoran atau muroja'ah santri</div>
            </div>
            <a href="{{ route('ustadz.hafalan.index') }}" class="btn-outline-hijau">
                ← Kembali
            </a>
        </div>

        <form method="POST" action="{{ route('ustadz.hafalan.store') }}">
            @csrf

            {{-- Pilih Santri & Jenis --}}
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <div class="card-title-custom">Informasi Santri</div>
                </div>
                <div class="card-body-custom">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Pilih Santri <span style="color:#e53935;">*</span></label>
                                <select name="santri_id" class="form-control-custom {{ $errors->has('santri_id') ? 'is-invalid' : '' }}" required>
                                    <option value="">-- Pilih Santri --</option>
                                    @foreach($santriList as $s)
                                        <option value="{{ $s->id }}" {{ old('santri_id') == $s->id ? 'selected' : '' }}>
                                            {{ $s->nama }} ({{ $s->nis }}) — {{ $s->kelas->nama_kelas ?? 'Tanpa Kelas' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('santri_id')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Jenis Setoran <span style="color:#e53935;">*</span></label>
                                <select name="jenis" class="form-control-custom" required>
                                    <option value="setoran_baru" {{ old('jenis') === 'setoran_baru' ? 'selected' : '' }}>Setoran Baru</option>
                                    <option value="murojaah"     {{ old('jenis') === 'murojaah'     ? 'selected' : '' }}>Muroja'ah</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Hafalan --}}
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <div class="card-title-custom">Detail Hafalan</div>
                </div>
                <div class="card-body-custom">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Nama Surat <span style="color:#e53935;">*</span></label>
                                <input type="text" name="nama_surat" class="form-control-custom {{ $errors->has('nama_surat') ? 'is-invalid' : '' }}"
                                    value="{{ old('nama_surat') }}" placeholder="cth: Al-Baqarah" required>
                                @error('nama_surat')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Nomor Juz</label>
                                <input type="number" name="nomor_juz" class="form-control-custom"
                                    value="{{ old('nomor_juz') }}" placeholder="1–30" min="1" max="30">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Halaman Mulai</label>
                                <input type="number" name="halaman_dari" id="halDari" class="form-control-custom"
                                    value="{{ old('halaman_dari') }}" placeholder="cth: 20" min="1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Halaman Selesai</label>
                                <input type="number" name="halaman_sampai" id="halSampai" class="form-control-custom"
                                    value="{{ old('halaman_sampai') }}" placeholder="cth: 23" min="1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Jumlah Halaman</label>
                                <input type="number" name="jumlah_halaman" id="jmlHal" class="form-control-custom"
                                    value="{{ old('jumlah_halaman') }}" placeholder="Otomatis" style="background:#f4f7f4;" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Nilai (0–100)</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="number" name="nilai" id="nilaiInput" class="form-control-custom"
                                        value="{{ old('nilai') }}" placeholder="cth: 85" min="0" max="100" step="0.5">
                                    <div id="gradeBadge" class="badge-custom" style="min-width:36px;justify-content:center;font-size:14px;padding:6px 10px;">—</div>
                                </div>
                                <div style="font-size:10px;color:var(--txt3);margin-top:4px;">A≥90 · B≥75 · C≥60 · D&lt;60</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Tanggal Setoran <span style="color:#e53935;">*</span></label>
                                <input type="date" name="tanggal_setoran" class="form-control-custom {{ $errors->has('tanggal_setoran') ? 'is-invalid' : '' }}"
                                    value="{{ old('tanggal_setoran', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                                @error('tanggal_setoran')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Catatan</label>
                                <textarea name="catatan" class="form-control-custom" rows="3"
                                    placeholder="Catatan tambahan mengenai kualitas hafalan...">{{ old('catatan') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 justify-content-end">
                <a href="{{ route('ustadz.hafalan.index') }}" class="btn-outline-hijau">Batal</a>
                <button type="submit" class="btn-hijau" style="padding:10px 28px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                    Simpan Setoran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-hitung jumlah halaman
const halDari   = document.getElementById('halDari');
const halSampai = document.getElementById('halSampai');
const jmlHal    = document.getElementById('jmlHal');

function hitungHalaman() {
    const dari   = parseInt(halDari.value)   || 0;
    const sampai = parseInt(halSampai.value) || 0;
    if (dari && sampai && sampai >= dari) {
        jmlHal.value = sampai - dari + 1;
    }
}
halDari.addEventListener('input', hitungHalaman);
halSampai.addEventListener('input', hitungHalaman);

// Auto-grade dari nilai
const nilaiInput = document.getElementById('nilaiInput');
const gradeBadge = document.getElementById('gradeBadge');

function updateGrade() {
    const n = parseFloat(nilaiInput.value);
    gradeBadge.className = 'badge-custom';
    if (isNaN(n) || nilaiInput.value === '') {
        gradeBadge.textContent = '—';
        return;
    }
    if (n >= 90) { gradeBadge.classList.add('grade-a'); gradeBadge.textContent = 'A'; }
    else if (n >= 75) { gradeBadge.classList.add('grade-b'); gradeBadge.textContent = 'B'; }
    else if (n >= 60) { gradeBadge.classList.add('grade-c'); gradeBadge.textContent = 'C'; }
    else { gradeBadge.classList.add('grade-d'); gradeBadge.textContent = 'D'; }
}
nilaiInput.addEventListener('input', updateGrade);
</script>
@endpush
