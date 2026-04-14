@extends('layouts.app')
@section('title', 'Rekap Kehadiran')
@section('page-title', 'Rekap Kehadiran')
@section('breadcrumb', '/ Kehadiran / <span>Rekap</span>')

@section('content')
<div class="page-header"><div><div class="page-header-title">Rekap Kehadiran Bulan Ini</div></div><a href="{{ route('ustadz.kehadiran.input') }}" class="btn-hijau">Input Kehadiran</a></div>
<div class="card-custom"><div class="table-responsive"><table class="table-custom"><thead><tr><th>Santri</th><th>Kelas</th><th>Hadir</th><th>Alpha</th></tr></thead><tbody>@forelse($rekap as $r)<tr><td>{{ $r->nama }}</td><td>{{ $r->kelas?->nama_kelas }}</td><td>{{ $r->total_hadir }}</td><td>{{ $r->total_alpha }}</td></tr>@empty<tr><td colspan="4" class="text-center">Belum ada data.</td></tr>@endforelse</tbody></table></div></div>
@endsection

