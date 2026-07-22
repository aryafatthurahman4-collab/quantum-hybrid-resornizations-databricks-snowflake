@extends('layouts.app')
@section('title', 'Rekap Absensi')
@section('content')
<div class="page-header flex-wrap no-print">
    <div>
        <h5 class="page-header-title">Rekap Absensi</h5>
        <p class="page-header-subtitle">Rekapitulasi kehadiran per karyawan</p>
    </div>
    <div class="d-flex gap-2">
        <form method="GET" class="d-flex align-items-center gap-2">
            <select name="bulan" class="form-select" style="width:auto">
                @foreach(range(1,12) as $m)
                <option value="{{ str_pad($m,2,'0',STR_PAD_LEFT) }}" {{ $bulan==str_pad($m,2,'0',STR_PAD_LEFT)?'selected':'' }}>{{ \Carbon\Carbon::create()->month($m)->locale('id')->monthName }}</option>
                @endforeach
            </select>
            <select name="tahun" class="form-select" style="width:auto">
                @foreach(range(now()->year, now()->year-5) as $t)
                <option value="{{ $t }}" {{ $tahun==$t?'selected':'' }}>{{ $t }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-custom btn-primary-custom"><i class="bi bi-filter me-1"></i> Filter</button>
        </form>
        <button class="btn btn-custom btn-outline-custom" onclick="window.print()"><i class="bi bi-printer me-1"></i> Cetak</button>
    </div>
</div>
<div class="content-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table-custom">
                <thead><tr><th>NIP</th><th>Nama</th><th>Hadir</th><th>Terlambat</th><th>Izin</th><th>Sakit</th><th>Cuti</th><th>Alfa</th><th>Total</th></tr></thead>
                <tbody>@foreach($rekap as $r)
                    <tr>
                        <td><code>{{ $r['karyawan']->nip }}</code></td>
                        <td style="font-weight:500">{{ $r['karyawan']->nama_lengkap }}</td>
                        <td><span class="badge-custom badge-success">{{ $r['hadir'] }}</span></td>
                        <td><span class="badge-custom badge-warning">{{ $r['terlambat'] }}</span></td>
                        <td><span class="badge-custom badge-info">{{ $r['izin'] }}</span></td>
                        <td><span class="badge-custom badge-secondary">{{ $r['sakit'] }}</span></td>
                        <td><span class="badge-custom badge-primary">{{ $r['cuti'] }}</span></td>
                        <td><span class="badge-custom badge-danger">{{ $r['alfa'] }}</span></td>
                        <td style="font-weight:600">{{ $r['total'] }}</td>
                    </tr>
                @endforeach</tbody>
            </table>
        </div>
    </div>
</div>
@endsection
