@extends('layouts.app')
@section('title','Daftar Izin Santri')
@section('page-title','Daftar Izin Santri')
@section('breadcrumb','/ <span>Izin Santri</span>')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Daftar Izin Santri Aktif</div>
        <div class="page-header-sub">Monitoring santri yang sedang izin hari ini</div>
    </div>
</div>

<div class="card-custom">
    <div class="table-responsive">
        <table class="table-custom">
            <thead><tr><th>No</th><th>Santri</th><th>Tanggal Izin</th><th>Kembali</th><th>Alasan</th><th>Pengaju</th></tr></thead>
            <tbody>
                @forelse($izinAktif as $idx => $iz)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td>{{ $iz->santri?->nama }}</td>
                        <td>{{ $iz->tanggal_mulai?->format('d M Y') }}</td>
                        <td>{{ $iz->tanggal_kembali?->format('d M Y') }}</td>
                        <td>{{ $iz->alasan }}</td>
                        <td>{{ $iz->pengaju?->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-5" style="color:var(--txt3);">
                        Tidak ada santri dengan izin aktif saat ini.
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
