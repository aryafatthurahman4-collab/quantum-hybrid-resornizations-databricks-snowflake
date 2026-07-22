@extends('layouts.app')
@section('title', 'Laporan Penilaian')
@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2 no-print">
    <div>
        <h5 class="mb-1" style="font-weight:700">Laporan Penilaian Kinerja</h5>
        <p style="font-size:.82rem;color:var(--gray);margin:0">Hasil evaluasi kinerja karyawan dengan nilai akhir dan grade</p>
    </div>
    
    <!-- Action Export Buttons -->
    <div class="report-toolbar d-flex gap-2 align-self-start flex-wrap">
        <a href="{{ route('laporan.penilaian.excel', request()->all()) }}" class="btn btn-sm btn-success d-flex align-items-center gap-1">
            <i class="bi bi-file-earmark-excel"></i> Excel
        </a>
        <a href="{{ route('laporan.penilaian.word', request()->all()) }}" class="btn btn-sm btn-primary d-flex align-items-center gap-1">
            <i class="bi bi-file-earmark-word"></i> Word
        </a>
        <a href="{{ route('laporan.penilaian.pdf', request()->all()) }}" class="btn btn-sm btn-danger d-flex align-items-center gap-1">
            <i class="bi bi-file-earmark-pdf"></i> PDF
        </a>
        <a href="{{ route('laporan.penilaian.pptx', request()->all()) }}" class="btn btn-sm btn-warning d-flex align-items-center gap-1">
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
        <form method="GET" action="{{ route('laporan.penilaian') }}" class="row g-2">
            <div class="col-md-4 col-12">
                <label class="form-label small text-muted mb-1">Karyawan</label>
                <select name="karyawan_id" class="form-select form-select-sm">
                    <option value="">-- Semua Karyawan --</option>
                    @foreach($karyawans as $kar)
                        <option value="{{ $kar->id }}" {{ request('karyawan_id') == $kar->id ? 'selected' : '' }}>
                            {{ $kar->nama_lengkap }} ({{ $kar->nip }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-12">
                <label class="form-label small text-muted mb-1">Periode (Tahun-Bulan)</label>
                <input type="month" name="periode" class="form-control form-control-sm" value="{{ request('periode') }}">
            </div>
            <div class="col-md-4 col-12 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-sm btn-custom w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
                <a href="{{ route('laporan.penilaian') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="content-card" style="border: 1px solid rgba(0,0,0,0.08); border-radius: 12px; background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
    <div class="card-body p-0">
        <table class="table-custom">
            <thead>
                <tr>
                    <th style="padding: 12px 16px;">Nama</th>
                    <th>NIP</th>
                    <th>Penilai</th>
                    <th>Periode</th>
                    <th>Nilai Akhir</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penilaian as $p)
                    <tr>
                        <td style="padding: 12px 16px; font-weight:500">{{ $p->karyawan->nama_lengkap ?? '-' }}</td>
                        <td><code>{{ $p->karyawan->nip ?? '-' }}</code></td>
                        <td style="color:var(--gray)">{{ $p->penilai->name ?? '-' }}</td>
                        <td style="font-size:.82rem;color:var(--gray)">{{ $p->periode }}</td>
                        <td>{{ number_format($p->nilai_akhir, 2) }}</td>
                        <td>
                            <span class="badge-custom {{ $p->nilai_akhir>=80?'badge-success':($p->nilai_akhir>=60?'badge-warning':'badge-danger') }}">
                                {{ $p->nilai_akhir>=80?'Sangat Baik':($p->nilai_akhir>=60?'Cukup':'Perlu Perbaikan') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Tidak ada data penilaian kinerja.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
