@extends('layouts.app')
@section('title', 'Detail Karyawan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h5 class="mb-1" style="font-weight:700">Detail Karyawan</h5><p style="font-size:.82rem;color:var(--gray);margin:0">{{ $karyawan->nama_lengkap }} ({{ $karyawan->nip }})</p></div>
    <div class="d-flex gap-2">
        <a href="{{ route('karyawan.edit',$karyawan) }}" class="btn btn-custom btn-warning-custom"><i class="bi bi-pencil me-1"></i> Edit</a>
        <a href="{{ route('karyawan.index') }}" class="btn btn-custom btn-outline-custom"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-4">
        <div class="content-card">
            <div class="card-header"><i class="bi bi-person me-2"></i>Data Pribadi</div>
            <div class="card-body">
                <table style="width:100%;font-size:.85rem">
                    <tr><td style="color:var(--gray);padding:6px 0;width:100px">NIP</td><td style="font-weight:500;padding:6px 0">{{ $karyawan->nip }}</td></tr>
                    <tr><td style="color:var(--gray);padding:6px 0">Nama</td><td style="font-weight:500;padding:6px 0">{{ $karyawan->nama_lengkap }}</td></tr>
                    <tr><td style="color:var(--gray);padding:6px 0">Tempat Lahir</td><td style="padding:6px 0">{{ $karyawan->tempat_lahir ?: '-' }}</td></tr>
                    <tr><td style="color:var(--gray);padding:6px 0">Tgl Lahir</td><td style="padding:6px 0">{{ $karyawan->tanggal_lahir ?: '-' }}</td></tr>
                    <tr><td style="color:var(--gray);padding:6px 0">JK</td><td style="padding:6px 0">{{ $karyawan->jenis_kelamin == 'L' ? 'Laki-laki' : ($karyawan->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</td></tr>
                    <tr><td style="color:var(--gray);padding:6px 0">Agama</td><td style="padding:6px 0">{{ $karyawan->agama ?: '-' }}</td></tr>
                    <tr><td style="color:var(--gray);padding:6px 0">Status</td><td style="padding:6px 0">{{ $karyawan->status_perkawinan ?: '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="content-card">
            <div class="card-header"><i class="bi bi-briefcase me-2"></i>Data Kepegawaian</div>
            <div class="card-body">
                <table style="width:100%;font-size:.85rem">
                    <tr><td style="color:var(--gray);padding:6px 0;width:130px">Jabatan</td><td style="font-weight:500;padding:6px 0">{{ $karyawan->jabatan->nama_jabatan ?? '-' }}</td></tr>
                    <tr><td style="color:var(--gray);padding:6px 0">Satuan Kerja</td><td style="padding:6px 0">{{ $karyawan->satuanKerja->nama_unit ?? '-' }}</td></tr>
                    <tr><td style="color:var(--gray);padding:6px 0">Tgl Masuk</td><td style="padding:6px 0">{{ $karyawan->tanggal_masuk ?: '-' }}</td></tr>
                    <tr><td style="color:var(--gray);padding:6px 0">Status Kepeg.</td><td style="padding:6px 0"><span class="badge-custom {{ $karyawan->status_kepegawaian=='tetap'?'badge-success':'badge-info' }}">{{ ucfirst($karyawan->status_kepegawaian ?? 'kontrak') }}</span></td></tr>
                    <tr><td style="color:var(--gray);padding:6px 0">Aktif</td><td style="padding:6px 0"><span class="badge-custom {{ $karyawan->aktif ? 'badge-success' : 'badge-danger' }}">{{ $karyawan->aktif ? 'Aktif' : 'Nonaktif' }}</span></td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="content-card">
            <div class="card-header"><i class="bi bi-envelope me-2"></i>Kontak</div>
            <div class="card-body">
                <table style="width:100%;font-size:.85rem">
                    <tr><td style="color:var(--gray);padding:6px 0;width:80px">Email</td><td style="padding:6px 0">{{ $karyawan->email ?: '-' }}</td></tr>
                    <tr><td style="color:var(--gray);padding:6px 0">Telepon</td><td style="padding:6px 0">{{ $karyawan->no_telepon ?: '-' }}</td></tr>
                    <tr><td style="color:var(--gray);padding:6px 0">Alamat</td><td style="padding:6px 0">{{ $karyawan->alamat ?: '-' }}</td></tr>
                    <tr><td style="color:var(--gray);padding:6px 0">Pendidikan</td><td style="padding:6px 0">{{ $karyawan->pendidikan_terakhir ?: '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
