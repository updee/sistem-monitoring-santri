{{-- ════════════════════════════════════════════════════════════
     layouts/partials/nav-ustadz.blade.php
     ════════════════════════════════════════════════════════════ --}}

<div class="sidebar-section">Menu Utama</div>
<a href="{{ route('ustadz.dashboard') }}" class="sidebar-item {{ request()->routeIs('ustadz.dashboard') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
    Dashboard
</a>

<div class="sidebar-section">Input Data</div>
<a href="{{ route('ustadz.hafalan.index') }}" class="sidebar-item {{ request()->routeIs('ustadz.hafalan.*') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg>
    Catat Hafalan
</a>
<a href="{{ route('ustadz.kehadiran.input') }}" class="sidebar-item {{ request()->routeIs('ustadz.kehadiran.*') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
    Input Kehadiran
</a>
<a href="{{ route('ustadz.pelanggaran.index') }}" class="sidebar-item {{ request()->routeIs('ustadz.pelanggaran.*') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    Catat Pelanggaran
</a>
<a href="{{ route('ustadz.pencapaian.index') }}" class="sidebar-item {{ request()->routeIs('ustadz.pencapaian.*') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
    Catat Pencapaian
</a>

<div class="sidebar-section">Info</div>
<a href="{{ route('ustadz.izin.index') }}" class="sidebar-item {{ request()->routeIs('ustadz.izin.*') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    Daftar Izin Santri
    @php $izin = \App\Models\Izin::where('status','disetujui')->whereDate('tanggal_mulai','<=',today())->whereDate('tanggal_kembali','>=',today())->count(); @endphp
    @if($izin > 0)
        <span class="sidebar-badge">{{ $izin }}</span>
    @endif
</a>
