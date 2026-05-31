{{-- resources/views/ustadz/pelanggaran/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Catat Pelanggaran')
@section('page-title', 'Catat Pelanggaran')
@section('breadcrumb', '/ <a href="' . route('ustadz.pelanggaran.index') . '">Pelanggaran</a> / <span>Catat Baru</span>')

@push('styles')
<style>
.form-section { border: 1px solid var(--border-light); border-radius: var(--radius-lg); background: #fff; margin-bottom: 16px; }
.form-section-header { padding: 14px 18px; border-bottom: 1px solid var(--border-light); font-size: 14px; font-weight: 700; color: var(--hijau); display: flex; align-items: center; gap: 8px; }
.form-section-body { padding: 16px 18px; }
.tingkat-indicator { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
.tingkat-ringan { background: var(--emas-light); color: var(--emas-dark); }
.tingkat-sedang { background: #f0e8fe; color: #6b1e9e; }
.tingkat-berat  { background: #fce8e8; color: #c62828; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Catat Pelanggaran Baru</div>
        <div class="page-header-sub">Isi semua field yang diperlukan</div>
    </div>
    <a href="{{ route('ustadz.pelanggaran.index') }}" class="btn-outline-hijau">← Kembali</a>
</div>

<form method="POST" action="{{ route('ustadz.pelanggaran.store') }}">
    @csrf

    {{-- ═══ 1) Identitas Santri ═══ --}}
    <div class="form-section">
        <div class="form-section-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            ① Identitas Santri
        </div>
        <div class="form-section-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Santri <span style="color:#c62828;">*</span></label>
                        <select name="santri_id" class="form-control-custom" required>
                            <option value="">-- Pilih Santri --</option>
                            @foreach($santriList as $s)
                                <option value="{{ $s->id }}" {{ old('santri_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama }} — {{ $s->nis }} ({{ $s->kelas->nama_kelas ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        @error('santri_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Tanggal <span style="color:#c62828;">*</span></label>
                        <input type="date" name="tanggal" class="form-control-custom"
                            value="{{ old('tanggal', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                        @error('tanggal')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Pencatat</label>
                        <input type="text" class="form-control-custom" value="{{ Auth::user()->name }}" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ 2) Detail Pelanggaran ═══ --}}
    <div class="form-section">
        <div class="form-section-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            ② Detail Pelanggaran
        </div>
        <div class="form-section-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Kategori Pelanggaran <span style="color:#c62828;">*</span></label>
                        <select name="kategori_id" id="kategoriSelect" class="form-control-custom" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoriList as $k)
                                <option value="{{ $k->id }}"
                                    data-tingkat="{{ $k->tingkat }}"
                                    data-poin="{{ $k->poin_default }}"
                                    {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kategori }} ({{ ucfirst($k->tingkat) }} — {{ $k->poin_default }} poin)
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')<div class="form-error">{{ $message }}</div>@enderror

                        {{-- Indicator tingkat --}}
                        <div id="tingkatIndicator" class="mt-2" style="display:none;">
                            <span id="tingkatBadge" class="tingkat-indicator"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Poin Sanksi <span style="color:#c62828;">*</span></label>
                        <input type="number" name="poin_sanksi" id="poinSanksi" class="form-control-custom"
                            value="{{ old('poin_sanksi') }}" min="1" max="200" required
                            placeholder="Otomatis dari kategori">
                        @error('poin_sanksi')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Tingkat</label>
                        <input type="text" id="tingkatDisplay" class="form-control-custom" disabled
                            placeholder="Otomatis" value="{{ old('kategori_id') ? '' : '' }}">
                    </div>
                </div>
            </div>
            <div class="row g-3 mt-1">
                <div class="col-md-12">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Nama / Jenis Pelanggaran <span style="color:#c62828;">*</span></label>
                        <input type="text" name="jenis_pelanggaran" class="form-control-custom"
                            value="{{ old('jenis_pelanggaran') }}" required
                            placeholder="Contoh: Terlambat hadir shalat Subuh, Membawa HP tanpa izin, dll.">
                        @error('jenis_pelanggaran')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ 3) Keterangan & Tindak Lanjut ═══ --}}
    <div class="form-section">
        <div class="form-section-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            ③ Keterangan & Tindak Lanjut
        </div>
        <div class="form-section-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Keterangan Tambahan</label>
                        <textarea name="keterangan" class="form-control-custom" rows="3"
                            placeholder="Deskripsi detail kejadian...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Status Tindak Lanjut <span style="color:#c62828;">*</span></label>
                        <select name="status_tindak_lanjut" class="form-control-custom" required>
                            <option value="belum" {{ old('status_tindak_lanjut', 'belum') === 'belum' ? 'selected' : '' }}>Belum</option>
                            <option value="sudah" {{ old('status_tindak_lanjut') === 'sudah' ? 'selected' : '' }}>Sudah</option>
                        </select>
                    </div>
                    <div class="form-group-custom">
                        <label class="form-label-custom">Tindak Lanjut</label>
                        <input type="text" name="tindak_lanjut" class="form-control-custom"
                            value="{{ old('tindak_lanjut') }}" placeholder="Catatan tindak lanjut...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Submit --}}
    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('ustadz.pelanggaran.index') }}" class="btn-outline-hijau">Batal</a>
        <button type="submit" class="btn-hijau">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
            Simpan Pelanggaran
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kategoriSelect = document.getElementById('kategoriSelect');
    const poinInput = document.getElementById('poinSanksi');
    const tingkatDisplay = document.getElementById('tingkatDisplay');
    const tingkatIndicator = document.getElementById('tingkatIndicator');
    const tingkatBadge = document.getElementById('tingkatBadge');

    function updateFromKategori() {
        const opt = kategoriSelect.options[kategoriSelect.selectedIndex];
        if (!opt || !opt.value) {
            poinInput.value = '';
            tingkatDisplay.value = '';
            tingkatIndicator.style.display = 'none';
            return;
        }
        const tingkat = opt.dataset.tingkat;
        const poin = opt.dataset.poin;
        poinInput.value = poin;
        tingkatDisplay.value = tingkat.charAt(0).toUpperCase() + tingkat.slice(1);

        // Show badge
        tingkatIndicator.style.display = 'block';
        tingkatBadge.className = 'tingkat-indicator tingkat-' + tingkat;
        const labels = { ringan: '⚠️ Ringan', sedang: '🟡 Sedang', berat: '🔴 Berat' };
        tingkatBadge.textContent = (labels[tingkat] || tingkat) + ' — ' + poin + ' poin';
    }

    kategoriSelect.addEventListener('change', updateFromKategori);

    // Initialize on page load if old value exists
    if (kategoriSelect.value) {
        updateFromKategori();
    }
});
</script>
@endpush
