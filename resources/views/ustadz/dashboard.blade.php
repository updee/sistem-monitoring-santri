{{-- resources/views/ustadz/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard Ustadz')
@section('page-title', 'Dashboard')
@section('breadcrumb', '/ <span>Dashboard Ustadz</span>')

@section('content')

{{-- Welcome Bar --}}
<div class="welcome-bar mb-4">
    <div>
        <div class="greeting">Assalamu'alaikum,</div>
        <div class="user-name">{{ auth()->user()->name }}</div>
        <div class="user-info">
            @php $kelas = auth()->user()->kelas->first(); @endphp
            @if($kelas)
                Halaqah {{ $kelas->nama_kelas }} &nbsp;·&nbsp; Tingkat {{ $kelas->tingkat }}
                &nbsp;·&nbsp; {{ $kelas->jumlah_santri }} Santri
            @else
                IBS Ash-Shiddiiqi Jambi
            @endif
        </div>
    </div>
    <div class="motto d-none d-md-block">
        "Sebaik-baik manusia adalah yang paling<br>bermanfaat bagi orang lain"
    </div>
</div>

{{-- Stat Cards --}}
<div class="mb-4" style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px;" class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-icon green">
            <svg viewBox="0 0 24 24" fill="none" stroke="#1a5c2e" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
        <div class="stat-card-value">{{ $totalSantri }}</div>
        <div class="stat-card-label">Santri di Halaqah Saya</div>
        <div class="stat-card-change change-up">{{ $kelas?->nama_kelas ?? 'Belum ada kelas' }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon gold">
            <svg viewBox="0 0 24 24" fill="none" stroke="#9a7a1a" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
        </div>
        <div class="stat-card-value">{{ $kehadiranHariIni['hadir'] ?? 0 }}</div>
        <div class="stat-card-label">Hadir Hari Ini</div>
        <div class="stat-card-change {{ ($kehadiranHariIni['alpha'] ?? 0) > 0 ? 'change-warn' : 'change-up' }}">
            {{ $kehadiranHariIni['alpha'] ?? 0 }} alpha · {{ $kehadiranHariIni['sakit'] ?? 0 }} sakit
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="#1a3c8e" stroke-width="2"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg>
        </div>
        <div class="stat-card-value">{{ $setoranBulanIni }}</div>
        <div class="stat-card-label">Setoran Bulan Ini</div>
        <div class="stat-card-change change-up">Total halaman: {{ $totalHalamanBulanIni }}</div>
    </div>
</div>

{{-- Row 2: Setoran Terbaru + Kehadiran --}}
<div class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="card-custom h-100">
            <div class="card-header-custom">
                <div class="card-title-custom">Setoran Hafalan Terbaru</div>
                <a href="{{ route('ustadz.hafalan.create') }}" class="btn-hijau" style="padding:6px 14px;font-size:12px;">
                    + Catat Baru
                </a>
            </div>
            <div class="card-body-custom p-0">
                @forelse($hafalanTerbaru as $hf)
                    <div class="d-flex align-items-center gap-3 px-4 py-3" style="border-bottom:1px solid #f4f6f4;">
                        <div class="td-avatar">{{ strtoupper(substr($hf->santri->nama, 0, 2)) }}</div>
                        <div class="flex-grow-1">
                            <div class="td-name-main">{{ Str::limit($hf->santri->nama, 24) }}</div>
                            <div class="td-name-sub">
                                {{ $hf->nama_surat }} · {{ $hf->jumlah_halaman }} hal ·
                                {{ $hf->tanggal_setoran->format('d M Y') }}
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge-custom {{ $hf->jenis === 'setoran_baru' ? 'badge-green' : 'badge-blue' }}" style="font-size:10px;">
                                {{ $hf->jenis_label }}
                            </span>
                            @if($hf->grade)
                                <span class="badge-custom grade-{{ strtolower($hf->grade) }}" style="width:28px;justify-content:center;">
                                    {{ $hf->grade }}
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5" style="color:var(--txt3);">
                        Belum ada setoran. <a href="{{ route('ustadz.hafalan.create') }}" style="color:var(--hijau);font-weight:600;">Catat sekarang →</a>
                    </div>
                @endforelse
            </div>
            @if($hafalanTerbaru->isNotEmpty())
                <div style="padding:10px 18px;border-top:1px solid var(--border-light);">
                    <a href="{{ route('ustadz.hafalan.index') }}" class="card-link">Lihat semua setoran →</a>
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card-custom h-100">
            <div class="card-header-custom">
                <div class="card-title-custom">Status Kehadiran Hari Ini</div>
                <a href="{{ route('ustadz.kehadiran.input') }}" class="btn-hijau" style="padding:6px 14px;font-size:12px;">
                    Input Absensi
                </a>
            </div>
            <div class="card-body-custom">
                @if(array_sum($kehadiranHariIni) > 0)
                    <div class="donut-wrapper justify-content-center mb-3">
                        <div style="position:relative;width:110px;height:110px;flex-shrink:0;">
                            <canvas id="chartDonutUstadz"></canvas>
                            <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                                <div style="font-size:20px;font-weight:800;color:var(--hijau);">
                                    {{ round((($kehadiranHariIni['hadir'] ?? 0) / max(array_sum($kehadiranHariIni), 1)) * 100) }}%
                                </div>
                                <div style="font-size:10px;color:var(--txt3);">hadir</div>
                            </div>
                        </div>
                        <div class="donut-legend">
                            @foreach(['hadir' => ['Hadir', '#1a5c2e'], 'izin' => ['Izin', '#c9a227'], 'sakit' => ['Sakit', '#90caf9'], 'alpha' => ['Alpha', '#ffcdd2']] as $key => [$label, $color])
                                <div class="donut-legend-item">
                                    <div class="donut-legend-dot" style="background:{{ $color }};"></div>
                                    {{ $label }}: {{ $kehadiranHariIni[$key] ?? 0 }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="text-center py-4" style="color:var(--txt3);font-size:13px;">
                        Absensi hari ini belum diinput
                    </div>
                @endif

                {{-- Santri Alpha hari ini --}}
                @if(!empty($santriAlphaHariIni) && count($santriAlphaHariIni) > 0)
                    <div style="background:#fce8e8;border-radius:8px;padding:10px 14px;">
                        <div style="font-size:11px;font-weight:700;color:#c62828;margin-bottom:6px;">
                            Santri Alpha Hari Ini
                        </div>
                        @foreach($santriAlphaHariIni as $alpha)
                            <div style="font-size:12px;color:#7a1a1a;padding:2px 0;">· {{ $alpha->santri->nama }}</div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Row 3: Progress Hafalan Santri --}}
<div class="card-custom">
    <div class="card-header-custom">
        <div class="card-title-custom">Progress Hafalan Santri Halaqah</div>
        <a href="{{ route('ustadz.hafalan.index') }}" class="card-link">Detail →</a>
    </div>
    <div class="card-body-custom">
        <div class="row g-3">
            @forelse($progressSantri as $s)
                <div class="col-md-6 col-lg-4">
                    <div style="background:#f9fbf9;border:1px solid var(--border-light);border-radius:8px;padding:10px 14px;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="td-avatar" style="width:28px;height:28px;font-size:10px;">
                                {{ strtoupper(substr($s->nama, 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-size:12px;font-weight:700;color:var(--txt);">{{ Str::limit($s->nama, 20) }}</div>
                                <div style="font-size:10px;color:var(--txt3);">{{ $s->hafalan_sum_jumlah_halaman ?? 0 }} halaman · rata {{ number_format($s->hafalan_avg_nilai ?? 0, 1) }}</div>
                            </div>
                        </div>
                        <div class="progress-hijau">
                            <div class="progress-fill" style="width:{{ min(100, (($s->hafalan_sum_jumlah_halaman ?? 0) / 604) * 100) }}%;"></div>
                        </div>
                        <div style="font-size:9px;color:var(--txt3);margin-top:3px;">
                            {{ number_format(min(100, (($s->hafalan_sum_jumlah_halaman ?? 0) / 604) * 100), 1) }}% dari 604 hal Al-Quran
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-3" style="color:var(--txt3);">Belum ada data hafalan</div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
@if(array_sum($kehadiranHariIni) > 0)
const khd = @json($kehadiranHariIni);
new Chart(document.getElementById('chartDonutUstadz'), {
    type: 'doughnut',
    data: {
        labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
        datasets: [{
            data: [khd.hadir??0, khd.izin??0, khd.sakit??0, khd.alpha??0],
            backgroundColor: ['#1a5c2e','#c9a227','#90caf9','#ffcdd2'],
            borderWidth: 0, hoverOffset: 4
        }]
    },
    options: { responsive:true, maintainAspectRatio:false, cutout:'72%', plugins:{ legend:{display:false} } }
});
@endif
</script>
@endpush
