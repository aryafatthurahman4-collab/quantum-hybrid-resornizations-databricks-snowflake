@extends('layouts.app')
@section('title', 'Laporan Karyawan')
@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2 no-print">
    <div>
        <h5 class="mb-1" style="font-weight:700">Laporan Data Karyawan</h5>
        <p style="font-size:.82rem;color:var(--gray);margin:0">Seluruh data karyawan aktif dengan filter kustom</p>
    </div>
    
    <!-- Action Export Buttons -->
    <div class="report-toolbar d-flex gap-2 align-self-start flex-wrap">
        <a href="{{ route('laporan.karyawan.excel', request()->all()) }}" class="btn btn-sm btn-success d-flex align-items-center gap-1">
            <i class="bi bi-file-earmark-excel"></i> Excel
        </a>
        <a href="{{ route('laporan.karyawan.word', request()->all()) }}" class="btn btn-sm btn-primary d-flex align-items-center gap-1">
            <i class="bi bi-file-earmark-word"></i> Word
        </a>
        <a href="{{ route('laporan.karyawan.pdf', request()->all()) }}" class="btn btn-sm btn-danger d-flex align-items-center gap-1">
            <i class="bi bi-file-earmark-pdf"></i> PDF
        </a>
        <a href="{{ route('laporan.karyawan.pptx', request()->all()) }}" class="btn btn-sm btn-warning d-flex align-items-center gap-1">
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
        <form method="GET" action="{{ route('laporan.karyawan') }}" class="row g-2">
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Jabatan</label>
                <select name="jabatan_id" class="form-select form-select-sm">
                    <option value="">-- Semua Jabatan --</option>
                    @foreach($jabatans as $jab)
                        <option value="{{ $jab->id }}" {{ request('jabatan_id') == $jab->id ? 'selected' : '' }}>
                            {{ $jab->nama_jabatan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Satuan Kerja</label>
                <select name="satuan_kerja_id" class="form-select form-select-sm">
                    <option value="">-- Semua Satuan Kerja --</option>
                    @foreach($satuanKerjas as $sk)
                        <option value="{{ $sk->id }}" {{ request('satuan_kerja_id') == $sk->id ? 'selected' : '' }}>
                            {{ $sk->nama_satuan }} ({{ $sk->singkatan }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-sm btn-custom w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
                <a href="{{ route('laporan.karyawan') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Laporan Karyawan Card -->
<div class="content-card" style="border: 1px solid rgba(0,0,0,0.08); border-radius: 12px; background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
    <div class="card-body p-0">
        <table class="table-custom" style="width: 100%; border-collapse: collapse; margin: 0;">
            <thead>
                <tr>
                    <th style="padding: 12px 16px;">NIP</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Unit</th>
                    <th>Tgl Masuk</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($karyawan as $k)
                    <tr>
                        <td style="padding: 12px 16px;"><code>{{ $k->nip }}</code></td>
                        <td style="font-weight:500">{{ $k->nama_lengkap }}</td>
                        <td style="color:var(--gray)">{{ $k->jabatan->nama_jabatan ?? '-' }}</td>
                        <td>{{ $k->satuanKerja->singkatan ?? '-' }}</td>
                        <td style="color:var(--gray);font-size:.82rem">{{ $k->tanggal_masuk ?: '-' }}</td>
                        <td><span class="badge-custom {{ $k->aktif ? 'badge-success' : 'badge-danger' }}">{{ $k->aktif ? 'Aktif' : 'Nonaktif' }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Tidak ada data karyawan yang cocok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
