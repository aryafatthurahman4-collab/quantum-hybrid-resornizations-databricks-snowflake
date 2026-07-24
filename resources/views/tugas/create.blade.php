@extends('layouts.app')
@section('title', 'Buat Tugas Baru')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="p-2.5 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                <i class="bi bi-clipboard-plus text-xl"></i>
            </span>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Buat Tugas Karyawan</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Delegasikan penugasan kerja baru kepada staf</p>
            </div>
        </div>
        <a href="{{ route('tugas.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-semibold shadow-sm transition-all">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 md:p-8">
        <form action="{{ route('tugas.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Judul Tugas</label>
                    <input type="text" name="judul" value="{{ old('judul') }}" required placeholder="Contoh: Penyusunan Laporan Tahunan SDM" class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                </div>

                <div>
                    <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Penerima Tugas (Karyawan)</label>
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
                    <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Tenggat Waktu (Deadline)</label>
                    <input type="date" name="tenggat" value="{{ old('tenggat') }}" class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                </div>

                <div>
                    <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Tingkat Prioritas</label>
                    <select name="prioritas" required class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                        <option value="rendah" {{ old('prioritas') == 'rendah' ? 'selected' : '' }}>Rendah (Low)</option>
                        <option value="sedang" {{ old('prioritas', 'sedang') == 'sedang' ? 'selected' : '' }}>Sedang (Medium)</option>
                        <option value="tinggi" {{ old('prioritas') == 'tinggi' ? 'selected' : '' }}>Tinggi (High)</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Deskripsi & Instruksi Tugas</label>
                    <textarea name="deskripsi" rows="4" placeholder="Detail instruksi penugasan..." class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">{{ old('deskripsi') }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('tugas.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-semibold transition-all">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-md shadow-indigo-600/20 transition-all">
                    <i class="bi bi-check2-circle text-base"></i> Simpan Penugasan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
