{{-- resources/views/admin/santri/rekap.blade.php --}}
@extends('layouts.app')
@section('title','Detail Santri')
@section('page-title','Detail Santri')
@section('breadcrumb','/ Data Santri / <span>Detail</span>')

@section('content')
<div class="page-header">
    <div><div class="page-header-title">{{ $santri->nama }}</div><div class="page-header-sub">NIS: {{ $santri->nis }} · {{ $santri->status_label }}</div></div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.santri.edit', $santri) }}" class="btn-outline-hijau">Edit Data</a>
        <a href="{{ route('admin.santri.index') }}" class="btn-outline-hijau">← Kembali</a>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Profil --}}
    <div class="col-lg-4">
        <div class="card-custom h-100">
            <div class="card-body-custom text-center" style="padding:24px;">
                <div style="width:80px;height:80px;border-radius:50%;background:var(--hijau-pale);display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800;color:var(--hijau);margin:0 auto 16px;">
                    @if($santri->foto)
                        <img src="{{ Storage::url($santri->foto) }}" alt="foto" style="width:80px;height:80px;border-radius:50%;object-fit:cover;">
                    @else
                        {{ strtoupper(substr($santri->nama,0,2)) }}
                    @endif
                </div>
                <div style="font-size:16px;font-weight:800;color:var(--txt);">{{ $santri->nama }}</div>
                <div style="font-size:12px;color:var(--txt3);margin-top:4px;">{{ $santri->nis }}</div>
                <span class="badge-custom badge-green mt-2">{{ $santri->status_label }}</span>
                <hr style="border-color:var(--border-light);margin:16px 0;">
                <div style="display:flex;flex-direction:column;gap:8px;text-align:left;">
                    @foreach([
                        ['Jenis Kelamin', $santri->jenis_kelamin_label],
                        ['Tempat Lahir', $santri->tempat_lahir??'-'],
                        ['Tanggal Lahir', $santri->tanggal_lahir?->format('d M Y')??'-'],
                        ['Kelas', $santri->kelas->nama_kelas??'-'],
                        ['Kamar', $santri->kamar->nama_kamar??'-'],
                        ['Wali', $santri->wali->name??'-'],
                        ['Tlp Wali', $santri->wali->no_telepon??'-'],
                        ['Tgl Masuk', $santri->tanggal_masuk?->format('d M Y')??'-'],
                    ] as [$lbl,$val])
                    <div class="d-flex justify-content-between" style="font-size:12px;">
                        <span style="color:var(--txt3);">{{ $lbl }}</span>
                        <span style="font-weight:600;text-align:right;max-width:55%;">{{ $val }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="col-lg-8">
        <div class="row g-3 mb-3">
            <div class="col-6 col-md-3">
                <div class="stat-card text-center">
                    <div class="stat-card-value" style="color:var(--emas-dark);">{{ $santri->total_halaman_hafalan }}</div>
                    <div class="stat-card-label">Halaman hafalan (semua jenis)</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card text-center">
                    <div class="stat-card-value" style="color:var(--hijau);">{{ $santri->persentase_kehadiran_bulan_ini }}<span style="font-size:14px;">%</span></div>
                    <div class="stat-card-label">Kehadiran Bulan Ini</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card text-center">
                    <div class="stat-card-value" style="color:#c62828;">{{ $santri->total_poin_pelanggaran }}</div>
                    <div class="stat-card-label">Poin Pelanggaran</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card text-center">
                    <div class="stat-card-value" style="color:#1a3c8e;">{{ $santri->pencapaian->count() }}</div>
                    <div class="stat-card-label">Pencapaian</div>
                </div>
            </div>
        </div>

        {{-- Hafalan terbaru --}}
        <div class="card-custom mb-3">
            <div class="card-header-custom"><div class="card-title-custom">Setoran Hafalan Terbaru</div></div>
            <div class="table-responsive">
                <table class="table-custom">
                    <thead><tr><th>Surat</th><th>Hal.</th><th>Nilai</th><th>Grade</th><th>Kategori</th><th>Jenis</th><th>Tanggal</th></tr></thead>
                    <tbody>
                        @forelse($santri->hafalan->take(5) as $hf)
                            <tr>
                                <td><div class="td-name-main">{{ $hf->nama_surat }}</div><div class="td-name-sub">Juz {{ $hf->nomor_juz??'-' }}</div></td>
                                <td style="font-size:12px;">{{ $hf->jumlah_halaman }}</td>
                                <td style="font-weight:700;">{{ $hf->nilai?number_format($hf->nilai,1):'-' }}</td>
                                <td>@if($hf->grade)<span class="badge-custom grade-{{ strtolower($hf->grade) }}">{{ $hf->grade }}</span>@else<span style="color:var(--txt3);">-</span>@endif</td>
                                <td>@if($hf->kategori)<span class="badge-custom {{ $hf->kategori_badge_color }}" style="font-size:10px;">{{ $hf->kategori_label }}</span>@else<span style="color:var(--txt3);">-</span>@endif</td>
                                <td><span class="badge-custom {{ $hf->jenis==='setoran_baru'?'badge-green':'badge-blue' }}" style="font-size:10px;">{{ $hf->jenis_label }}</span></td>
                                <td style="font-size:12px;">{{ $hf->tanggal_setoran->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-3" style="color:var(--txt3);">Belum ada data hafalan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pelanggaran --}}
        @if($santri->pelanggaran->count() > 0)
        <div class="card-custom">
            <div class="card-header-custom"><div class="card-title-custom">Pelanggaran Terbaru</div></div>
            <div class="table-responsive">
                <table class="table-custom">
                    <thead><tr><th>Jenis</th><th>Tingkat</th><th>Poin</th><th>Tanggal</th></tr></thead>
                    <tbody>
                        @foreach($santri->pelanggaran->take(4) as $p)
                            <tr>
                                <td style="font-size:12px;">{{ $p->jenis_pelanggaran }}</td>
                                <td>
                                    @php $t=$p->kategori?->tingkat; @endphp
                                    <span class="badge-custom {{ ['ringan'=>'badge-gold','sedang'=>'badge-purple','berat'=>'badge-red'][$t??'']??'badge-gray' }}" style="font-size:10px;">{{ ucfirst($t??'-') }}</span>
                                </td>
                                <td style="font-weight:700;color:var(--emas-dark);">{{ $p->poin_sanksi }}</td>
                                <td style="font-size:12px;">{{ $p->tanggal->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Pencapaian --}}
@if($santri->pencapaian->count() > 0)
<div class="card-custom">
    <div class="card-header-custom"><div class="card-title-custom">Pencapaian & Prestasi</div></div>
    <div class="card-body-custom">
        <div class="row g-2">
            @foreach($santri->pencapaian as $pr)
                <div class="col-md-4">
                    <div style="background:#f9fbf9;border:1px solid var(--border-light);border-radius:8px;padding:10px 14px;">
                        <div style="font-size:12px;font-weight:700;color:var(--txt);">{{ Str::limit($pr->judul_pencapaian,32) }}</div>
                        <div style="font-size:10px;color:var(--txt3);margin-top:3px;">{{ $pr->peringkat_label }} · {{ $pr->tingkat_label }} · {{ $pr->tanggal->format('d M Y') }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection
