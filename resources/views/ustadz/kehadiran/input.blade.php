{{-- resources/views/ustadz/kehadiran/input.blade.php --}}
@extends('layouts.app')
@section('title', 'Input Kehadiran')
@section('page-title', 'Input Kehadiran')
@section('breadcrumb', '/ <span>Input Kehadiran</span>')

@push('styles')
<style>
.status-radio { display: none; }
.status-label {
    display: inline-flex; align-items: center; justify-content: center;
    width: 62px; height: 30px;
    border: 1.5px solid var(--border-light);
    border-radius: 6px;
    font-size: 11px; font-weight: 700;
    cursor: pointer;
    transition: all 0.12s;
    color: var(--txt3);
    background: #f9f9f9;
}
.status-radio:checked + .status-label.lbl-hadir { background: var(--hijau-pale); color: var(--hijau); border-color: var(--hijau); }
.status-radio:checked + .status-label.lbl-izin  { background: var(--emas-light);  color: var(--emas-dark);  border-color: var(--emas); }
.status-radio:checked + .status-label.lbl-sakit { background: #e8f0fe; color: #1a3c8e; border-color: #90caf9; }
.status-radio:checked + .status-label.lbl-alpha { background: #fce8e8; color: #c62828; border-color: #ef9a9a; }
@media (max-width: 767.98px) {
  .kehadiran-input-table { display: none; }
  .kehadiran-input-mobile-list { display: grid; gap: 10px; padding: 12px; }
  .kehadiran-input-card { border: 1px solid var(--border-light); border-radius: 10px; background: #fff; padding: 10px; }
  .status-label { width: 56px; height: 28px; font-size: 10px; }
}
@media (min-width: 768px) { .kehadiran-input-mobile-list { display: none; } }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Input Kehadiran Santri</div>
        <div class="page-header-sub">Absensi harian per kelas / halaqah</div>
    </div>
    <a href="{{ route('ustadz.kehadiran.rekap') }}" class="btn-outline-hijau">
        Lihat Rekap Bulanan →
    </a>
</div>

{{-- Filter Form --}}
<div class="card-custom mb-4">
    <div class="card-body-custom">
        <form method="GET" action="{{ route('ustadz.kehadiran.input') }}" id="filterForm">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label-custom">Pilih Kelas / Halaqah</label>
                    <select name="kelas_id" class="form-control-custom" onchange="this.form.submit()" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }} — {{ $kelas->jumlah_santri }} santri
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-custom">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control-custom"
                        value="{{ $tanggal }}" max="{{ date('Y-m-d') }}"
                        onchange="this.form.submit()">
                </div>
                <div class="col-md-2">
                    <label class="form-label-custom">Sesi</label>
                    <select name="sesi_kehadiran_id" class="form-control-custom" onchange="this.form.submit()">
                        @foreach($sesiList as $sesi)
                            <option value="{{ $sesi->id }}" {{ $sesiId == $sesi->id ? 'selected' : '' }}>{{ $sesi->nama_sesi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-custom">&nbsp;</label>
                    @if($santriList->isNotEmpty())
                        <div style="font-size:12px;color:var(--txt3);padding:8px 0;">
                            {{ $santriList->count() }} santri ditemukan
                            @if($santriList->where('sudah_absen', true)->count() > 0)
                                · <span style="color:var(--emas-dark);font-weight:600;">{{ $santriList->where('sudah_absen', true)->count() }} sudah diabsen</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

@if($santriList->isNotEmpty())
    <form method="POST" action="{{ route('ustadz.kehadiran.store-bulk') }}">
        @csrf
        <input type="hidden" name="tanggal"           value="{{ $tanggal }}">
        <input type="hidden" name="sesi_kehadiran_id"  value="{{ $sesiId }}">
        <input type="hidden" name="kelas_id"            value="{{ $kelasId }}">

        <div class="card-custom mb-4">
            {{-- Quick Actions --}}
            <div class="card-header-custom">
                <div class="card-title-custom">
                    Absensi {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                    — Sesi {{ $sesiList->firstWhere('id', $sesiId)?->nama_sesi ?? '-' }}
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn-outline-hijau" style="padding:5px 12px;font-size:11px;" onclick="setAllStatus('hadir')">
                        Semua Hadir
                    </button>
                    <button type="button" class="btn-danger-custom" onclick="setAllStatus('alpha')">
                        Semua Alpha
                    </button>
                </div>
            </div>

            <div class="table-responsive kehadiran-input-table">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th>Nama Santri</th>
                            <th>Kamar</th>
                            <th>Status Kehadiran</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($santriList as $idx => $s)
                            <tr id="row_{{ $s->id }}" class="{{ $s->sudah_absen ? 'bg-light' : '' }}">
                                <td style="color:var(--txt3);font-size:12px;">{{ $idx + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="td-avatar">{{ strtoupper(substr($s->nama, 0, 2)) }}</div>
                                        <div>
                                            <div class="td-name-main">{{ $s->nama }}</div>
                                            <div class="td-name-sub">NIS: {{ $s->nis }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size:12px;">{{ $s->kamar->nama_kamar ?? '-' }}</td>
                                <td>
                                    <div class="d-flex gap-1 align-items-center flex-wrap">
                                        @foreach(['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpha' => 'Alpha'] as $val => $lbl)
                                            <input type="radio" class="status-radio" name="absensi[{{ $s->id }}][status]"
                                                id="status_{{ $s->id }}_{{ $val }}" value="{{ $val }}"
                                                {{ $s->status_absen === $val ? 'checked' : '' }}>
                                            <label for="status_{{ $s->id }}_{{ $val }}" class="status-label lbl-{{ $val }}">
                                                {{ $lbl }}
                                            </label>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="absensi[{{ $s->id }}][keterangan]"
                                        class="form-control-custom" style="height:32px;font-size:12px;"
                                        value="{{ $s->keterangan }}"
                                        placeholder="Keterangan...">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="kehadiran-input-mobile-list">
                @foreach($santriList as $s)
                    <div class="kehadiran-input-card">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="td-avatar">{{ strtoupper(substr($s->nama, 0, 2)) }}</div>
                            <div>
                                <div class="td-name-main">{{ $s->nama }}</div>
                                <div class="td-name-sub">NIS: {{ $s->nis }} · Kamar: {{ $s->kamar->nama_kamar ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="d-flex gap-1 align-items-center flex-wrap mb-2">
                            @foreach(['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpha' => 'Alpha'] as $val => $lbl)
                                <input type="radio" class="status-radio" name="absensi[{{ $s->id }}][status]"
                                    id="m_status_{{ $s->id }}_{{ $val }}" value="{{ $val }}"
                                    {{ $s->status_absen === $val ? 'checked' : '' }}>
                                <label for="m_status_{{ $s->id }}_{{ $val }}" class="status-label lbl-{{ $val }}">{{ $lbl }}</label>
                            @endforeach
                        </div>
                        <input type="text" name="absensi[{{ $s->id }}][keterangan]" class="form-control-custom" style="height:32px;font-size:12px;" value="{{ $s->keterangan }}" placeholder="Keterangan...">
                    </div>
                @endforeach
            </div>

            {{-- Summary & Submit --}}
            <div class="card-header-custom" style="justify-content:space-between;">
                <div class="d-flex gap-3" id="absensiSummary">
                    <span style="font-size:12px;" class="badge-custom badge-green">Hadir: <span id="countHadir">0</span></span>
                    <span style="font-size:12px;" class="badge-custom badge-gold">Izin: <span id="countIzin">0</span></span>
                    <span style="font-size:12px;" class="badge-custom badge-blue">Sakit: <span id="countSakit">0</span></span>
                    <span style="font-size:12px;" class="badge-custom badge-red">Alpha: <span id="countAlpha">0</span></span>
                </div>
                <button type="submit" class="btn-hijau">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                    Simpan Absensi ({{ $santriList->count() }} Santri)
                </button>
            </div>
        </div>
    </form>

@elseif($kelasId)
    <div class="card-custom text-center py-5" style="color:var(--txt3);">
        Tidak ada santri aktif di kelas ini.
    </div>
@else
    <div class="card-custom text-center py-5" style="color:var(--txt3);">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="opacity:0.3;margin:0 auto 12px;display:block;"><path d="M9 11l3 3L22 4"/></svg>
        Pilih kelas/halaqah dan tanggal untuk memulai input absensi.
    </div>
@endif
@endsection

@push('scripts')
<script>
/**
 * Hanya radio aktif (desktop ATAU mobile) yang di-set, karena kedua blok
 * memakai name yang sama per santri — memilih semua termasuk yang disabled
 * membuat browser membatalkan pilihan per baris.
 */
function setAllStatus(status) {
    document.querySelectorAll('input.status-radio[type="radio"][value="' + status + '"]').forEach(function (r) {
        if (!r.disabled) {
            r.checked = true;
        }
    });
    updateSummary();
}

function updateSummary() {
    const counts = { hadir: 0, izin: 0, sakit: 0, alpha: 0 };
    document.querySelectorAll('input.status-radio[type="radio"]:checked').forEach(function (r) {
        if (r.disabled) {
            return;
        }
        if (counts[r.value] !== undefined) {
            counts[r.value]++;
        }
    });
    document.getElementById('countHadir').textContent = counts.hadir;
    document.getElementById('countIzin').textContent = counts.izin;
    document.getElementById('countSakit').textContent = counts.sakit;
    document.getElementById('countAlpha').textContent = counts.alpha;
}

function syncInputMode() {
    const isMobile = window.matchMedia('(max-width: 767.98px)').matches;
    document.querySelectorAll('.kehadiran-input-table input').forEach(function (el) {
        el.disabled = isMobile;
    });
    document.querySelectorAll('.kehadiran-input-mobile-list input').forEach(function (el) {
        el.disabled = !isMobile;
    });
    updateSummary();
}

document.querySelectorAll('input.status-radio[type="radio"]').forEach(function (r) {
    r.addEventListener('change', updateSummary);
});
window.addEventListener('resize', syncInputMode);
syncInputMode();
</script>
@endpush
