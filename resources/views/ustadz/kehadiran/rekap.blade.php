@extends('layouts.app')
@section('title', 'Rekap Kehadiran Bulanan')
@section('page-title', 'Rekap Kehadiran Bulanan')
@section('breadcrumb', '/ Kehadiran / <span>Rekap Bulanan</span>')

@section('content')
<div class="page-header"><div><div class="page-header-title">Rekap Bulan {{ $bulan }}/{{ $tahun }}</div></div><a href="{{ route('ustadz.kehadiran.input') }}" class="btn-outline-hijau">Kembali Input</a></div>
<div class="card-custom"><div class="table-responsive"><table class="table-custom"><thead><tr><th>Santri</th><th>Hadir</th><th>Izin</th><th>Sakit</th><th>Alpha</th><th>Persen Hadir</th></tr></thead><tbody>@foreach($rekap as $r)<tr><td>{{ $r->nama }}</td><td>{{ $r->hadir }}</td><td>{{ $r->izin }}</td><td>{{ $r->sakit }}</td><td>{{ $r->alpha }}</td><td>{{ $r->persen }}%</td></tr>@endforeach</tbody></table></div></div>
@endsection

