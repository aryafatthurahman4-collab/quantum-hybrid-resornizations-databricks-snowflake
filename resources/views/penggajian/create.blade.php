@extends('layouts.app')
@section('title', 'Hitung Gaji')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h5 class="mb-1" style="font-weight:700">Hitung Gaji</h5><p style="font-size:.82rem;color:var(--gray);margin:0">Buat perhitungan gaji karyawan</p></div>
    <a href="{{ route('penggajian.index') }}" class="btn btn-custom btn-outline-custom"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>
<div class="content-card"><div class="card-body">
    <form action="{{ route('penggajian.store') }}" method="POST" class="form-custom">
        @csrf
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label">Karyawan</label>
                <select name="karyawan_id" class="form-select" required>
                    <option value="">- Pilih -</option>
                    @foreach($karyawan as $k)
                    <option value="{{ $k->id }}" {{ old('karyawan_id')==$k->id?'selected':'' }}>{{ $k->nip }} - {{ $k->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">Periode</label><input type="month" name="periode" class="form-control" value="{{ old('periode', date('Y-m')) }}" required></div>
            <div class="col-md-4"></div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-custom btn-primary-custom"><i class="bi bi-calculator me-1"></i> Hitung & Simpan</button>
            <a href="{{ route('penggajian.index') }}" class="btn btn-custom btn-outline-custom">Batal</a>
        </div>
    </form>
</div></div>
@endsection
