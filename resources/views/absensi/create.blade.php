@extends('layouts.app')
@section('title', 'Catat Absensi')
@section('content')
<div class="page-header">
    <div>
        <h5 class="page-header-title">Catat Absensi</h5>
        <p class="page-header-subtitle">Input kehadiran karyawan</p>
    </div>
    <a href="{{ route('absensi.index') }}" class="btn btn-custom btn-outline-custom"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>
<div class="content-card"><div class="card-body">
    <form action="{{ route('absensi.store') }}" method="POST" class="form-custom">
        @csrf
        <div class="row g-4">
            <div class="col-md-4">
                <label class="form-label">Karyawan</label>
                <select name="karyawan_id" class="form-select" required>
                    <option value="">- Pilih Karyawan -</option>
                    @foreach($karyawan as $k)
                    <option value="{{ $k->id }}" {{ old('karyawan_id')==$k->id?'selected':'' }}>{{ $k->nip }} &mdash; {{ $k->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal',date('Y-m-d')) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="hadir" {{ old('status')=='hadir'?'selected':'' }}>Hadir</option>
                    <option value="terlambat" {{ old('status')=='terlambat'?'selected':'' }}>Terlambat</option>
                    <option value="sakit" {{ old('status')=='sakit'?'selected':'' }}>Sakit</option>
                    <option value="izin" {{ old('status')=='izin'?'selected':'' }}>Izin</option>
                    <option value="cuti" {{ old('status')=='cuti'?'selected':'' }}>Cuti</option>
                    <option value="dinas_luar" {{ old('status')=='dinas_luar'?'selected':'' }}>Dinas Luar</option>
                    <option value="alfa" {{ old('status')=='alfa'?'selected':'' }}>Alfa</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Jam Masuk</label>
                <input type="time" name="jam_masuk" class="form-control" value="{{ old('jam_masuk') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Jam Pulang</label>
                <input type="time" name="jam_pulang" class="form-control" value="{{ old('jam_pulang') }}">
            </div>
        </div>
        <div class="mt-4 pt-3 d-flex gap-2 border-top">
            <button type="submit" class="btn btn-custom btn-primary-custom"><i class="bi bi-save me-1"></i> Simpan</button>
            <a href="{{ route('absensi.index') }}" class="btn btn-custom btn-outline-custom">Batal</a>
        </div>
    </form>
</div></div>
@endsection
