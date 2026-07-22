@extends('layouts.app')
@section('title', 'Laporan Penggajian')
@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2 no-print">
    <div>
        <h5 class="mb-1" style="font-weight:700">Laporan Penggajian</h5>
        <p style="font-size:.82rem;color:var(--gray);margin:0">Riwayat rekapitulasi penggajian karyawan aktif</p>
    </div>
    
    <!-- Action Export Buttons -->
    <div class="report-toolbar d-flex gap-2 align-self-start flex-wrap">
        <a href="{{ route('laporan.penggajian.excel', request()->all()) }}" class="btn btn-sm btn-success d-flex align-items-center gap-1">
            <i class="bi bi-file-earmark-excel"></i> Excel
        </a>
        <a href="{{ route('laporan.penggajian.word', request()->all()) }}" class="btn btn-sm btn-primary d-flex align-items-center gap-1">
            <i class="bi bi-file-earmark-word"></i> Word
        </a>
        <a href="{{ route('laporan.penggajian.pdf', request()->all()) }}" class="btn btn-sm btn-danger d-flex align-items-center gap-1">
            <i class="bi bi-file-earmark-pdf"></i> PDF
        </a>
        <a href="{{ route('laporan.penggajian.pptx', request()->all()) }}" class="btn btn-sm btn-warning d-flex align-items-center gap-1">
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
        <form method="GET" action="{{ route('laporan.penggajian') }}" class="row g-2">
            <div class="col-md-4 col-12">
                <label class="form-label small text-muted mb-1">Periode (Tahun-Bulan)</label>
                <input type="month" name="periode" class="form-control form-control-sm" value="{{ request('periode') }}">
            </div>
            <div class="col-md-4 col-12">
                <label class="form-label small text-muted mb-1">Status Pembayaran</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Semua Status --</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="dikonfirmasi" {{ request('status') == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="dibayar" {{ request('status') == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                </select>
            </div>
            <div class="col-md-4 col-12 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-sm btn-custom w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
                <a href="{{ route('laporan.penggajian') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="content-card mb-4" style="border: 1px solid rgba(0,0,0,0.08); border-radius: 12px; background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
    <div class="card-body p-0">
        <table class="table-custom">
            <thead>
                <tr>
                    <th style="padding: 12px 16px;">Nama</th>
                    <th>NIP</th>
                    <th>Periode</th>
                    <th>Gaji Pokok</th>
                    <th>Total Gaji</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penggajian as $p)
                    <tr>
                        <td style="padding: 12px 16px; font-weight:500">{{ $p->karyawan->nama_lengkap ?? '-' }}</td>
                        <td><code>{{ $p->karyawan->nip ?? '-' }}</code></td>
                        <td style="font-size:.82rem;color:var(--gray)">{{ \Carbon\Carbon::parse($p->periode.'-01')->format('F Y') }}</td>
                        <td style="color:var(--gray)">Rp {{ number_format($p->gaji_pokok,0,',','.') }}</td>
                        <td style="font-weight:600">Rp {{ number_format($p->total_diterima,0,',','.') }}</td>
                        <td>
                            <span class="badge-custom {{ $p->status=='dibayar'?'badge-success':($p->status=='dikonfirmasi'?'badge-info':'badge-warning') }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Tidak ada data penggajian.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Total Summary Card -->
<div class="row">
    <div class="col-md-4 offset-md-8 col-12">
        <div class="content-card p-3 text-end" style="border: 1px solid rgba(0,0,0,0.08); border-radius: 12px; background: #f8fafc;">
            <span class="small text-muted d-block mb-1">Total Keseluruhan Gaji Diterima</span>
            <h4 class="mb-0" style="font-weight: 700; color: #1e293b;">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</h4>
        </div>
    </div>
</div>
@endsection
