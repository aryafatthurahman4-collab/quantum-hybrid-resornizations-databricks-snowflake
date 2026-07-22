@extends('layouts.app')
@section('title', 'Edit Absensi')
@section('content')
<div class="page-header">
    <div>
        <h5 class="page-header-title">Edit Absensi</h5>
        <p class="page-header-subtitle">Ubah data kehadiran</p>
    </div>
    <a href="{{ route('absensi.index') }}" class="btn btn-custom btn-outline-custom"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>
<div class="content-card"><div class="card-body">
    <form action="{{ route('absensi.update',$absensi) }}" method="POST" class="form-custom">
        @csrf @method('PUT')
        <div class="row g-4">
            <div class="col-md-4">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal',$absensi->tanggal?->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="hadir" {{ (old('status',$absensi->status)=='hadir')?'selected':'' }}>Hadir</option>
                    <option value="terlambat" {{ (old('status',$absensi->status)=='terlambat')?'selected':'' }}>Terlambat</option>
                    <option value="sakit" {{ (old('status',$absensi->status)=='sakit')?'selected':'' }}>Sakit</option>
                    <option value="izin" {{ (old('status',$absensi->status)=='izin')?'selected':'' }}>Izin</option>
                    <option value="cuti" {{ (old('status',$absensi->status)=='cuti')?'selected':'' }}>Cuti</option>
                    <option value="dinas_luar" {{ (old('status',$absensi->status)=='dinas_luar')?'selected':'' }}>Dinas Luar</option>
                    <option value="alfa" {{ (old('status',$absensi->status)=='alfa')?'selected':'' }}>Alfa</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Jam Masuk</label>
                <input type="time" name="jam_masuk" class="form-control" value="{{ old('jam_masuk',$absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Jam Pulang</label>
                <input type="time" name="jam_pulang" class="form-control" value="{{ old('jam_pulang',$absensi->jam_pulang ? \Carbon\Carbon::parse($absensi->jam_pulang)->format('H:i') : '') }}">
            </div>
        </div>
        <div class="mt-4 pt-3 d-flex gap-2 border-top">
            <button type="submit" class="btn btn-custom btn-primary-custom"><i class="bi bi-save me-1"></i> Update</button>
            <a href="{{ route('absensi.index') }}" class="btn btn-custom btn-outline-custom">Batal</a>
        </div>
    </form>
</div></div>
@endsection
