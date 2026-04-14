{{-- ════════════════════════════════════════════════════════════
     layouts/partials/nav-admin.blade.php
     ════════════════════════════════════════════════════════════ --}}

<div class="sidebar-section">Menu Utama</div>
<a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
    Dashboard
</a>

<div class="sidebar-section">Master Data</div>
<a href="{{ route('admin.santri.index') }}" class="sidebar-item {{ request()->routeIs('admin.santri.*') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
    Data Santri
</a>
<a href="{{ route('admin.users.index') }}" class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
    Akun Pengguna
</a>
<a href="{{ route('admin.kelas.index') }}" class="sidebar-item {{ request()->routeIs('admin.kelas.*') || request()->routeIs('admin.kamar.*') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    Kelas & Kamar
</a>

<div class="sidebar-section">Monitoring</div>
<a href="{{ route('admin.laporan.hafalan') }}" class="sidebar-item {{ request()->routeIs('admin.laporan.hafalan') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg>
    Hafalan Al-Quran
</a>
<a href="{{ route('admin.laporan.kehadiran') }}" class="sidebar-item {{ request()->routeIs('admin.laporan.kehadiran') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
    Kehadiran
</a>
<a href="{{ route('admin.laporan.pelanggaran') }}" class="sidebar-item {{ request()->routeIs('admin.laporan.pelanggaran') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    Pelanggaran
</a>
<a href="{{ route('admin.laporan.pencapaian') }}" class="sidebar-item {{ request()->routeIs('admin.laporan.pencapaian') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
    Pencapaian
</a>
<a href="{{ route('admin.izin.index') }}" class="sidebar-item {{ request()->routeIs('admin.izin.*') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    Pengajuan Izin
    @php $pending = \App\Models\Izin::menunggu()->count(); @endphp
    @if($pending > 0)
        <span class="sidebar-badge">{{ $pending }}</span>
    @endif
</a>

<div class="sidebar-section">Laporan</div>
<a href="{{ route('admin.laporan.index') }}" class="sidebar-item {{ request()->routeIs('admin.laporan.index') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
    Ekspor Data
</a>
