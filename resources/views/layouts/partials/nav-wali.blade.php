{{-- layouts/partials/nav-wali.blade.php --}}

<div class="sidebar-section">Menu Utama</div>
<a href="{{ route('wali_santri.dashboard') }}" class="sidebar-item {{ request()->routeIs('wali_santri.dashboard') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
    Dashboard
</a>

<div class="sidebar-section">Monitoring Anak</div>
<a href="{{ route('wali_santri.hafalan') }}" class="sidebar-item {{ request()->routeIs('wali_santri.hafalan') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg>
    Progress Hafalan
</a>
<a href="{{ route('wali_santri.kehadiran') }}" class="sidebar-item {{ request()->routeIs('wali_santri.kehadiran') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
    Kehadiran
</a>
<a href="{{ route('wali_santri.pelanggaran') }}" class="sidebar-item {{ request()->routeIs('wali_santri.pelanggaran') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    Catatan Pelanggaran
</a>
<a href="{{ route('wali_santri.pencapaian') }}" class="sidebar-item {{ request()->routeIs('wali_santri.pencapaian') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
    Pencapaian
</a>

<div class="sidebar-section">Perizinan</div>
<a href="{{ route('wali_santri.izin.create') }}" class="sidebar-item {{ request()->routeIs('wali_santri.izin.create') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
    Ajukan Izin
</a>
<a href="{{ route('wali_santri.izin.index') }}" class="sidebar-item {{ request()->routeIs('wali_santri.izin.index') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    Riwayat Izin
</a>
