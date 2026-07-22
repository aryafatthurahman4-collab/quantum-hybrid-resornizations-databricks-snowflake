@extends('layouts.app')
@section('title', 'Slip Gaji')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="content-card" id="slipGaji">
            <div class="card-header">
                <span><i class="bi bi-receipt me-2"></i>SLIP GAJI KARYAWAN</span>
                <span style="font-weight:400;font-size:.8rem">Periode: {{ \Carbon\Carbon::parse($penggajian->periode.'-01')->format('F Y') }}</span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-6"><strong>Nama:</strong> {{ $penggajian->karyawan->nama_lengkap ?? '-' }}</div>
                    <div class="col-6"><strong>NIP:</strong> {{ $penggajian->karyawan->nip ?? '-' }}</div>
                    <div class="col-6"><strong>Jabatan:</strong> {{ $penggajian->karyawan->jabatan->nama_jabatan ?? '-' }}</div>
                    <div class="col-6"><strong>Status:</strong> {{ $penggajian->status }}</div>
                </div>
                <hr>
                <table class="table-custom mb-3">
                    <thead><tr><th>Komponen</th><th style="text-align:right">Penghasilan</th><th style="text-align:right">Potongan</th></tr></thead>
                    <tbody>
                        <tr><td style="font-weight:500">Gaji Pokok</td><td style="text-align:right;font-weight:600">Rp {{ number_format($penggajian->gaji_pokok,0,',','.') }}</td><td></td></tr>
                        @foreach($penggajian->detail as $d)
                        <tr>
                            <td>{{ $d->nama_komponen }}</td>
                            @if($d->tipe == 'penghasilan')
                            <td style="text-align:right;color:var(--success)">Rp {{ number_format(abs($d->nilai),0,',','.') }}</td><td></td>
                            @else
                            <td></td><td style="text-align:right;color:var(--danger)">Rp {{ number_format(abs($d->nilai),0,',','.') }}</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot><tr style="background:var(--gray-light)"><td style="font-weight:700;font-size:1rem">TOTAL</td><td style="text-align:right;font-weight:700;font-size:1rem;color:var(--success)">Rp {{ number_format($penggajian->total_diterima,0,',','.') }}</td></tr></tfoot>
                </table>
                <div class="text-center no-print mt-3">
                    <button class="btn btn-custom btn-primary-custom" onclick="window.print()"><i class="bi bi-printer me-1"></i> Cetak Slip Gaji</button>
                    <a href="{{ route('penggajian.index') }}" class="btn btn-custom btn-outline-custom"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
