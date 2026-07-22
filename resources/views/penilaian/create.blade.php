@extends('layouts.app')
@section('title', 'Buat Penilaian')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h5 class="mb-1" style="font-weight:700">Buat Penilaian</h5><p style="font-size:.82rem;color:var(--gray);margin:0">Nilai kinerja karyawan</p></div>
    <a href="{{ route('penilaian.index') }}" class="btn btn-custom btn-outline-custom"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>
<div class="content-card"><div class="card-body">
    <form action="{{ route('penilaian.store') }}" method="POST" class="form-custom">
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
            <div class="col-md-4"><label class="form-label">Periode</label><input type="month" name="periode" class="form-control" value="{{ old('periode',date('Y-m')) }}" required></div>
            <div class="col-md-4"><label class="form-label">Tanggal Penilaian</label><input type="date" name="tanggal_penilaian" class="form-control" value="{{ old('tanggal_penilaian',date('Y-m-d')) }}" required></div>
        </div>
        <hr>
        <h6 style="font-weight:600;margin-bottom:1rem">Kriteria Penilaian (0 - 100)</h6>
        <div class="row g-3">
            @php $kriteria = ['nilai_disiplin'=>'Kedisiplinan','nilai_kualitas'=>'Kualitas Pekerjaan','nilai_kuantitas'=>'Kuantitas Pekerjaan','nilai_tanggung_jawab'=>'Tanggung Jawab','nilai_kerjasama'=>'Kerja Sama','nilai_inisiatif'=>'Inisiatif','nilai_ketepatan_waktu'=>'Ketepatan Waktu','nilai_target'=>'Pencapaian Target']; @endphp
            @foreach($kriteria as $field => $label)
            <div class="col-md-3">
                <label class="form-label">{{ $label }}</label>
                <input type="number" name="{{ $field }}" class="form-control" value="{{ old($field) }}" min="0" max="100" step="0.01" required>
            </div>
            @endforeach
        </div>
        <div class="mt-3">
            <label class="form-label">Catatan</label>
            <textarea name="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-custom btn-primary-custom"><i class="bi bi-save me-1"></i> Simpan</button>
            <a href="{{ route('penilaian.index') }}" class="btn btn-custom btn-outline-custom">Batal</a>
        </div>
    </form>
</div></div>
@endsection
