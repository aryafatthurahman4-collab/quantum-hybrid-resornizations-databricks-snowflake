@extends('layouts.app')
@section('title', 'Catat Absensi')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="p-2.5 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                <i class="bi bi-calendar-check text-xl"></i>
            </span>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Catat Absensi Manual</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Input catatan kehadiran dan waktu kerja harian karyawan</p>
            </div>
        </div>
        <a href="{{ route('absensi.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-semibold shadow-sm transition-all">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 md:p-8">
        <form action="{{ route('absensi.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Pilih Karyawan</label>
                    <select name="karyawan_id" required class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                        <option value="">- Pilih Karyawan -</option>
                        @foreach($karyawan as $k)
                            <option value="{{ $k->id }}" {{ old('karyawan_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nik ?? $k->nip }} &mdash; {{ $k->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                </div>

                <div>
                    <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Status Kehadiran</label>
                    <select name="status" required class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                        <option value="hadir" {{ old('status') == 'hadir' ? 'selected' : '' }}>Hadir (On Time)</option>
                        <option value="terlambat" {{ old('status') == 'terlambat' ? 'selected' : '' }}>Terlambat (Late)</option>
                        <option value="sakit" {{ old('status') == 'sakit' ? 'selected' : '' }}>Sakit (Sick)</option>
                        <option value="izin" {{ old('status') == 'izin' ? 'selected' : '' }}>Izin (Permit)</option>
                        <option value="cuti" {{ old('status') == 'cuti' ? 'selected' : '' }}>Cuti (Leave)</option>
                        <option value="dinas_luar" {{ old('status') == 'dinas_luar' ? 'selected' : '' }}>Dinas Luar</option>
                        <option value="alfa" {{ old('status') == 'alfa' ? 'selected' : '' }}>Alfa (Absent)</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Jam Masuk</label>
                    <input type="time" name="jam_masuk" value="{{ old('jam_masuk', '08:00') }}" class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                </div>

                <div>
                    <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Jam Pulang</label>
                    <input type="time" name="jam_pulang" value="{{ old('jam_pulang', '17:00') }}" class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                </div>

                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Catatan / Keterangan</label>
                    <textarea name="keterangan" rows="2" placeholder="Catatan opsional..." class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('absensi.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-semibold transition-all">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-md shadow-indigo-600/20 transition-all">
                    <i class="bi bi-check2-circle text-base"></i> Simpan Presensi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
