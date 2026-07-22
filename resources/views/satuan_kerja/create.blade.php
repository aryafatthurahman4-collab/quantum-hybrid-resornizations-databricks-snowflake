@extends('layouts.app')
@section('title', 'Tambah Satuan Kerja')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h5 class="mb-1" style="font-weight:700">Tambah Satuan Kerja</h5></div>
    <a href="{{ route('satuan-kerja.index') }}" class="btn btn-custom btn-outline-custom"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>
<div class="content-card"><div class="card-body">
    <form action="{{ route('satuan-kerja.store') }}" method="POST" class="form-custom">
        @csrf
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Nama Unit</label><input type="text" name="nama_unit" class="form-control" value="{{ old('nama_unit') }}" required></div>
            <div class="col-md-6"><label class="form-label">Singkatan</label><input type="text" name="singkatan" class="form-control" value="{{ old('singkatan') }}" required></div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-custom btn-primary-custom"><i class="bi bi-save me-1"></i> Simpan</button>
            <a href="{{ route('satuan-kerja.index') }}" class="btn btn-custom btn-outline-custom">Batal</a>
        </div>
    </form>
</div></div>
@endsection
