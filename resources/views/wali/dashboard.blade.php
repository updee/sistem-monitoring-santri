{{-- resources/views/wali/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard Wali Santri')
@section('page-title', 'Dashboard')
@section('breadcrumb', '/ <span>Dashboard Wali Santri</span>')

@section('content')

@if(!$santri)
    <div class="alert-emas mb-4">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div>
            Akun Anda belum dihubungkan dengan data santri. Silakan hubungi Admin pesantren untuk proses verifikasi.
        </div>
    </div>
@else

    @if($santri->active_sp)
        <div class="alert-emas mb-4" style="background:#fff3f3;border-left:4px solid #c62828;color:#c62828;">
            <div class="d-flex align-items-center gap-3">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                <div>
                    <strong style="display:block;font-size:14px;margin-bottom:2px;">PERHATIAN: STATUS {{ strtoupper($santri->active_sp->jenis_sp) }} AKTIF</strong>
                    <span style="font-size:13px;">Anak Anda sedang dalam masa peringatan aktif karena akumulasi poin pelanggaran mencapai {{ $santri->active_sp->total_poin }} poin. Harap hubungi pihak asrama untuk konfirmasi.</span>
                </div>
            </div>
        </div>
    @endif

    {{-- Profil Santri --}}
    <div class="welcome-bar mb-4">
        <div class="d-flex align-items-center gap-4">
            <div style="width:60px;height:60px;border-radius:50%;background:var(--emas);display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:800;color:var(--hijau);flex-shrink:0;">
                {{ strtoupper(substr($santri->nama, 0, 2)) }}
            </div>
            <div>
                <div class="greeting">Profil Santri Anda</div>
                <div class="user-name">{{ $santri->nama }}</div>
                <div class="user-info">
                    NIS: {{ $santri->nis }} &nbsp;·&nbsp;
                    {{ $santri->kelas->nama_kelas ?? '-' }} &nbsp;·&nbsp;
                    {{ $santri->kamar->nama_kamar ?? '-' }}
                </div>
            </div>
        </div>
        <div class="d-flex flex-column align-items-end gap-2">
            <span class="badge-custom badge-green" style="font-size:12px;">{{ $santri->status_label }}</span>
            <div class="motto">Masuk: {{ $santri->tanggal_masuk?->format('d M Y') ?? '-' }}</div>
        </div>
    </div>

    {{-- Stat Cards Monitoring --}}
    <div class="mb-4" style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;">
        <div class="stat-card">
            <div class="stat-card-icon gold">
                <svg viewBox="0 0 24 24" fill="none" stroke="#9a7a1a" stroke-width="2"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg>
            </div>
            <div class="stat-card-value">{{ number_format($persenProgressHafalan, 1) }}<span style="font-size:14px;font-weight:400;color:var(--txt3);">%</span></div>
            <div class="stat-card-label">Progress hafalan (target khatam)</div>
            <div class="progress-hijau mt-2"><div class="progress-fill" style="width:{{ min(100, $persenProgressHafalan) }}%;"></div></div>
            <div class="stat-card-change {{ $persenProgressHafalan >= 100 ? 'change-up' : 'change-warn' }}">
                {{ $totalHalaman }} / {{ $targetHalamanHafalan }} hal. tercatat · {{ $setoranBulanIni }} entri bulan ini
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon green">
                <svg viewBox="0 0 24 24" fill="none" stroke="#1a5c2e" stroke-width="2"><path d="M9 11l3 3L22 4"/></svg>
            </div>
            <div class="stat-card-value">{{ $persenKehadiran }}<span style="font-size:14px;font-weight:400;color:var(--txt3);">%</span></div>
            <div class="stat-card-label">Kehadiran Bulan Ini</div>
            <div class="stat-card-change {{ $persenKehadiran >= 85 ? 'change-up' : 'change-warn' }}">
                {{ $persenKehadiran >= 85 ? 'Bagus!' : 'Perlu ditingkatkan' }}
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon red">
                <svg viewBox="0 0 24 24" fill="none" stroke="#c62828" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            </div>
            <div class="stat-card-value">{{ $totalPoin }}</div>
            <div class="stat-card-label">Akumulasi Poin Pelanggaran</div>
            <div class="stat-card-change {{ $totalPoin >= 75 ? 'change-down' : ($totalPoin > 0 ? 'change-warn' : 'change-up') }}">
                {{ $totalPoin >= 75 ? 'Kritis!' : ($totalPoin > 0 ? 'Perlu perhatian' : 'Baik') }}
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="#1a3c8e" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
            <div class="stat-card-value">{{ $totalPrestasi }}</div>
            <div class="stat-card-label">Total Pencapaian</div>
            <div class="stat-card-change change-up">Akademik & non-akademik</div>
        </div>
    </div>

    {{-- Row 2: Hafalan Terbaru + Form Izin --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card-custom h-100">
                <div class="card-header-custom">
                    <div class="card-title-custom">Setoran Hafalan Terbaru</div>
                    <a href="{{ route('wali_santri.hafalan') }}" class="card-link">Lihat semua →</a>
                </div>
                <div class="card-body-custom p-0">
                    @forelse($hafalanTerbaru as $hf)
                        <div class="d-flex align-items-center gap-3 px-4 py-3" style="border-bottom:1px solid #f4f6f4;">
                            <div>
                                <div class="td-name-main">{{ $hf->nama_surat }}</div>
                                <div class="td-name-sub">Juz {{ $hf->nomor_juz }} · {{ $hf->jumlah_halaman }} halaman</div>
                            </div>
                            <div class="ms-auto d-flex align-items-center gap-2">
                                <span style="font-size:11px;color:var(--txt3);">{{ $hf->tanggal_setoran->format('d M') }}</span>
                                @if($hf->grade)
                                    <span class="badge-custom grade-{{ strtolower($hf->grade) }}">{{ $hf->grade }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5" style="color:var(--txt3);">Belum ada data setoran</div>
                    @endforelse
                </div>
                <div style="padding:10px 18px;border-top:1px solid var(--border-light);">
                    <div class="progress-hijau" style="margin-bottom:6px;">
                        <div class="progress-fill" style="width:{{ min(100, $persenProgressHafalan) }}%;"></div>
                    </div>
                    <div style="font-size:11px;color:var(--txt3);">
                        Progress khatam: <strong style="color:var(--hijau);">{{ number_format($persenProgressHafalan, 1) }}%</strong>
                        ({{ $totalHalaman }} / {{ $targetHalamanHafalan }} hal. setoran & muroja'ah)
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card-custom h-100">
                <div class="card-header-custom">
                    <div class="card-title-custom">Ajukan Izin Pulang</div>
                    <a href="{{ route('wali_santri.izin.index') }}" class="card-link">Riwayat →</a>
                </div>
                <div class="card-body-custom">
                    @if($izinAktif)
                        <div class="alert-emas mb-3">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <div style="font-size:12px;">Santri sedang dalam izin yang aktif (s/d {{ $izinAktif->tanggal_kembali->format('d M Y') }})</div>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('wali_santri.izin.store') }}">
                        @csrf
                        <div class="form-group-custom">
                            <label class="form-label-custom">Tanggal Mulai <span style="color:#e53935;">*</span></label>
                            <input type="date" name="tanggal_mulai" class="form-control-custom" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group-custom">
                            <label class="form-label-custom">Tanggal Kembali <span style="color:#e53935;">*</span></label>
                            <input type="date" name="tanggal_kembali" class="form-control-custom" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group-custom">
                            <label class="form-label-custom">Nama Penjemput</label>
                            <input type="text" name="nama_penjemput" class="form-control-custom" value="{{ auth()->user()->name }}" placeholder="Nama wali/penjemput">
                        </div>
                        <div class="form-group-custom">
                            <label class="form-label-custom">Alasan Izin <span style="color:#e53935;">*</span></label>
                            <textarea name="alasan" class="form-control-custom" rows="3" placeholder="Jelaskan alasan pengajuan izin..." required></textarea>
                        </div>
                        <button type="submit" class="btn-hijau w-100 justify-content-center">
                            Kirim Pengajuan Izin
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 3: Status Izin + Pencapaian + Pelanggaran --}}
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card-custom">
                <div class="card-header-custom">
                    <div class="card-title-custom">Status Izin Terakhir</div>
                    <a href="{{ route('wali_santri.izin.index') }}" class="card-link">Semua →</a>
                </div>
                <div class="card-body-custom p-0">
                    @forelse($izinTerakhir as $iz)
                        <div class="d-flex align-items-center gap-3 px-4 py-3" style="border-bottom:1px solid #f4f6f4;">
                            <div class="flex-grow-1">
                                <div class="td-name-main">{{ $iz->tanggal_mulai->format('d M') }} — {{ $iz->tanggal_kembali->format('d M Y') }}</div>
                                <div class="td-name-sub">{{ Str::limit($iz->alasan, 40) }}</div>
                            </div>
                            <span class="badge-custom badge-{{ $iz->status === 'menunggu' ? 'gold' : ($iz->status === 'disetujui' ? 'green' : 'red') }}">
                                {{ $iz->status_label }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-4" style="color:var(--txt3);font-size:13px;">Belum ada riwayat izin</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-custom">
                <div class="card-header-custom">
                    <div class="card-title-custom">Pencapaian Terbaru</div>
                    <a href="{{ route('wali_santri.pencapaian') }}" class="card-link">Semua →</a>
                </div>
                <div class="card-body-custom p-0">
                    @forelse($pencapaianTerbaru as $pr)
                        <div class="d-flex align-items-center gap-3 px-4 py-3" style="border-bottom:1px solid #f4f6f4;">
                            @php $medal = match($pr->peringkat) { 'juara_1'=>['🥇','#f5e9c0','#9a7a1a'], 'juara_2'=>['🥈','#f0f0f0','#5a5a6a'], 'juara_3'=>['🥉','#f5e5d8','#8a4c20'], default=>['★','var(--hijau-pale)','var(--hijau)'] }; @endphp
                            <div style="width:32px;height:32px;border-radius:50%;background:{{ $medal[1] }};display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;">{{ $medal[0] }}</div>
                            <div class="flex-grow-1">
                                <div class="td-name-main">{{ Str::limit($pr->judul_pencapaian, 28) }}</div>
                                <div class="td-name-sub">{{ $pr->tingkat_label }} · {{ $pr->tanggal->format('d M Y') }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4" style="color:var(--txt3);font-size:13px;">Belum ada pencapaian</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-custom">
                <div class="card-header-custom">
                    <div class="card-title-custom">Pelanggaran Terbaru</div>
                    <a href="{{ route('wali_santri.pelanggaran') }}" class="card-link">Semua →</a>
                </div>
                <div class="card-body-custom p-0">
                    @forelse($pelanggaranTerbaru as $pl)
                        @php $tingkat = $pl->kategori?->tingkat; @endphp
                        <div class="d-flex align-items-center gap-3 px-4 py-3" style="border-bottom:1px solid #f4f6f4;">
                            <div class="flex-grow-1">
                                <div class="td-name-main">{{ Str::limit($pl->jenis_pelanggaran, 28) }}</div>
                                <div class="td-name-sub">{{ $pl->tanggal->format('d M Y') }} · {{ $pl->poin_sanksi }} poin</div>
                            </div>
                            <span class="badge-custom {{ ['ringan'=>'badge-gold','sedang'=>'badge-purple','berat'=>'badge-red'][$tingkat ?? ''] ?? 'badge-gray' }}">
                                {{ ucfirst($tingkat ?? '-') }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-4" style="color:var(--txt3);font-size:13px;">Tidak ada pelanggaran terbaru</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endif
@endsection
