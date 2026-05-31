{{-- resources/views/ustadz/hafalan/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit Hafalan')
@section('page-title', 'Edit Hafalan')
@section('breadcrumb', '/ Hafalan / <span>Edit</span>')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="page-header">
            <div>
                <div class="page-header-title">Edit Setoran Hafalan</div>
                <div class="page-header-sub">Perbarui data setoran hafalan santri</div>
            </div>
            <a href="{{ route('ustadz.hafalan.index') }}" class="btn-outline-hijau">← Kembali</a>
        </div>

        <form method="POST" action="{{ route('ustadz.hafalan.update', $hafalan) }}" id="formHafalan">
            @csrf @method('PUT')

            {{-- ═══ 1) Identitas Setoran ═══ --}}
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <div class="card-title-custom">① Identitas Setoran</div>
                </div>
                <div class="card-body-custom">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Pilih Santri <span style="color:#e53935;">*</span></label>
                                <select name="santri_id" class="form-control-custom {{ $errors->has('santri_id') ? 'is-invalid' : '' }}" required>
                                    <option value="">-- Pilih Santri --</option>
                                    @foreach($santriList as $s)
                                        <option value="{{ $s->id }}" {{ old('santri_id', $hafalan->santri_id) == $s->id ? 'selected' : '' }}>
                                            {{ $s->nama }} ({{ $s->nis }}) — {{ $s->kelas->nama_kelas ?? 'Tanpa Kelas' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('santri_id')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Tanggal Setoran <span style="color:#e53935;">*</span></label>
                                <input type="date" name="tanggal_setoran" class="form-control-custom {{ $errors->has('tanggal_setoran') ? 'is-invalid' : '' }}"
                                    value="{{ old('tanggal_setoran', $hafalan->tanggal_setoran?->format('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                                @error('tanggal_setoran')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ 2) Kategori Program & 3) Jenis Kegiatan ═══ --}}
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <div class="card-title-custom">② Kategori & Jenis</div>
                </div>
                <div class="card-body-custom">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Kategori Program</label>
                                <select name="kategori" id="kategoriSelect" class="form-control-custom">
                                    <option value="" {{ old('kategori', $hafalan->kategori) == '' ? 'selected' : '' }}>— Tanpa Kategori —</option>
                                    <option value="wisuda" {{ old('kategori', $hafalan->kategori) === 'wisuda' ? 'selected' : '' }}>🎓 Wisuda</option>
                                    <option value="zaidah" {{ old('kategori', $hafalan->kategori) === 'zaidah' ? 'selected' : '' }}>📖 Zaidah</option>
                                    <option value="ujian"  {{ old('kategori', $hafalan->kategori) === 'ujian'  ? 'selected' : '' }}>📝 Ujian</option>
                                    <option value="harian" {{ old('kategori', $hafalan->kategori) === 'harian' ? 'selected' : '' }}>📅 Harian</option>
                                </select>
                                @error('kategori')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Jenis Kegiatan <span style="color:#e53935;">*</span></label>
                                <select name="jenis" class="form-control-custom" required>
                                    <option value="setoran_baru" {{ old('jenis', $hafalan->jenis) === 'setoran_baru' ? 'selected' : '' }}>Setoran Baru</option>
                                    <option value="murojaah"     {{ old('jenis', $hafalan->jenis) === 'murojaah' ? 'selected' : '' }}>Muroja'ah</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ 4) Detail Materi ═══ --}}
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <div class="card-title-custom">③ Detail Materi</div>
                </div>
                <div class="card-body-custom">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Nama Surat <span style="color:#e53935;">*</span></label>
                                <input type="text" name="nama_surat" class="form-control-custom {{ $errors->has('nama_surat') ? 'is-invalid' : '' }}"
                                    value="{{ old('nama_surat', $hafalan->nama_surat) }}" placeholder="cth: Al-Baqarah" required>
                                @error('nama_surat')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Nomor Juz</label>
                                <input type="number" name="nomor_juz" class="form-control-custom"
                                    value="{{ old('nomor_juz', $hafalan->nomor_juz) }}" placeholder="1–30" min="1" max="30">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Halaman Mulai</label>
                                <input type="number" name="halaman_dari" id="halDari" class="form-control-custom"
                                    value="{{ old('halaman_dari', $hafalan->halaman_dari) }}" placeholder="cth: 20" min="1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Halaman Selesai</label>
                                <input type="number" name="halaman_sampai" id="halSampai" class="form-control-custom"
                                    value="{{ old('halaman_sampai', $hafalan->halaman_sampai) }}" placeholder="cth: 23" min="1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Jumlah Halaman</label>
                                <input type="number" name="jumlah_halaman" id="jmlHal" class="form-control-custom"
                                    value="{{ old('jumlah_halaman', $hafalan->jumlah_halaman) }}" placeholder="Otomatis" style="background:#f4f7f4;" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Ayat Dari</label>
                                <input type="number" name="ayat_dari" class="form-control-custom"
                                    value="{{ old('ayat_dari', $hafalan->ayat_dari) }}" placeholder="Opsional" min="1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Ayat Sampai</label>
                                <input type="number" name="ayat_sampai" class="form-control-custom"
                                    value="{{ old('ayat_sampai', $hafalan->ayat_sampai) }}" placeholder="Opsional" min="1">
                                @error('ayat_sampai')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ 5A) Bagian Wisuda ═══ --}}
            <div class="card-custom mb-4 kategori-section" id="sectionWisuda" style="display:none;">
                <div class="card-header-custom" style="background:#fffbeb;">
                    <div class="card-title-custom" style="color:var(--emas-dark);">🎓 Detail Wisuda</div>
                </div>
                <div class="card-body-custom">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Target Wisuda <span style="color:#e53935;">*</span></label>
                                <select name="target_wisuda" class="form-control-custom">
                                    <option value="">-- Pilih Target --</option>
                                    @foreach(['Paket Juz 30','5 Juz','10 Juz','15 Juz','20 Juz','30 Juz (Khatam)'] as $tw)
                                        <option value="{{ $tw }}" {{ old('target_wisuda', $hafalan->target_wisuda) === $tw ? 'selected' : '' }}>{{ $tw }}</option>
                                    @endforeach
                                </select>
                                @error('target_wisuda')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Sesi Wisuda <span style="color:#e53935;">*</span></label>
                                <select name="sesi_wisuda" class="form-control-custom">
                                    <option value="">-- Pilih Sesi --</option>
                                    <option value="setoran_bertahap" {{ old('sesi_wisuda', $hafalan->sesi_wisuda) === 'setoran_bertahap' ? 'selected' : '' }}>Setoran Bertahap</option>
                                    <option value="tasmi" {{ old('sesi_wisuda', $hafalan->sesi_wisuda) === 'tasmi' ? 'selected' : '' }}>Tasmi'</option>
                                </select>
                                @error('sesi_wisuda')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Status Wisuda <span style="color:#e53935;">*</span></label>
                                <select name="status_wisuda" class="form-control-custom">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="lulus" {{ old('status_wisuda', $hafalan->status_wisuda) === 'lulus' ? 'selected' : '' }}>✅ Lulus</option>
                                    <option value="perbaikan" {{ old('status_wisuda', $hafalan->status_wisuda) === 'perbaikan' ? 'selected' : '' }}>⚠️ Perbaikan</option>
                                    <option value="belum_lulus" {{ old('status_wisuda', $hafalan->status_wisuda) === 'belum_lulus' ? 'selected' : '' }}>❌ Belum Lulus</option>
                                </select>
                                @error('status_wisuda')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Catatan Perbaikan</label>
                                <textarea name="catatan_perbaikan" class="form-control-custom" rows="2"
                                    placeholder="Catatan jika ada perbaikan...">{{ old('catatan_perbaikan', $hafalan->catatan_perbaikan) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ 5B) Bagian Zaidah ═══ --}}
            <div class="card-custom mb-4 kategori-section" id="sectionZaidah" style="display:none;">
                <div class="card-header-custom" style="background:#e8f0fe;">
                    <div class="card-title-custom" style="color:#1a3c8e;">📖 Detail Zaidah</div>
                </div>
                <div class="card-body-custom">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Zaidah Ke-</label>
                                <input type="number" name="zaidah_ke" class="form-control-custom"
                                    value="{{ old('zaidah_ke', $hafalan->zaidah_ke) }}" placeholder="cth: 1" min="1">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Keterangan</label>
                                <textarea name="keterangan_zaidah" class="form-control-custom" rows="2"
                                    placeholder="Tambahan di luar target wisuda">{{ old('keterangan_zaidah', $hafalan->keterangan_zaidah) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ 5C) Bagian Ujian ═══ --}}
            <div class="card-custom mb-4 kategori-section" id="sectionUjian" style="display:none;">
                <div class="card-header-custom" style="background:#f0e8fe;">
                    <div class="card-title-custom" style="color:#6b1e9e;">📝 Detail Ujian</div>
                </div>
                <div class="card-body-custom">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Jenis Ujian <span style="color:#e53935;">*</span></label>
                                <select name="jenis_ujian" class="form-control-custom">
                                    <option value="">-- Pilih --</option>
                                    <option value="pekanan" {{ old('jenis_ujian', $hafalan->jenis_ujian) === 'pekanan' ? 'selected' : '' }}>Pekanan</option>
                                    <option value="bulanan" {{ old('jenis_ujian', $hafalan->jenis_ujian) === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                    <option value="tengah_semester" {{ old('jenis_ujian', $hafalan->jenis_ujian) === 'tengah_semester' ? 'selected' : '' }}>Tengah Semester</option>
                                    <option value="semester" {{ old('jenis_ujian', $hafalan->jenis_ujian) === 'semester' ? 'selected' : '' }}>Semester</option>
                                </select>
                                @error('jenis_ujian')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Model Ujian <span style="color:#e53935;">*</span></label>
                                <select name="model_ujian" class="form-control-custom">
                                    <option value="">-- Pilih --</option>
                                    <option value="tasmi" {{ old('model_ujian', $hafalan->model_ujian) === 'tasmi' ? 'selected' : '' }}>Tasmi'</option>
                                    <option value="sambung_ayat" {{ old('model_ujian', $hafalan->model_ujian) === 'sambung_ayat' ? 'selected' : '' }}>Sambung Ayat</option>
                                    <option value="acak_halaman" {{ old('model_ujian', $hafalan->model_ujian) === 'acak_halaman' ? 'selected' : '' }}>Acak Halaman</option>
                                </select>
                                @error('model_ujian')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Status Ujian <span style="color:#e53935;">*</span></label>
                                <select name="status_ujian" id="statusUjianSelect" class="form-control-custom">
                                    <option value="">-- Pilih --</option>
                                    <option value="lulus" {{ old('status_ujian', $hafalan->status_ujian) === 'lulus' ? 'selected' : '' }}>✅ Lulus</option>
                                    <option value="remedial" {{ old('status_ujian', $hafalan->status_ujian) === 'remedial' ? 'selected' : '' }}>🔄 Remedial</option>
                                </select>
                                @error('status_ujian')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4" id="jadwalRemedialWrap" style="display:none;">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Jadwal Remedial <span style="color:#e53935;">*</span></label>
                                <input type="date" name="jadwal_remedial" class="form-control-custom"
                                    value="{{ old('jadwal_remedial', $hafalan->jadwal_remedial?->format('Y-m-d')) }}">
                                @error('jadwal_remedial')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ 6) Penilaian ═══ --}}
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <div class="card-title-custom">④ Penilaian</div>
                </div>
                <div class="card-body-custom">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Nilai (0–100)</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="number" name="nilai" id="nilaiInput" class="form-control-custom"
                                        value="{{ old('nilai', $hafalan->nilai) }}" placeholder="cth: 85" min="0" max="100" step="0.5">
                                    <div id="gradeBadge" class="badge-custom" style="min-width:36px;justify-content:center;font-size:14px;padding:6px 10px;">—</div>
                                </div>
                                <div style="font-size:10px;color:var(--txt3);margin-top:4px;">A≥90 · B≥75 · C≥60 · D&lt;60</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Salah Ringan</label>
                                <input type="number" name="salah_ringan" class="form-control-custom"
                                    value="{{ old('salah_ringan', $hafalan->salah_ringan) }}" placeholder="0" min="0">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Salah Berat</label>
                                <input type="number" name="salah_berat" class="form-control-custom"
                                    value="{{ old('salah_berat', $hafalan->salah_berat) }}" placeholder="0" min="0">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Kelancaran</label>
                                <select name="kelancaran" class="form-control-custom">
                                    <option value="">-</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('kelancaran', $hafalan->kelancaran) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <div style="font-size:9px;color:var(--txt3);margin-top:2px;">Skala 1-5</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Tajwid/Makhraj</label>
                                <select name="tajwid_makhraj" class="form-control-custom">
                                    <option value="">-</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('tajwid_makhraj', $hafalan->tajwid_makhraj) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <div style="font-size:9px;color:var(--txt3);margin-top:2px;">Skala 1-5</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ Catatan ═══ --}}
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <div class="card-title-custom">⑤ Catatan</div>
                </div>
                <div class="card-body-custom">
                    <div class="form-group-custom">
                        <textarea name="catatan" class="form-control-custom" rows="3"
                            placeholder="Catatan tambahan mengenai kualitas hafalan...">{{ old('catatan', $hafalan->catatan) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ═══ Tombol ═══ --}}
            <div class="d-flex gap-3 justify-content-end">
                <a href="{{ route('ustadz.hafalan.index') }}" class="btn-outline-hijau">Batal</a>
                <button type="submit" class="btn-hijau" style="padding:10px 28px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px;"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                    Update Setoran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ── Auto-hitung jumlah halaman ──────────────────────────────
const halDari   = document.getElementById('halDari');
const halSampai = document.getElementById('halSampai');
const jmlHal    = document.getElementById('jmlHal');

function hitungHalaman() {
    const dari   = parseInt(halDari.value)   || 0;
    const sampai = parseInt(halSampai.value) || 0;
    if (dari && sampai && sampai >= dari) {
        jmlHal.value = sampai - dari + 1;
    } else {
        jmlHal.value = '';
    }
}
halDari.addEventListener('input', hitungHalaman);
halSampai.addEventListener('input', hitungHalaman);
hitungHalaman();

// ── Auto-grade dari nilai ───────────────────────────────────
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
updateGrade();

// ── Show/hide bagian kategori ───────────────────────────────
const kategoriSelect = document.getElementById('kategoriSelect');
const sectionWisuda  = document.getElementById('sectionWisuda');
const sectionZaidah  = document.getElementById('sectionZaidah');
const sectionUjian   = document.getElementById('sectionUjian');

function toggleKategori() {
    const val = kategoriSelect.value;
    sectionWisuda.style.display = val === 'wisuda' ? 'block' : 'none';
    sectionZaidah.style.display = val === 'zaidah' ? 'block' : 'none';
    sectionUjian.style.display  = val === 'ujian'  ? 'block' : 'none';
}
kategoriSelect.addEventListener('change', toggleKategori);
toggleKategori();

// ── Show/hide jadwal remedial ───────────────────────────────
const statusUjianSelect  = document.getElementById('statusUjianSelect');
const jadwalRemedialWrap = document.getElementById('jadwalRemedialWrap');

function toggleRemedial() {
    jadwalRemedialWrap.style.display = statusUjianSelect.value === 'remedial' ? 'block' : 'none';
}
statusUjianSelect.addEventListener('change', toggleRemedial);
toggleRemedial();
</script>
@endpush
