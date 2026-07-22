@extends('layouts.app')
@section('title', 'Tambah Jabatan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h5 class="mb-1" style="font-weight:700">Tambah Jabatan</h5><p style="font-size:.82rem;color:var(--gray);margin:0">Buat data jabatan baru</p></div>
    <a href="{{ route('jabatan.index') }}" class="btn btn-custom btn-outline-custom"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>
<div class="content-card"><div class="card-body">
    <form action="{{ route('jabatan.store') }}" method="POST" class="form-custom">
        @csrf
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Nama Jabatan</label><input type="text" name="nama_jabatan" class="form-control" value="{{ old('nama_jabatan') }}" required></div>
            <div class="col-md-6"><label class="form-label">Level</label><select name="level" class="form-select" required>
                <option value="">- Pilih -</option>
                <option value="Direksi" {{ old('level')=='Direksi'?'selected':'' }}>Direksi</option>
                <option value="Manager" {{ old('level')=='Manager'?'selected':'' }}>Manager</option>
                <option value="Supervisor" {{ old('level')=='Supervisor'?'selected':'' }}>Supervisor</option>
                <option value="Staff" {{ old('level')=='Staff'?'selected':'' }}>Staff</option>
            </select></div>
            <div class="col-md-6"><label class="form-label">Gaji Pokok</label><input type="number" name="gaji_pokok" class="form-control" value="{{ old('gaji_pokok',0) }}" required></div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-custom btn-primary-custom"><i class="bi bi-save me-1"></i> Simpan</button>
            <a href="{{ route('jabatan.index') }}" class="btn btn-custom btn-outline-custom">Batal</a>
        </div>
    </form>
</div></div>
@endsection
