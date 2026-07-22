@extends('layouts.app')
@section('title', 'Edit Karyawan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h5 class="mb-1" style="font-weight:700">Edit Karyawan</h5><p style="font-size:.82rem;color:var(--gray);margin:0">Ubah data {{ $karyawan->nama_lengkap }}</p></div>
    <a href="{{ route('karyawan.index') }}" class="btn btn-custom btn-outline-custom"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>
<div class="content-card"><div class="card-body">
    <form action="{{ route('karyawan.update',$karyawan) }}" method="POST" class="form-custom">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label">NIP</label><input type="text" name="nip" class="form-control" value="{{ old('nip',$karyawan->nip) }}" required></div>
            <div class="col-md-4"><label class="form-label">Nama Lengkap</label><input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap',$karyawan->nama_lengkap) }}" required></div>
            <div class="col-md-4"><label class="form-label">Tanggal Masuk</label><input type="date" name="tanggal_masuk" class="form-control" value="{{ old('tanggal_masuk',$karyawan->tanggal_masuk) }}" required></div>
            <div class="col-md-4"><label class="form-label">Tempat Lahir</label><input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir',$karyawan->tempat_lahir) }}"></div>
            <div class="col-md-4"><label class="form-label">Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir',$karyawan->tanggal_lahir) }}"></div>
            <div class="col-md-4"><label class="form-label">Jenis Kelamin</label><select name="jenis_kelamin" class="form-select"><option value="">- Pilih -</option><option value="L" {{ (old('jenis_kelamin',$karyawan->jenis_kelamin)=='L')?'selected':'' }}>Laki-laki</option><option value="P" {{ (old('jenis_kelamin',$karyawan->jenis_kelamin)=='P')?'selected':'' }}>Perempuan</option></select></div>
            <div class="col-md-6"><label class="form-label">Jabatan</label><select name="jabatan_id" class="form-select" required>
                @foreach($jabatan as $j)<option value="{{ $j->id }}" {{ old('jabatan_id',$karyawan->jabatan_id)==$j->id?'selected':'' }}>{{ $j->nama_jabatan }}</option>@endforeach
            </select></div>
            <div class="col-md-6"><label class="form-label">Satuan Kerja</label><select name="satuan_kerja_id" class="form-select" required>
                @foreach($units ?? $satuanKerja ?? [] as $s)<option value="{{ $s->id }}" {{ old('satuan_kerja_id',$karyawan->satuan_kerja_id)==$s->id?'selected':'' }}>{{ $s->nama_unit }}</option>@endforeach
            </select></div>
            <div class="col-md-6"><label class="form-label">Status Kepegawaian</label><select name="status_kepegawaian" class="form-select">
                <option value="tetap" {{ (old('status_kepegawaian',$karyawan->status_kepegawaian)=='tetap')?'selected':'' }}>Tetap</option>
                <option value="kontrak" {{ (old('status_kepegawaian',$karyawan->status_kepegawaian)=='kontrak')?'selected':'' }}>Kontrak</option>
                <option value="magang" {{ (old('status_kepegawaian',$karyawan->status_kepegawaian)=='magang')?'selected':'' }}>Magang</option>
                <option value="honorer" {{ (old('status_kepegawaian',$karyawan->status_kepegawaian)=='honorer')?'selected':'' }}>Honorer</option>
            </select></div>
            <div class="col-md-6"><label class="form-label">Aktif</label><select name="aktif" class="form-select">
                <option value="1" {{ (old('aktif',$karyawan->aktif)==1)?'selected':'' }}>Aktif</option>
                <option value="0" {{ (old('aktif',$karyawan->aktif)==0)?'selected':'' }}>Nonaktif</option>
            </select></div>
            <div class="col-12"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control" rows="2">{{ old('alamat',$karyawan->alamat) }}</textarea></div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-custom btn-primary-custom"><i class="bi bi-save me-1"></i> Update</button>
            <a href="{{ route('karyawan.index') }}" class="btn btn-custom btn-outline-custom">Batal</a>
        </div>
    </form>
</div></div>
@endsection
