@extends('layouts.app')
@section('title', 'Riwayat Hafalan Santri')
@section('page-title', 'Riwayat Hafalan Santri')
@section('breadcrumb', '/ Hafalan / <span>Per Santri</span>')

@section('content')
<div class="page-header"><div><div class="page-header-title">{{ $santri->nama }}</div><div class="page-header-sub">Total halaman: {{ $totalHalaman }} | Rata-rata nilai: {{ number_format($rataRataNilai ?? 0, 1) }}</div></div><a href="{{ route('ustadz.hafalan.index') }}" class="btn-outline-hijau">Kembali</a></div>
<div class="card-custom"><div class="table-responsive"><table class="table-custom"><thead><tr><th>Tanggal</th><th>Surat</th><th>Halaman</th><th>Nilai</th></tr></thead><tbody>@forelse($hafalan as $h)<tr><td>{{ $h->tanggal_setoran?->format('d M Y') }}</td><td>{{ $h->nama_surat }}</td><td>{{ $h->jumlah_halaman }}</td><td>{{ $h->nilai }}</td></tr>@empty<tr><td colspan="4" class="text-center">Belum ada data.</td></tr>@endforelse</tbody></table></div></div>
@endsection

