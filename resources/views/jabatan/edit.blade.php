@extends('layouts.app')
@section('title', 'Edit Jabatan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h5 class="mb-1" style="font-weight:700">Edit Jabatan</h5><p style="font-size:.82rem;color:var(--gray);margin:0">Ubah data jabatan</p></div>
    <a href="{{ route('jabatan.index') }}" class="btn btn-custom btn-outline-custom"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>
<div class="content-card"><div class="card-body">
    <form action="{{ route('jabatan.update',$jabatan) }}" method="POST" class="form-custom">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Nama Jabatan</label><input type="text" name="nama_jabatan" class="form-control" value="{{ old('nama_jabatan',$jabatan->nama_jabatan) }}" required></div>
            <div class="col-md-6"><label class="form-label">Level</label><select name="level" class="form-select" required>
                <option value="Direksi" {{ (old('level',$jabatan->level)=='Direksi')?'selected':'' }}>Direksi</option>
                <option value="Manager" {{ (old('level',$jabatan->level)=='Manager')?'selected':'' }}>Manager</option>
                <option value="Supervisor" {{ (old('level',$jabatan->level)=='Supervisor')?'selected':'' }}>Supervisor</option>
                <option value="Staff" {{ (old('level',$jabatan->level)=='Staff')?'selected':'' }}>Staff</option>
            </select></div>
            <div class="col-md-6"><label class="form-label">Gaji Pokok</label><input type="number" name="gaji_pokok" class="form-control" value="{{ old('gaji_pokok',$jabatan->gaji_pokok) }}" required></div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-custom btn-primary-custom"><i class="bi bi-save me-1"></i> Update</button>
            <a href="{{ route('jabatan.index') }}" class="btn btn-custom btn-outline-custom">Batal</a>
        </div>
    </form>
</div></div>
@endsection
