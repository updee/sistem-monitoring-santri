{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.app')
@section('title','Kelola Pengguna')
@section('page-title','Kelola Pengguna')
@section('breadcrumb','/ <span>Akun Pengguna</span>')

@push('styles')
<style>
@media (max-width: 767.98px) {
  .users-table-wrap { display: none; }
  .users-mobile-list { display: grid; gap: 10px; padding: 12px; }
  .users-mobile-card { border: 1px solid var(--border-light); border-radius: 10px; background: #fff; padding: 10px; }
}
@media (min-width: 768px) { .users-mobile-list { display: none; } }
</style>
@endpush

@section('content')
<div class="page-header">
    <div><div class="page-header-title">Akun Pengguna</div><div class="page-header-sub">Kelola akun admin, ustadz, dan wali santri</div></div>
    <a href="{{ route('admin.users.create') }}" class="btn-hijau">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:16px;height:16px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Pengguna
    </a>
</div>

<div class="card-custom mb-4">
    <div class="card-body-custom">
        <form method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-5"><label class="form-label-custom">Cari</label><input type="text" name="search" class="form-control-custom" placeholder="Nama atau email..." value="{{ request('search') }}"></div>
                <div class="col-md-3"><label class="form-label-custom">Role</label>
                    <select name="role" class="form-control-custom">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role')==='admin'?'selected':'' }}>Admin</option>
                        <option value="ustadz" {{ request('role')==='ustadz'?'selected':'' }}>Ustadz</option>
                        <option value="wali_santri" {{ request('role')==='wali_santri'?'selected':'' }}>Wali Santri</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn-hijau flex-grow-1 justify-content-center">Cari</button>
                    <a href="{{ route('admin.users.index') }}" class="btn-outline-hijau">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card-custom">
    <div class="table-responsive users-table-wrap">
        <table class="table-custom">
            <thead><tr><th>No</th><th>Pengguna</th><th>Role</th><th>No. Telepon</th><th>Terhubung Dengan</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($users as $idx => $u)
                    <tr>
                        <td style="color:var(--txt3);font-size:12px;">{{ $users->firstItem()+$idx }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="td-avatar" style="{{ $u->role==='admin'?'background:#fce8e8;color:#c62828;':($u->role==='ustadz'?'':'') }}">
                                    {{ strtoupper(substr($u->name,0,2)) }}
                                </div>
                                <div>
                                    <div class="td-name-main">{{ $u->name }}</div>
                                    <div class="td-name-sub">{{ $u->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-custom {{ ['admin'=>'badge-red','ustadz'=>'badge-blue','wali_santri'=>'badge-green'][$u->role]??'badge-gray' }}">
                                {{ $u->role_label }}
                            </span>
                        </td>
                        <td style="font-size:12px;">{{ $u->no_telepon??'-' }}</td>
                        <td style="font-size:12px;">
                            @if($u->isWaliSantri())
                                {{ $u->santriWali->first()?->nama ?? '<span style="color:var(--txt3);">Belum dihubungkan</span>' }}
                            @elseif($u->isUstadz())
                                {{ $u->kelas->first()?->nama_kelas ?? '<span style="color:var(--txt3);">Belum ada kelas</span>' }}
                            @else
                                <span style="color:var(--txt3);">-</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.users.toggle-active',$u) }}" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="badge-custom {{ $u->is_active?'badge-green':'badge-red' }}" style="border:none;cursor:pointer;">
                                    {{ $u->is_active?'Aktif':'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.users.edit',$u) }}" class="btn-edit-custom">Edit</a>
                                @if($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy',$u) }}" onsubmit="return confirm('Hapus akun {{ $u->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger-custom">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-5" style="color:var(--txt3);">Tidak ada data pengguna</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="users-mobile-list">
        @forelse($users as $u)
            <div class="users-mobile-card">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="td-avatar">{{ strtoupper(substr($u->name,0,2)) }}</div>
                    <div>
                        <div class="td-name-main">{{ $u->name }}</div>
                        <div class="td-name-sub">{{ $u->email }}</div>
                    </div>
                    <span class="badge-custom {{ ['admin'=>'badge-red','ustadz'=>'badge-blue','wali_santri'=>'badge-green'][$u->role]??'badge-gray' }} ms-auto">{{ $u->role_label }}</span>
                </div>
                <div class="td-name-sub mb-2">Telepon: {{ $u->no_telepon ?? '-' }}</div>
                <div class="d-flex gap-1">
                    <a href="{{ route('admin.users.edit',$u) }}" class="btn-edit-custom">Edit</a>
                    @if($u->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy',$u) }}" onsubmit="return confirm('Hapus akun {{ $u->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger-custom">Hapus</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.users.toggle-active',$u) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="badge-custom {{ $u->is_active?'badge-green':'badge-red' }}" style="border:none;cursor:pointer;">{{ $u->is_active?'Aktif':'Nonaktif' }}</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-3" style="color:var(--txt3);">Tidak ada data pengguna</div>
        @endforelse
    </div>
    <div class="pagination-custom">
        <div class="pagination-info">{{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }}</div>
        <div class="d-flex gap-1">
            @foreach($users->getUrlRange(max(1,$users->currentPage()-2),min($users->lastPage(),$users->currentPage()+2)) as $page=>$url)
                <a href="{{ $url }}" class="page-link {{ $page===$users->currentPage()?'active':'' }}">{{ $page }}</a>
            @endforeach
        </div>
    </div>
</div>
@endsection
