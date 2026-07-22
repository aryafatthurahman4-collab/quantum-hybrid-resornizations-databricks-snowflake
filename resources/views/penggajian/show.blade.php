@extends('layouts.app')
@section('title', 'Penggajian')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h5 class="mb-1" style="font-weight:700">Detail Penggajian</h5><p style="font-size:.82rem;color:var(--gray);margin:0">Periode {{ \Carbon\Carbon::parse($penggajian->periode.'-01')->format('F Y') }}</p></div>
    <div class="d-flex gap-2">
        <a href="{{ route('penggajian.slip',$penggajian) }}" class="btn btn-custom btn-outline-custom"><i class="bi bi-printer me-1"></i> Slip Gaji</a>
        <a href="{{ route('penggajian.index') }}" class="btn btn-custom btn-outline-custom"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="content-card">
            <div class="card-header"><i class="bi bi-person me-2"></i>Data Karyawan</div>
            <div class="card-body">
                <div style="font-weight:600;font-size:1.1rem">{{ $penggajian->karyawan->nama_lengkap ?? '-' }}</div>
                <div style="color:var(--gray);font-size:.85rem">NIP: {{ $penggajian->karyawan->nip ?? '-' }}</div>
                <div style="color:var(--gray);font-size:.85rem">{{ $penggajian->karyawan->jabatan->nama_jabatan ?? '-' }}</div>
                <hr>
                <div class="d-flex justify-content-between"><span style="color:var(--gray)">Status</span><span class="badge-custom {{ $penggajian->status=='dibayar'?'badge-success':($penggajian->status=='dikonfirmasi'?'badge-info':'badge-warning') }}">{{ $penggajian->status }}</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="content-card">
            <div class="card-header"><i class="bi bi-wallet2 me-2"></i>Rincian Gaji</div>
            <div class="card-body p-0">
                <table class="table-custom">
                    <thead><tr><th>Komponen</th><th>Jenis</th><th style="text-align:right">Nilai</th></tr></thead>
                    <tbody>
                        <tr><td style="font-weight:500">Gaji Pokok</td><td><span class="badge-custom badge-success">gaji</span></td><td style="text-align:right;font-weight:600">Rp {{ number_format($penggajian->gaji_pokok,0,',','.') }}</td></tr>
                        @foreach($penggajian->detail as $d)
                        <tr>
                            <td style="font-weight:500">{{ $d->nama_komponen }}</td>
                            <td><span class="badge-custom {{ $d->tipe == 'penghasilan' ? 'badge-success' : 'badge-danger' }}">{{ $d->tipe }}</span></td>
                            <td style="text-align:right;font-weight:600;color:{{ $d->tipe == 'penghasilan' ? 'var(--success)' : 'var(--danger)' }}">
                                {{ $d->tipe == 'potongan' ? '-' : '+' }}Rp {{ number_format(abs($d->nilai),0,',','.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot><tr style="background:var(--gray-light)"><td colspan="2" style="font-weight:700;font-size:1rem">Total Gaji</td><td style="text-align:right;font-weight:700;font-size:1.1rem;color:var(--primary)">Rp {{ number_format($penggajian->total_diterima,0,',','.') }}</td></tr></tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
