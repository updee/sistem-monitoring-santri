{{-- resources/views/ustadz/pelanggaran/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Catat Pelanggaran')
@section('page-title', 'Catat Pelanggaran')
@section('breadcrumb', '/ Pelanggaran / <span>Catat Baru</span>')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
@php
    $existingJenis = old('jenis_pelanggaran', $pelanggaran->jenis_pelanggaran ?? '');
    $jenisTexts = collect($jenisPelanggaranList ?? [])->pluck('text')->all();
    $isExistingManual = $existingJenis && !in_array($existingJenis, $jenisTexts, true);
    $selectedKategoriPoin = old('kategori_poin');
    if (!$selectedKategoriPoin && isset($pelanggaran)) {
        $selectedKategoriPoin = (($pelanggaran->kategori_id ?? 0) . '|' . ($pelanggaran->poin_sanksi ?? 0) . '|' . ($pelanggaran->kategori?->tingkat ?? ''));
    }
@endphp
<div class="page-header">
    <div><div class="page-header-title">Catat Pelanggaran</div><div class="page-header-sub">Catat pelanggaran disiplin santri</div></div>
    <a href="{{ route('ustadz.pelanggaran.index') }}" class="btn-outline-hijau">← Kembali</a>
</div>
<form method="POST" action="{{ isset($pelanggaran) ? route('ustadz.pelanggaran.update', $pelanggaran) : route('ustadz.pelanggaran.store') }}">
@csrf
@if(isset($pelanggaran)) @method('PUT') @endif
<div class="card-custom mb-4">
    <div class="card-header-custom"><div class="card-title-custom">Data Santri & Pelanggaran</div></div>
    <div class="card-body-custom">
        <div class="form-group-custom">
            <label class="form-label-custom">Pilih Santri <span style="color:#e53935;">*</span></label>
            @php
                $selectedSantriId = old('santri_id', $pelanggaran->santri_id ?? null);
                $selectedSantri = $santriList->firstWhere('id', (int) $selectedSantriId);
                $selectedSantriLabel = $selectedSantri
                    ? ($selectedSantri->nama . ' (' . $selectedSantri->nis . ') — ' . ($selectedSantri->kelas->nama_kelas ?? '-'))
                    : '';
            @endphp
            <input type="hidden" name="santri_id" id="santriIdInput" value="{{ $selectedSantriId }}">
            <input
                type="text"
                id="santriAutocomplete"
                class="form-control-custom {{ $errors->has('santri_id') ? 'is-invalid':'' }}"
                list="santriOptions"
                placeholder="Ketik nama/nis santri..."
                value="{{ old('santri_label', $selectedSantriLabel) }}"
                autocomplete="off"
                required
            >
            <datalist id="santriOptions">
                @foreach($santriList as $s)
                    <option value="{{ $s->nama }} ({{ $s->nis }}) — {{ $s->kelas->nama_kelas ?? '-' }}" data-id="{{ $s->id }}"></option>
                @endforeach
            </datalist>
            <div style="font-size:10px;color:var(--txt3);margin-top:3px;">Ketik beberapa huruf, saran nama akan muncul otomatis.</div>
            @error('santri_id')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="row g-3">
            <div class="col-md-12">
                <div class="form-group-custom">
                    <label class="form-label-custom">Jenis Pelanggaran <span style="color:#e53935;">*</span></label>
                    <select name="jenis_pelanggaran" id="jenisSelect" class="form-control-custom {{ $errors->has('jenis_pelanggaran') ? 'is-invalid':'' }}" onchange="handleJenisChange()">
                        <option value="">-- Pilih Jenis Pelanggaran --</option>
                        @foreach($jenisPelanggaranList as $jenis)
                            <option
                                value="{{ $jenis['text'] }}"
                                data-kategori-poin="{{ $jenis['kategori_poin'] }}"
                                {{ old('jenis_pelanggaran', $pelanggaran->jenis_pelanggaran ?? '') === $jenis['text'] ? 'selected' : '' }}
                            >
                                {{ $jenis['label'] }}
                            </option>
                        @endforeach
                        <option value="__manual__" {{ old('jenis_pelanggaran') === '__manual__' || $isExistingManual ? 'selected' : '' }}>Lainnya (Input Manual)</option>
                    </select>
                    @error('jenis_pelanggaran')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-12" id="manualJenisWrapper" style="display:none;">
                <div class="form-group-custom">
                    <label class="form-label-custom">Input Jenis Pelanggaran Manual</label>
                    <input type="text" name="jenis_pelanggaran_manual" class="form-control-custom {{ $errors->has('jenis_pelanggaran_manual') ? 'is-invalid':'' }}"
                        value="{{ old('jenis_pelanggaran_manual', $isExistingManual ? $existingJenis : '') }}" placeholder="Contoh: Tidak menjaga kebersihan kelas">
                    @error('jenis_pelanggaran_manual')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group-custom">
                    <label class="form-label-custom">Kategori & Poin <span style="color:#e53935;">*</span></label>
                    <select name="kategori_poin" id="kategoriSelect" class="form-control-custom {{ $errors->has('kategori_poin') ? 'is-invalid':'' }}">
                        <option value="">-- Pilih Kategori & Poin --</option>
                        @foreach($kategoriPoinOptions as $opt)
                            <option value="{{ $opt['value'] }}" {{ $selectedKategoriPoin === $opt['value'] ? 'selected' : '' }}>
                                {{ $opt['label'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_poin')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-group-custom">
                    <label class="form-label-custom">Tanggal Kejadian <span style="color:#e53935;">*</span></label>
                    <input type="date" name="tanggal" class="form-control-custom"
                        value="{{ old('tanggal', isset($pelanggaran) ? $pelanggaran->tanggal?->format('Y-m-d') : date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group-custom">
                    <label class="form-label-custom">Status Tindak Lanjut</label>
                    <select name="status_tindak_lanjut" class="form-control-custom">
                        <option value="belum" {{ old('status_tindak_lanjut', $pelanggaran->status_tindak_lanjut ?? 'belum') === 'belum' ? 'selected':'' }}>Belum ditindaklanjuti</option>
                        <option value="sudah" {{ old('status_tindak_lanjut', $pelanggaran->status_tindak_lanjut ?? '') === 'sudah' ? 'selected':'' }}>Sudah ditindaklanjuti</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group-custom">
            <label class="form-label-custom">Keterangan Detail</label>
            <textarea name="keterangan" class="form-control-custom" rows="3"
                placeholder="Uraian lengkap kejadian pelanggaran...">{{ old('keterangan', $pelanggaran->keterangan ?? '') }}</textarea>
        </div>
        <div class="form-group-custom">
            <label class="form-label-custom">Tindak Lanjut</label>
            <textarea name="tindak_lanjut" class="form-control-custom" rows="2"
                placeholder="Sanksi atau tindakan yang sudah/akan diberikan...">{{ old('tindak_lanjut', $pelanggaran->tindak_lanjut ?? '') }}</textarea>
        </div>
    </div>
</div>
<div class="d-flex gap-3 justify-content-end">
    <a href="{{ route('ustadz.pelanggaran.index') }}" class="btn-outline-hijau">Batal</a>
    <button type="submit" class="btn-hijau" style="padding:10px 28px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:16px;height:16px;"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
        Simpan Pelanggaran
    </button>
</div>
</form>
</div>
</div>
@endsection
@push('scripts')
<script>
function autoPoin(sel) {
    return sel;
}
function ucfirst(s) { return s ? s.charAt(0).toUpperCase()+s.slice(1) : ''; }
function toggleManualJenis() {
    const isManual = document.getElementById('jenisSelect').value === '__manual__';
    document.getElementById('manualJenisWrapper').style.display = isManual ? 'block' : 'none';
}
function handleJenisChange() {
    const jenisSelect = document.getElementById('jenisSelect');
    const isManual = jenisSelect.value === '__manual__';
    toggleManualJenis();
    if (isManual) return;
    const kategoriPoin = jenisSelect.options[jenisSelect.selectedIndex]?.dataset?.kategoriPoin || '';
    if (kategoriPoin) {
        document.getElementById('kategoriSelect').value = kategoriPoin;
    }
}
document.addEventListener('DOMContentLoaded', function () {
    handleJenisChange();

    const santriInput = document.getElementById('santriAutocomplete');
    const santriIdInput = document.getElementById('santriIdInput');
    const datalist = document.getElementById('santriOptions');

    function syncSantriId() {
        const val = (santriInput.value || '').trim();
        let matchedId = '';
        for (const opt of datalist.options) {
            if (opt.value === val) {
                matchedId = opt.dataset.id || '';
                break;
            }
        }
        santriIdInput.value = matchedId;
    }

    santriInput.addEventListener('input', syncSantriId);
    santriInput.addEventListener('change', syncSantriId);
    syncSantriId();
});
</script>
@endpush


{{-- ════════════════════════════════════════════════════════════
     resources/views/ustadz/pencapaian/index.blade.php
     ════════════════════════════════════════════════════════════ --}}
