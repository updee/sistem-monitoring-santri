{{-- resources/views/ustadz/hafalan/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Hafalan')
@section('page-title', 'Detail Hafalan')
@section('breadcrumb', '/ Hafalan / <span>Detail</span>')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="page-header">
            <div>
                <div class="page-header-title">Detail Setoran Hafalan</div>
                <div class="page-header-sub">{{ $hafalan->santri?->nama ?? '-' }} — {{ $hafalan->tanggal_setoran?->format('d M Y') }}</div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('ustadz.hafalan.edit', $hafalan) }}" class="btn-outline-hijau">Edit</a>
                <a href="{{ route('ustadz.hafalan.index') }}" class="btn-outline-hijau">← Kembali</a>
            </div>
        </div>

        {{-- Identitas --}}
        <div class="card-custom mb-4">
            <div class="card-header-custom">
                <div class="card-title-custom">Identitas Setoran</div>
                <div class="d-flex gap-2">
                    @if($hafalan->kategori)
                        <span class="badge-custom {{ $hafalan->kategori_badge_color }}">{{ $hafalan->kategori_label }}</span>
                    @endif
                    <span class="badge-custom {{ $hafalan->jenis === 'setoran_baru' ? 'badge-green' : 'badge-blue' }}">{{ $hafalan->jenis_label }}</span>
                </div>
            </div>
            <div class="card-body-custom">
                <div class="row g-3">
                    @foreach([
                        ['Santri', $hafalan->santri?->nama . ' (' . ($hafalan->santri?->nis ?? '-') . ')'],
                        ['Kelas', $hafalan->santri?->kelas?->nama_kelas ?? '-'],
                        ['Penyimak', $hafalan->ustadz?->name ?? '-'],
                        ['Tanggal', $hafalan->tanggal_setoran?->format('d M Y')],
                    ] as [$label, $value])
                    <div class="col-md-6">
                        <div style="font-size:10px;color:var(--txt3);font-weight:700;text-transform:uppercase;">{{ $label }}</div>
                        <div style="font-size:13px;font-weight:600;margin-top:2px;">{{ $value }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Detail Materi --}}
        <div class="card-custom mb-4">
            <div class="card-header-custom">
                <div class="card-title-custom">Detail Materi</div>
            </div>
            <div class="card-body-custom">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div style="font-size:10px;color:var(--txt3);font-weight:700;text-transform:uppercase;">Surat</div>
                        <div style="font-size:13px;font-weight:600;margin-top:2px;">{{ $hafalan->nama_surat }}</div>
                    </div>
                    <div class="col-md-2">
                        <div style="font-size:10px;color:var(--txt3);font-weight:700;text-transform:uppercase;">Juz</div>
                        <div style="font-size:13px;font-weight:600;margin-top:2px;">{{ $hafalan->nomor_juz ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <div style="font-size:10px;color:var(--txt3);font-weight:700;text-transform:uppercase;">Halaman</div>
                        <div style="font-size:13px;font-weight:600;margin-top:2px;">{{ $hafalan->halaman_dari }}–{{ $hafalan->halaman_sampai }} ({{ $hafalan->jumlah_halaman }} hal)</div>
                    </div>
                    @if($hafalan->ayat_dari)
                    <div class="col-md-3">
                        <div style="font-size:10px;color:var(--txt3);font-weight:700;text-transform:uppercase;">Ayat</div>
                        <div style="font-size:13px;font-weight:600;margin-top:2px;">{{ $hafalan->ayat_dari }}–{{ $hafalan->ayat_sampai ?? '?' }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Detail Wisuda --}}
        @if($hafalan->kategori === 'wisuda')
        <div class="card-custom mb-4">
            <div class="card-header-custom" style="background:#fffbeb;">
                <div class="card-title-custom" style="color:var(--emas-dark);">🎓 Detail Wisuda</div>
            </div>
            <div class="card-body-custom">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div style="font-size:10px;color:var(--txt3);font-weight:700;text-transform:uppercase;">Target Wisuda</div>
                        <div style="font-size:13px;font-weight:600;margin-top:2px;">{{ $hafalan->target_wisuda_label }}</div>
                    </div>
                    <div class="col-md-4">
                        <div style="font-size:10px;color:var(--txt3);font-weight:700;text-transform:uppercase;">Sesi Wisuda</div>
                        <div style="font-size:13px;font-weight:600;margin-top:2px;">{{ $hafalan->sesi_wisuda_label }}</div>
                    </div>
                    <div class="col-md-4">
                        <div style="font-size:10px;color:var(--txt3);font-weight:700;text-transform:uppercase;">Status Wisuda</div>
                        <div style="margin-top:2px;"><span class="badge-custom {{ $hafalan->status_wisuda_badge }}">{{ $hafalan->status_wisuda_label }}</span></div>
                    </div>
                    @if($hafalan->catatan_perbaikan)
                    <div class="col-md-12">
                        <div style="font-size:10px;color:var(--txt3);font-weight:700;text-transform:uppercase;">Catatan Perbaikan</div>
                        <div style="font-size:13px;margin-top:2px;background:#fffbeb;padding:8px 12px;border-radius:6px;border:1px solid #f0e0a0;">{{ $hafalan->catatan_perbaikan }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Detail Zaidah --}}
        @if($hafalan->kategori === 'zaidah')
        <div class="card-custom mb-4">
            <div class="card-header-custom" style="background:#e8f0fe;">
                <div class="card-title-custom" style="color:#1a3c8e;">📖 Detail Zaidah</div>
            </div>
            <div class="card-body-custom">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div style="font-size:10px;color:var(--txt3);font-weight:700;text-transform:uppercase;">Zaidah Ke-</div>
                        <div style="font-size:13px;font-weight:600;margin-top:2px;">{{ $hafalan->zaidah_ke ?? '-' }}</div>
                    </div>
                    <div class="col-md-8">
                        <div style="font-size:10px;color:var(--txt3);font-weight:700;text-transform:uppercase;">Keterangan</div>
                        <div style="font-size:13px;margin-top:2px;">{{ $hafalan->keterangan_zaidah ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Detail Ujian --}}
        @if($hafalan->kategori === 'ujian')
        <div class="card-custom mb-4">
            <div class="card-header-custom" style="background:#f0e8fe;">
                <div class="card-title-custom" style="color:#6b1e9e;">📝 Detail Ujian</div>
            </div>
            <div class="card-body-custom">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div style="font-size:10px;color:var(--txt3);font-weight:700;text-transform:uppercase;">Jenis Ujian</div>
                        <div style="font-size:13px;font-weight:600;margin-top:2px;">{{ $hafalan->jenis_ujian_label }}</div>
                    </div>
                    <div class="col-md-4">
                        <div style="font-size:10px;color:var(--txt3);font-weight:700;text-transform:uppercase;">Model Ujian</div>
                        <div style="font-size:13px;font-weight:600;margin-top:2px;">{{ $hafalan->model_ujian_label }}</div>
                    </div>
                    <div class="col-md-4">
                        <div style="font-size:10px;color:var(--txt3);font-weight:700;text-transform:uppercase;">Status Ujian</div>
                        <div style="margin-top:2px;"><span class="badge-custom {{ $hafalan->status_ujian_badge }}">{{ $hafalan->status_ujian_label }}</span></div>
                    </div>
                    @if($hafalan->status_ujian === 'remedial' && $hafalan->jadwal_remedial)
                    <div class="col-md-4">
                        <div style="font-size:10px;color:var(--txt3);font-weight:700;text-transform:uppercase;">Jadwal Remedial</div>
                        <div style="font-size:13px;font-weight:600;margin-top:2px;color:#c62828;">{{ $hafalan->jadwal_remedial->format('d M Y') }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Penilaian --}}
        <div class="card-custom mb-4">
            <div class="card-header-custom">
                <div class="card-title-custom">Penilaian</div>
                @if($hafalan->grade)
                    <span class="badge-custom grade-{{ strtolower($hafalan->grade) }}" style="font-size:14px;padding:4px 12px;">{{ $hafalan->grade }}</span>
                @endif
            </div>
            <div class="card-body-custom">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div style="font-size:10px;color:var(--txt3);font-weight:700;text-transform:uppercase;">Nilai</div>
                        <div style="font-size:20px;font-weight:800;margin-top:2px;">{{ $hafalan->nilai ? number_format($hafalan->nilai, 1) : '-' }}</div>
                    </div>
                    <div class="col-md-2">
                        <div style="font-size:10px;color:var(--txt3);font-weight:700;text-transform:uppercase;">Salah Ringan</div>
                        <div style="font-size:13px;font-weight:600;margin-top:2px;">{{ $hafalan->salah_ringan ?? '-' }}</div>
                    </div>
                    <div class="col-md-2">
                        <div style="font-size:10px;color:var(--txt3);font-weight:700;text-transform:uppercase;">Salah Berat</div>
                        <div style="font-size:13px;font-weight:600;margin-top:2px;">{{ $hafalan->salah_berat ?? '-' }}</div>
                    </div>
                    <div class="col-md-2">
                        <div style="font-size:10px;color:var(--txt3);font-weight:700;text-transform:uppercase;">Kelancaran</div>
                        <div style="font-size:13px;font-weight:600;margin-top:2px;">{{ $hafalan->kelancaran ? $hafalan->kelancaran . '/5' : '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <div style="font-size:10px;color:var(--txt3);font-weight:700;text-transform:uppercase;">Tajwid/Makhraj</div>
                        <div style="font-size:13px;font-weight:600;margin-top:2px;">{{ $hafalan->tajwid_makhraj ? $hafalan->tajwid_makhraj . '/5' : '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Catatan --}}
        @if($hafalan->catatan)
        <div class="card-custom mb-4">
            <div class="card-header-custom">
                <div class="card-title-custom">Catatan</div>
            </div>
            <div class="card-body-custom">
                <p style="font-size:13px;color:var(--txt2);margin:0;">{{ $hafalan->catatan }}</p>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
