@extends('layouts.app')
@section('title', 'Edit Komponen Gaji')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h5 class="mb-1" style="font-weight:700">Edit Komponen Gaji</h5></div>
    <a href="{{ route('komponen-gaji.index') }}" class="btn btn-custom btn-outline-custom"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>
<div class="content-card"><div class="card-body">
    <form action="{{ route('komponen-gaji.update',$komponenGaji) }}" method="POST" class="form-custom">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label">Kode</label><input type="text" name="kode" class="form-control" value="{{ old('kode',$komponenGaji->kode) }}" maxlength="30" required></div>
            <div class="col-md-4"><label class="form-label">Nama Komponen</label><input type="text" name="nama" class="form-control" value="{{ old('nama',$komponenGaji->nama) }}" maxlength="100" required></div>
            <div class="col-md-4"><label class="form-label">Tipe</label>
                <select name="tipe" class="form-select" required>
                    <option value="penghasilan" {{ (old('tipe',$komponenGaji->tipe)=='penghasilan')?'selected':'' }}>Penghasilan</option>
                    <option value="potongan" {{ (old('tipe',$komponenGaji->tipe)=='potongan')?'selected':'' }}>Potongan</option>
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">Sifat</label>
                <select name="sifat" class="form-select" required>
                    <option value="tetap" {{ (old('sifat',$komponenGaji->sifat)=='tetap')?'selected':'' }}>Tetap</option>
                    <option value="variable" {{ (old('sifat',$komponenGaji->sifat)=='variable')?'selected':'' }}>Variable</option>
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">Nilai (Rp)</label><input type="number" name="nilai" class="form-control" value="{{ old('nilai',$komponenGaji->nilai) }}" min="0" required></div>
            <div class="col-md-4"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control" rows="1">{{ old('keterangan',$komponenGaji->keterangan) }}</textarea></div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-custom btn-primary-custom"><i class="bi bi-save me-1"></i> Update</button>
            <a href="{{ route('komponen-gaji.index') }}" class="btn btn-custom btn-outline-custom">Batal</a>
        </div>
    </form>
</div></div>
@endsection
