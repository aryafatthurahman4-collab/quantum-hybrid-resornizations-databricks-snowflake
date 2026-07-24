@extends('layouts.app')
@section('title', 'Edit Jabatan')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="p-2.5 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                <i class="bi bi-pencil-square text-xl"></i>
            </span>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Edit Jabatan</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Perbarui data {{ $jabatan->nama_jabatan }}</p>
            </div>
        </div>
        <a href="{{ route('jabatan.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-semibold shadow-sm transition-all">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 md:p-8">
        <form action="{{ route('jabatan.update', $jabatan) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Nama Jabatan</label>
                    <input type="text" name="nama_jabatan" value="{{ old('nama_jabatan', $jabatan->nama_jabatan) }}" required class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                </div>

                <div>
                    <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Level Hirarki</label>
                    <select name="level" required class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                        <option value="Direksi" {{ old('level', $jabatan->level) == 'Direksi' ? 'selected' : '' }}>Direksi</option>
                        <option value="Manager" {{ old('level', $jabatan->level) == 'Manager' ? 'selected' : '' }}>Manager</option>
                        <option value="Supervisor" {{ old('level', $jabatan->level) == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                        <option value="Staff" {{ old('level', $jabatan->level) == 'Staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Gaji Pokok Standar (Rp)</label>
                    <input type="number" name="gaji_pokok" value="{{ old('gaji_pokok', $jabatan->gaji_pokok) }}" min="0" required class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('jabatan.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-semibold transition-all">
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
