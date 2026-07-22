@extends('layouts.app')
@section('title', 'Ajukan Izin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h5 class="mb-1" style="font-weight:700">Ajukan Izin / Cuti</h5><p style="font-size:.82rem;color:var(--gray);margin:0">Buat pengajuan izin atau cuti</p></div>
    <a href="{{ route('pengajuan-izin.index') }}" class="btn btn-custom btn-outline-custom"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>
<div class="content-card"><div class="card-body">
    <form action="{{ route('pengajuan-izin.store') }}" method="POST" class="form-custom">
        @csrf
        <div class="row g-3">
            @if(in_array(Auth::user()->role,['admin','atasan']))
            <div class="col-md-6"><label class="form-label">Karyawan</label>
                <select name="karyawan_id" class="form-select" required>
                    <option value="">- Pilih -</option>
                    @foreach($karyawan as $k)
                    <option value="{{ $k->id }}" {{ old('karyawan_id')==$k->id?'selected':'' }}>{{ $k->nip }} - {{ $k->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-md-6"><label class="form-label">Jenis</label>
                <select name="jenis" class="form-select" required>
                    <option value="">- Pilih -</option>
                    <option value="izin" {{ old('jenis')=='izin'?'selected':'' }}>Izin</option>
                    <option value="sakit" {{ old('jenis')=='sakit'?'selected':'' }}>Sakit</option>
                    <option value="cuti" {{ old('jenis')=='cuti'?'selected':'' }}>Cuti</option>
                    <option value="dinas_luar" {{ old('jenis')=='dinas_luar'?'selected':'' }}>Dinas Luar</option>
                </select>
            </div>
            <div class="col-md-3"><label class="form-label">Tanggal Mulai</label><input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai',date('Y-m-d')) }}" required></div>
            <div class="col-md-3"><label class="form-label">Tanggal Selesai</label><input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai',date('Y-m-d')) }}" required></div>
            <div class="col-12"><label class="form-label">Alasan</label><textarea name="alasan" class="form-control" rows="3" required>{{ old('alasan') }}</textarea></div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-custom btn-primary-custom"><i class="bi bi-send me-1"></i> Ajukan</button>
            <a href="{{ route('pengajuan-izin.index') }}" class="btn btn-custom btn-outline-custom">Batal</a>
        </div>
    </form>
</div></div>
@endsection
