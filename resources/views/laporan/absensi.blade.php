@extends('layouts.app')
@section('title', 'Laporan Absensi')
@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2 no-print">
    <div>
        <h5 class="mb-1" style="font-weight:700">Laporan Absensi & Kehadiran</h5>
        <p style="font-size:.82rem;color:var(--gray);margin:0">
            Periode: <b>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</b> s/d <b>{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</b>
            @if($startTime || $endTime)
                | Jam Masuk: <b>{{ $startTime ?? 'Mulai' }}</b> - <b>{{ $endTime ?? 'Selesai' }}</b>
            @endif
        </p>
    </div>
    
    <!-- Action Export Buttons -->
    <div class="report-toolbar d-flex gap-2 align-self-start flex-wrap">
        <a href="{{ route('laporan.absensi.excel', request()->all()) }}" class="btn btn-sm btn-success d-flex align-items-center gap-1">
            <i class="bi bi-file-earmark-excel"></i> Excel
        </a>
        <a href="{{ route('laporan.absensi.word', request()->all()) }}" class="btn btn-sm btn-primary d-flex align-items-center gap-1">
            <i class="bi bi-file-earmark-word"></i> Word
        </a>
        <a href="{{ route('laporan.absensi.pdf', request()->all()) }}" class="btn btn-sm btn-danger d-flex align-items-center gap-1">
            <i class="bi bi-file-earmark-pdf"></i> PDF
        </a>
        <a href="{{ route('laporan.absensi.pptx', request()->all()) }}" class="btn btn-sm btn-warning d-flex align-items-center gap-1">
            <i class="bi bi-file-earmark-slides"></i> PPTX
        </a>
        <button class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1" onclick="window.print()">
            <i class="bi bi-printer"></i> Cetak
        </button>
    </div>
</div>

<!-- Filter Card -->
<div class="content-card mb-4 no-print" style="border: 1px solid rgba(0,0,0,0.08); border-radius: 12px; background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('laporan.absensi') }}" class="row g-2">
            <div class="col-md-3 col-6">
                <label class="form-label small text-muted mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label small text-muted mb-1">Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
            </div>
            <div class="col-md-2 col-6">
                <label class="form-label small text-muted mb-1">Jam Masuk (Min)</label>
                <input type="time" name="start_time" class="form-control form-control-sm" value="{{ $startTime }}">
            </div>
            <div class="col-md-2 col-6">
                <label class="form-label small text-muted mb-1">Jam Masuk (Max)</label>
                <input type="time" name="end_time" class="form-control form-control-sm" value="{{ $endTime }}">
            </div>
            <div class="col-md-2 d-flex align-items-end gap-1">
                <button type="submit" class="btn btn-sm btn-custom w-100"><i class="bi bi-funnel"></i></button>
                <a href="{{ route('laporan.absensi') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>
</div>

<!-- Tabs to view Rekap vs Detail -->
<ul class="nav nav-pills mb-3 no-print" id="absensiTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active btn-sm" id="rekap-tab" data-bs-toggle="pill" data-bs-target="#rekap-content" type="button" role="tab" aria-selected="true"><i class="bi bi-table me-1"></i> Rekapitulasi Kehadiran</button>
    </li>
    <li class="nav-item ms-2" role="presentation">
        <button class="nav-link btn-sm" id="detail-tab" data-bs-toggle="pill" data-bs-target="#detail-content" type="button" role="tab" aria-selected="false"><i class="bi bi-list-ul me-1"></i> Rincian Detail Kehadiran</button>
    </li>
</ul>

<div class="tab-content" id="absensiTabContent">
    <!-- Rekapitulasi Content -->
    <div class="tab-pane fade show active" id="rekap-content" role="tabpanel" aria-labelledby="rekap-tab">
        <div class="content-card" style="border: 1px solid rgba(0,0,0,0.08); border-radius: 12px; background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
            <div class="card-body p-0">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th style="padding: 12px 16px;">NIP</th>
                            <th>Nama</th>
                            <th>Unit</th>
                            <th>Hadir</th>
                            <th>Terlambat</th>
                            <th>Izin</th>
                            <th>Sakit</th>
                            <th>Cuti</th>
                            <th>Dinas Luar</th>
                            <th>Alfa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekap as $r)
                            <tr>
                                <td style="padding: 12px 16px;"><code>{{ $r['nip'] }}</code></td>
                                <td style="font-weight:500">{{ $r['nama'] }}</td>
                                <td style="color:var(--gray)">{{ $r['unit'] }}</td>
                                <td><span class="badge-custom badge-success">{{ $r['hadir'] }}</span></td>
                                <td><span class="badge-custom badge-warning">{{ $r['terlambat'] }}</span></td>
                                <td><span class="badge-custom badge-info">{{ $r['izin'] }}</span></td>
                                <td><span class="badge-custom badge-secondary">{{ $r['sakit'] }}</span></td>
                                <td><span class="badge-custom badge-primary">{{ $r['cuti'] }}</span></td>
                                <td><span class="badge-custom badge-primary" style="background-color: #cbd5e1; color: #1e293b">{{ $r['dinas_luar'] }}</span></td>
                                <td><span class="badge-custom badge-danger">{{ $r['alfa'] }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Detail Rincian Content -->
    <div class="tab-pane fade" id="detail-content" role="tabpanel" aria-labelledby="detail-tab">
        <div class="content-card" style="border: 1px solid rgba(0,0,0,0.08); border-radius: 12px; background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
            <div class="card-body p-0">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th style="padding: 12px 16px;">Tanggal</th>
                            <th>NIP</th>
                            <th>Nama Karyawan</th>
                            <th>Jabatan</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($details as $d)
                            <tr>
                                <td style="padding: 12px 16px;"><code>{{ \Carbon\Carbon::parse($d->tanggal)->format('d/m/Y') }}</code></td>
                                <td><code>{{ $d->karyawan->nip ?? '-' }}</code></td>
                                <td style="font-weight:500">{{ $d->karyawan->nama_lengkap ?? '-' }}</td>
                                <td style="color:var(--gray)">{{ $d->karyawan->jabatan->nama_jabatan ?? '-' }}</td>
                                <td>{{ $d->jam_masuk ?? '-' }}</td>
                                <td>{{ $d->jam_pulang ?? '-' }}</td>
                                <td>
                                    <span class="badge-custom @if($d->status == 'hadir') badge-success 
                                                       @elseif($d->status == 'terlambat') badge-warning
                                                       @elseif($d->status == 'izin' || $d->status == 'sakit' || $d->status == 'cuti') badge-info
                                                       @elseif($d->status == 'dinas_luar') badge-primary
                                                       @else badge-danger @endif">
                                        {{ ucfirst($d->status) }}
                                    </span>
                                </td>
                                <td><span style="font-size: .8rem; color: var(--gray)">{{ $d->keterangan ?? '-' }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Tidak ada rincian data absensi untuk filter yang ditentukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
