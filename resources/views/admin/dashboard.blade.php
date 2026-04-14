{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('breadcrumb', '/ <span>Dashboard Admin</span>')

@section('content')

{{-- Welcome Bar --}}
<div class="welcome-bar">
    <div>
        <div class="wb-hi">Assalamu'alaikum,</div>
        <div class="wb-name">{{ auth()->user()->name }}</div>
        <div class="wb-info">
            IBS Ash-Shiddiiqi Jambi &nbsp;·&nbsp; Tahun Ajaran 2025/2026
        </div>
    </div>
    <div class="wb-motto d-none d-md-block">
        "Terdepan dalam Prestasi dan Inovasi<br>dalam bingkai adab islami"
    </div>
</div>

{{-- Stat Cards --}}
<div class="stats-row mb-4" style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;">

    <div class="stat-c">
        <div class="stat-icon green">
            <svg viewBox="0 0 24 24" fill="none" stroke="#1a5c2e" stroke-width="2">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
            </svg>
        </div>
        <div class="stat-val">{{ $stats['total_santri'] }}</div>
        <div class="stat-lbl">Total Santri Aktif</div>
        <div class="stat-chg chg-up">{{ $stats['total_ustadz'] }} ustadz terdaftar</div>
    </div>

    <div class="stat-c">
        <div class="stat-icon gold">
            <svg viewBox="0 0 24 24" fill="none" stroke="#9a7a1a" stroke-width="2">
                <path d="M9 11l3 3L22 4"/>
                <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
            </svg>
        </div>
        <div class="stat-val">{{ $kehadiranHariIni['hadir'] ?? 0 }} <span style="font-size:13px;font-weight:400;color:var(--txt3);">hadir</span></div>
        <div class="stat-lbl">Kehadiran Hari Ini</div>
        <div class="stat-chg chg-up">{{ round((($kehadiranHariIni['hadir'] ?? 0) / max(array_sum($kehadiranHariIni ?: [1]), 1)) * 100) }}% kehadiran</div>
    </div>

    <div class="stat-c">
        <div class="stat-icon blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="#1a3c8e" stroke-width="2">
                <path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/>
                <path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/>
            </svg>
        </div>
        <div class="stat-val">{{ $pelanggaranBulanIni }}</div>
        <div class="stat-lbl">Pelanggaran Bulan Ini</div>
        <div class="stat-chg chg-warn">Perlu dipantau</div>
    </div>

    <div class="stat-c">
        <div class="stat-icon red">
            <svg viewBox="0 0 24 24" fill="none" stroke="#c62828" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
        </div>
        <div class="stat-val">{{ $stats['izin_menunggu'] }}</div>
        <div class="stat-lbl">Izin Menunggu</div>
        @if($stats['izin_menunggu'] > 0)
            <div class="stat-chg chg-down">
                <a href="{{ route('admin.izin.index') }}" style="color:inherit;text-decoration:underline;">Tinjau sekarang</a>
            </div>
        @else
            <div class="stat-chg chg-up">Semua selesai</div>
        @endif
    </div>

</div>

{{-- Row 2: Grafik + Donut --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card-c h-100">
            <div class="card-c-hdr">
                <div class="card-c-title">Grafik Kehadiran 7 Hari Terakhir</div>
                <a href="{{ route('admin.laporan.kehadiran') }}" class="card-c-link">Rekap lengkap →</a>
            </div>
            <div class="card-c-body">
                <div class="d-flex gap-3 mb-3">
                    @foreach(['#1a5c2e'=>'Hadir','#ffcdd2'=>'Alpha','#c9a227'=>'Izin/Sakit'] as $clr=>$lbl)
                        <div class="d-flex align-items-center gap-2" style="font-size:12px;color:var(--txt2);">
                            <div style="width:9px;height:9px;background:{{ $clr }};border-radius:2px;"></div>{{ $lbl }}
                        </div>
                    @endforeach
                </div>
                <div style="position:relative;height:190px;">
                    <canvas id="chartKehadiran"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-c h-100">
            <div class="card-c-hdr">
                <div class="card-c-title">Status Kehadiran Hari Ini</div>
                <a href="{{ route('admin.laporan.kehadiran') }}" class="card-c-link">Detail →</a>
            </div>
            <div class="card-c-body d-flex flex-column align-items-center">
                <div style="position:relative;width:120px;height:120px;margin-bottom:14px;">
                    <canvas id="chartDonut"></canvas>
                    <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                        <div style="font-size:22px;font-weight:800;color:var(--hijau);">
                            {{ round((($kehadiranHariIni['hadir'] ?? 0) / max(array_sum($kehadiranHariIni ?: [1]), 1)) * 100) }}%
                        </div>
                        <div style="font-size:10px;color:var(--txt3);">hadir</div>
                    </div>
                </div>
                <div class="donut-lgd w-100">
                    @foreach(['hadir'=>['Hadir','#1a5c2e'],'izin'=>['Izin','#c9a227'],'sakit'=>['Sakit','#90caf9'],'alpha'=>['Alpha','#ffcdd2']] as $k=>[$lbl,$clr])
                        <div class="donut-lgd-i">
                            <div class="donut-dot" style="background:{{ $clr }};"></div>
                            {{ $lbl }}: {{ $kehadiranHariIni[$k] ?? 0 }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Row 3: Izin + Hafalan + Pelanggaran --}}
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card-c h-100">
            <div class="card-c-hdr">
                <div class="card-c-title">Izin Menunggu Persetujuan</div>
                <a href="{{ route('admin.izin.index') }}" class="card-c-link">Kelola →</a>
            </div>
            <div style="padding:0;">
                @forelse($izinMenunggu as $izin)
                    <div class="d-flex align-items-center gap-3 px-3 py-2" style="border-bottom:1px solid #f4f6f4;">
                        <div class="td-av">{{ strtoupper(substr($izin->santri->nama, 0, 2)) }}</div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="td-name" style="font-size:12px;font-weight:600;">{{ Str::limit($izin->santri->nama, 22) }}</div>
                            <div class="td-sub">{{ $izin->tanggal_mulai->format('d M') }} – {{ $izin->tanggal_kembali->format('d M Y') }}</div>
                        </div>
                        <span class="bdg bdg-gold" style="font-size:10px;white-space:nowrap;">Menunggu</span>
                    </div>
                @empty
                    <div class="text-center py-4" style="color:var(--txt3);font-size:13px;">Tidak ada izin menunggu</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-c h-100">
            <div class="card-c-hdr">
                <div class="card-c-title">Setoran Hafalan Terbaru</div>
                <a href="{{ route('admin.laporan.hafalan') }}" class="card-c-link">Semua →</a>
            </div>
            <div style="padding:0;">
                @forelse($hafalanTerbaru as $hf)
                    <div class="d-flex align-items-center gap-3 px-3 py-2" style="border-bottom:1px solid #f4f6f4;">
                        <div class="td-av">{{ strtoupper(substr($hf->santri->nama, 0, 2)) }}</div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="td-name" style="font-size:12px;font-weight:600;">{{ Str::limit($hf->santri->nama, 20) }}</div>
                            <div class="td-sub">{{ $hf->nama_surat }} · {{ $hf->jumlah_halaman }} hal · {{ $hf->tanggal_setoran->format('d M') }}</div>
                        </div>
                        @if($hf->grade)
                            <span class="bdg grade-{{ strtolower($hf->grade) }}" style="font-size:11px;min-width:26px;justify-content:center;">{{ $hf->grade }}</span>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-4" style="color:var(--txt3);font-size:13px;">Belum ada data hafalan</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-c h-100">
            <div class="card-c-hdr">
                <div class="card-c-title">Top Poin Pelanggaran</div>
                <a href="{{ route('admin.laporan.pelanggaran') }}" class="card-c-link">Semua →</a>
            </div>
            <div class="card-c-body">
                @forelse($santriRawan as $i => $sr)
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:20px;height:20px;border-radius:5px;background:var(--hijau-pale);display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:800;color:var(--hijau);">{{ $i+1 }}</div>
                                <span style="font-size:12px;font-weight:600;">{{ Str::limit($sr->nama, 20) }}</span>
                            </div>
                            <span style="font-size:12px;font-weight:700;color:var(--emas-dark);">{{ $sr->pelanggaran_sum_poin_sanksi ?? 0 }} poin</span>
                        </div>
                        <div class="prog-bar">
                            <div class="prog-fill" style="width:{{ min(100, ($sr->pelanggaran_sum_poin_sanksi ?? 0)) }}%;"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-3" style="color:var(--txt3);font-size:13px;">Tidak ada pelanggaran</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Bar Chart Kehadiran
const grafik = @json($grafikKehadiran);
const labels  = grafik.map(d => d.label);
const hadir   = grafik.map(d => d.hadir);
const totalArr= grafik.map(d => d.total);
const alpha   = totalArr.map((t,i) => Math.max(0, t - hadir[i] - Math.round(t * .05)));
const izinSkt = totalArr.map((t,i) => Math.max(0, t - hadir[i] - alpha[i]));

new Chart(document.getElementById('chartKehadiran'), {
    type: 'bar',
    data: { labels, datasets: [
        { label:'Hadir',      data: hadir,   backgroundColor:'#1a5c2e', borderRadius:3, borderSkipped:false },
        { label:'Alpha',      data: alpha,   backgroundColor:'#ffcdd2', borderRadius:3, borderSkipped:false },
        { label:'Izin/Sakit', data: izinSkt, backgroundColor:'#c9a227', borderRadius:3, borderSkipped:false },
    ]},
    options: {
        responsive:true, maintainAspectRatio:false,
        plugins:{ legend:{display:false} },
        scales:{
            x:{ grid:{display:false}, ticks:{font:{size:11}} },
            y:{ grid:{color:'#f0f0f0'}, ticks:{font:{size:11}}, beginAtZero:true }
        }
    }
});

// Donut Chart
const khd = @json($kehadiranHariIni);
new Chart(document.getElementById('chartDonut'), {
    type:'doughnut',
    data:{ labels:['Hadir','Izin','Sakit','Alpha'],
        datasets:[{ data:[khd.hadir??0,khd.izin??0,khd.sakit??0,khd.alpha??0],
            backgroundColor:['#1a5c2e','#c9a227','#90caf9','#ffcdd2'],
            borderWidth:0, hoverOffset:4 }]
    },
    options:{ responsive:true, maintainAspectRatio:false, cutout:'72%', plugins:{legend:{display:false}} }
});
</script>
@endpush
