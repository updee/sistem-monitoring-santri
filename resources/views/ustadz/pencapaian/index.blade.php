{{-- resources/views/ustadz/pencapaian/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Pencapaian Santri')
@section('page-title', 'Pencapaian Santri')
@section('breadcrumb', '/ <span>Pencapaian</span>')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Pencapaian & Prestasi Santri</div>
        <div class="page-header-sub">Dokumentasi prestasi akademik dan non-akademik</div>
    </div>
    <a href="{{ route('ustadz.pencapaian.create') }}" class="btn-hijau">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Pencapaian
    </a>
</div>

{{-- Grid Card Pencapaian --}}
<div class="row g-3">
    @forelse($pencapaian as $p)
        <div class="col-md-6 col-lg-4">
            <div class="card-custom h-100">
                <div class="card-body-custom">
                    {{-- Medal + Tingkat --}}
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            @php
                                $medalColor = match($p->peringkat) {
                                    'juara_1' => ['#f5e9c0','#9a7a1a','1'],
                                    'juara_2' => ['#f0f0f0','#5a5a6a','2'],
                                    'juara_3' => ['#f5e5d8','#8a4c20','3'],
                                    default   => ['var(--hijau-pale)','var(--hijau)','★'],
                                };
                            @endphp
                            <div style="width:36px;height:36px;border-radius:50%;background:{{ $medalColor[0] }};display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:{{ $medalColor[1] }};flex-shrink:0;">
                                {{ $medalColor[2] }}
                            </div>
                            <div>
                                <div style="font-size:13px;font-weight:700;color:var(--txt);line-height:1.3;">
                                    {{ Str::limit($p->judul_pencapaian, 28) }}
                                </div>
                                <div style="font-size:11px;color:var(--txt3);">{{ $p->peringkat_label }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Santri --}}
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="td-avatar" style="width:26px;height:26px;font-size:9px;">
                            {{ strtoupper(substr($p->santri->nama, 0, 2)) }}
                        </div>
                        <div>
                            <div style="font-size:12px;font-weight:600;color:var(--txt);">{{ Str::limit($p->santri->nama, 22) }}</div>
                            <div style="font-size:10px;color:var(--txt3);">{{ $p->santri->kelas->nama_kelas ?? '-' }}</div>
                        </div>
                    </div>

                    {{-- Tags --}}
                    <div class="d-flex gap-2 flex-wrap mb-3">
                        <span class="badge-custom badge-green" style="font-size:10px;">{{ $p->jenis }}</span>
                        @php
                            $tingkatClass = match($p->tingkat) {
                                'nasional','internasional' => 'badge-red',
                                'provinsi' => 'badge-purple',
                                'kabupaten' => 'badge-blue',
                                default => 'badge-gray',
                            };
                        @endphp
                        <span class="badge-custom {{ $tingkatClass }}" style="font-size:10px;">{{ $p->tingkat_label }}</span>
                        <span style="font-size:10px;color:var(--txt3);margin-left:auto;">{{ $p->tanggal->format('d M Y') }}</span>
                    </div>

                    @if($p->keterangan)
                        <div style="font-size:11px;color:var(--txt3);background:#f9fbf9;border-radius:6px;padding:7px 10px;line-height:1.5;">
                            {{ Str::limit($p->keterangan, 80) }}
                        </div>
                    @endif
                </div>
                <div style="padding:10px 16px;border-top:1px solid var(--border-light);display:flex;gap:6px;">
                    <a href="{{ route('ustadz.pencapaian.edit', $p) }}" class="btn-edit-custom">Edit</a>
                    <form method="POST" action="{{ route('ustadz.pencapaian.destroy', $p) }}"
                        onsubmit="return confirm('Hapus data pencapaian ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger-custom">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card-custom text-center py-5" style="color:var(--txt3);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="opacity:0.3;margin:0 auto 12px;display:block;"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                Belum ada data pencapaian. <a href="{{ route('ustadz.pencapaian.create') }}" style="color:var(--hijau);font-weight:600;">Tambah sekarang →</a>
            </div>
        </div>
    @endforelse
</div>
@endsection


{{-- ════════════════════════════════════════════════════════════
     resources/views/ustadz/pencapaian/create.blade.php
     ════════════════════════════════════════════════════════════ --}}
