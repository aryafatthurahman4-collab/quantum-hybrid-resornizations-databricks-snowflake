@extends('layouts.app')
@section('title', 'Edit Karyawan')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="p-2.5 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                <i class="bi bi-pencil-square text-xl"></i>
            </span>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Edit Data Karyawan</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Perbarui biodata & status kepegawaian {{ $karyawan->nama_lengkap }}</p>
            </div>
        </div>
        <a href="{{ route('karyawan.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-semibold shadow-sm transition-all">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 md:p-8">
        <form action="{{ route('karyawan.update', $karyawan) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Section 1: Informasi Kepegawaian -->
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 pb-2 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
                    <i class="bi bi-briefcase text-indigo-500"></i> Informasi Kepegawaian
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">NIK / NIP</label>
                        <input type="text" name="nik" value="{{ old('nik', old('nip', $karyawan->nik ?? $karyawan->nip)) }}" required class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $karyawan->nama_lengkap) }}" required class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', $karyawan->tanggal_masuk ? \Carbon\Carbon::parse($karyawan->tanggal_masuk)->format('Y-m-d') : '') }}" required class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Jabatan</label>
                        <select name="jabatan_id" required class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                            @foreach($jabatan as $j)
                                <option value="{{ $j->id }}" {{ old('jabatan_id', $karyawan->jabatan_id) == $j->id ? 'selected' : '' }}>{{ $j->nama_jabatan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Satuan Kerja / Unit</label>
                        <select name="satuan_kerja_id" required class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                            @foreach($units ?? $satuanKerja ?? [] as $s)
                                <option value="{{ $s->id }}" {{ old('satuan_kerja_id', $karyawan->satuan_kerja_id) == $s->id ? 'selected' : '' }}>{{ $s->nama_divisi ?? $s->nama_unit }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Status Kepegawaian</label>
                        <select name="status_kepegawaian" class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                            <option value="tetap" {{ old('status_kepegawaian', $karyawan->status_kepegawaian) == 'tetap' ? 'selected' : '' }}>Tetap</option>
                            <option value="kontrak" {{ old('status_kepegawaian', $karyawan->status_kepegawaian) == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                            <option value="magang" {{ old('status_kepegawaian', $karyawan->status_kepegawaian) == 'magang' ? 'selected' : '' }}>Magang</option>
                            <option value="honorer" {{ old('status_kepegawaian', $karyawan->status_kepegawaian) == 'honorer' ? 'selected' : '' }}>Honorer</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Status Aktif</label>
                        <select name="aktif" class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                            <option value="1" {{ old('aktif', $karyawan->aktif) == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('aktif', $karyawan->aktif) == 0 ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 2: Biodata Pribadi -->
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 pb-2 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
                    <i class="bi bi-person-vcard text-indigo-500"></i> Biodata Pribadi & Kontak
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $karyawan->tempat_lahir) }}" class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir ? \Carbon\Carbon::parse($karyawan->tanggal_lahir)->format('Y-m-d') : '') }}" class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                            <option value="Laki-laki" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'Laki-laki' || old('jenis_kelamin', $karyawan->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'Perempuan' || old('jenis_kelamin', $karyawan->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">No. Telepon / WhatsApp</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp', old('no_telepon', $karyawan->no_telp ?? $karyawan->no_telepon)) }}" class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                    </div>

                    <div class="md:col-span-2 lg:col-span-3">
                        <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">{{ old('alamat', $karyawan->alamat) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('karyawan.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-semibold transition-all">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-md shadow-indigo-600/20 transition-all">
                    <i class="bi bi-check2-circle text-base"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
