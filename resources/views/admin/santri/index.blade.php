{{-- resources/views/admin/santri/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Data Santri')
@section('page-title', 'Data Santri')
@section('breadcrumb', '/ <span>Data Santri</span>')

@push('styles')
<style>
@media (max-width: 767.98px) {
  .santri-table-wrap { display: none; }
  .santri-mobile-list { display: grid; gap: 10px; padding: 12px; }
  .santri-mobile-card { border: 1px solid var(--border-light); border-radius: 10px; background: #fff; padding: 10px; }
}
@media (min-width: 768px) { .santri-mobile-list { display: none; } }
</style>
@endpush

@section('content')

<div class="page-header">
    <div>
        <div class="page-header-title">Data Santri</div>
        <div class="page-header-sub">Total {{ $santri->total() }} santri terdaftar dalam sistem</div>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn-outline-hijau" data-bs-toggle="modal" data-bs-target="#importModal">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
            Import Excel
        </button>
        <a href="{{ route('admin.santri.index') }}?export=excel" class="btn-outline-hijau">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Export Excel
        </a>
        <a href="{{ route('admin.santri.create') }}" class="btn-hijau">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Santri
        </a>
    </div>
</div>

{{-- Filter & Search --}}
<div class="card-custom mb-4">
    <div class="card-body-custom">
        <form method="GET" action="{{ route('admin.santri.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label-custom">Cari Santri</label>
                    <input type="text" name="search" class="form-control-custom"
                        placeholder="Nama atau NIS..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label-custom">Filter Kelas</label>
                    <select name="kelas_id" class="form-control-custom">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label-custom">Status</label>
                    <select name="status" class="form-control-custom">
                        <option value="">Semua Status</option>
                        <option value="aktif"  {{ request('status') === 'aktif'  ? 'selected' : '' }}>Aktif</option>
                        <option value="alumni" {{ request('status') === 'alumni' ? 'selected' : '' }}>Alumni</option>
                        <option value="keluar" {{ request('status') === 'keluar' ? 'selected' : '' }}>Keluar</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn-hijau flex-grow-1 justify-content-center">Cari</button>
                    <a href="{{ route('admin.santri.index') }}" class="btn-outline-hijau">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card-custom">
    <div class="table-responsive santri-table-wrap">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Santri</th>
                    <th>Kelas / Halaqah</th>
                    <th>Kamar</th>
                    <th>Wali Santri</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($santri as $index => $s)
                    <tr>
                        <td style="color:var(--txt3);font-size:12px;">{{ $santri->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="td-avatar">{{ strtoupper(substr($s->nama, 0, 2)) }}</div>
                                <div>
                                    <div class="td-name-main">{{ $s->nama }}</div>
                                    <div class="td-name-sub">NIS: {{ $s->nis }} · {{ $s->jenis_kelamin_label }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="td-name-main">{{ $s->kelas->nama_kelas ?? '-' }}</div>
                            <div class="td-name-sub">Tingkat {{ $s->kelas->tingkat ?? '-' }}</div>
                        </td>
                        <td>{{ $s->kamar->nama_kamar ?? '-' }}</td>
                        <td>
                            <div class="td-name-main">{{ $s->wali->name ?? '-' }}</div>
                            <div class="td-name-sub">{{ $s->wali->no_telepon ?? '-' }}</div>
                        </td>
                        <td>
                            <span class="badge-custom {{ $s->status === 'aktif' ? 'badge-green' : ($s->status === 'alumni' ? 'badge-blue' : 'badge-gray') }}">
                                {{ $s->status_label }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.santri.rekap', $s) }}" class="btn-view-custom">Detail</a>
                                <a href="{{ route('admin.santri.edit', $s) }}" class="btn-edit-custom">Edit</a>
                                <form method="POST" action="{{ route('admin.santri.destroy', $s) }}"
                                    onsubmit="return confirm('Hapus data santri {{ $s->nama }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger-custom">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5" style="color:var(--txt3);">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="opacity:0.3;display:block;margin:0 auto 8px;"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            Tidak ada data santri ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="santri-mobile-list">
        @forelse($santri as $s)
            <div class="santri-mobile-card">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="td-avatar">{{ strtoupper(substr($s->nama, 0, 2)) }}</div>
                    <div>
                        <div class="td-name-main">{{ $s->nama }}</div>
                        <div class="td-name-sub">NIS: {{ $s->nis }}</div>
                    </div>
                    <span class="badge-custom {{ $s->status === 'aktif' ? 'badge-green' : ($s->status === 'alumni' ? 'badge-blue' : 'badge-gray') }} ms-auto">{{ $s->status_label }}</span>
                </div>
                <div class="td-name-sub mb-2">Kelas: {{ $s->kelas->nama_kelas ?? '-' }} · Kamar: {{ $s->kamar->nama_kamar ?? '-' }}</div>
                <div class="td-name-sub mb-2">Wali: {{ $s->wali->name ?? '-' }}</div>
                <div class="d-flex gap-1">
                    <a href="{{ route('admin.santri.rekap', $s) }}" class="btn-view-custom">Detail</a>
                    <a href="{{ route('admin.santri.edit', $s) }}" class="btn-edit-custom">Edit</a>
                    <form method="POST" action="{{ route('admin.santri.destroy', $s) }}" onsubmit="return confirm('Hapus data santri {{ $s->nama }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger-custom">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-3" style="color:var(--txt3);">Tidak ada data santri ditemukan</div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="pagination-custom">
        <div class="pagination-info">
            Menampilkan {{ $santri->firstItem() }}–{{ $santri->lastItem() }} dari {{ $santri->total() }} santri
        </div>
        <div class="d-flex gap-1">
            @if($santri->onFirstPage())
                <span class="page-link" style="opacity:0.4;cursor:not-allowed;">‹</span>
            @else
                <a href="{{ $santri->previousPageUrl() }}" class="page-link">‹</a>
            @endif

            @foreach($santri->getUrlRange(max(1, $santri->currentPage()-2), min($santri->lastPage(), $santri->currentPage()+2)) as $page => $url)
                <a href="{{ $url }}" class="page-link {{ $page === $santri->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach

            @if($santri->hasMorePages())
                <a href="{{ $santri->nextPageUrl() }}" class="page-link">›</a>
            @else
                <span class="page-link" style="opacity:0.4;cursor:not-allowed;">›</span>
            @endif
        </div>
    </div>
</div>

{{-- Modal Import Excel --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.santri.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel" style="font-size: 16px; font-weight: 700; color: var(--hijau);">Import Data Santri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="font-size: 13px;">
                    <p class="mb-3">Silakan gunakan template Excel di bawah ini untuk memastikan format data sesuai dengan sistem.</p>
                    <a href="{{ route('admin.santri.import.template') }}" class="btn-outline-hijau mb-4">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Download Template CSV
                    </a>
                    
                    <div class="form-group-custom">
                        <label class="form-label-custom">Pilih File Excel / CSV</label>
                        <input type="file" name="file" class="form-control-custom" accept=".xlsx, .xls, .csv" required style="padding-top: 6px;">
                        <div class="form-error">Maksimal ukuran file: 2MB. Format: .xlsx, .xls, .csv</div>
                    </div>

                    <div class="alert-emas mt-3">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        <div>Data relasi seperti <b>Kelas</b>, <b>Kamar</b>, dan <b>Wali</b> akan dikosongkan. Admin dapat melengkapinya nanti setelah import selesai.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-outline-hijau" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-hijau">Import Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
