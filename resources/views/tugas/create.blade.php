@extends('layouts.app')
@section('title', 'Buat Tugas')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h5 class="mb-1" style="font-weight:700">Buat Tugas</h5><p style="font-size:.82rem;color:var(--gray);margin:0">Berikan tugas kepada karyawan</p></div>
    <a href="{{ route('tugas.index') }}" class="btn btn-custom btn-outline-custom"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>
<div class="content-card"><div class="card-body">
    <form action="{{ route('tugas.store') }}" method="POST" class="form-custom">
        @csrf
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Judul Tugas</label><input type="text" name="judul" class="form-control" value="{{ old('judul') }}" required></div>
            <div class="col-md-6"><label class="form-label">Karyawan</label>
                <select name="karyawan_id" class="form-select" required>
                    <option value="">- Pilih -</option>
                    @foreach($karyawan as $k)
                    <option value="{{ $k->id }}" {{ old('karyawan_id')==$k->id?'selected':'' }}>{{ $k->nip }} - {{ $k->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3"><label class="form-label">Tenggat</label><input type="date" name="tenggat" class="form-control" value="{{ old('tenggat') }}"></div>
            <div class="col-md-3"><label class="form-label">Prioritas</label>
                <select name="prioritas" class="form-select" required>
                    <option value="rendah" {{ old('prioritas')=='rendah'?'selected':'' }}>Rendah</option>
                    <option value="sedang" {{ old('prioritas')=='sedang'?'selected':'' }} selected>Sedang</option>
                    <option value="tinggi" {{ old('prioritas')=='tinggi'?'selected':'' }}>Tinggi</option>
                </select>
            </div>
            <div class="col-12"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi') }}</textarea></div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-custom btn-primary-custom"><i class="bi bi-save me-1"></i> Simpan</button>
            <a href="{{ route('tugas.index') }}" class="btn btn-custom btn-outline-custom">Batal</a>
        </div>
    </form>
</div></div>
@endsection
