@extends('layouts.app')
@section('title', 'Data Jabatan')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">Data Jabatan</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400">Daftar tingkatan jabatan dan standar gaji pokok karyawan.</p>
    </div>
    <a href="{{ route('jabatan.create') }}" 
       class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-md shadow-indigo-600/25 transition-all">
        <i class="bi bi-plus-lg text-sm"></i>
        <span>Tambah Jabatan</span>
    </a>
</div>

<div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                    <th class="py-3.5 px-4">Nama Jabatan</th>
                    <th class="py-3.5 px-4">Level</th>
                    <th class="py-3.5 px-4">Gaji Pokok</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                @forelse($jabatan as $j)
                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                    <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-white">
                        {{ $j->nama_jabatan }}
                    </td>
                    <td class="py-3.5 px-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800">
                            Level {{ $j->level }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 font-mono font-semibold text-slate-700 dark:text-slate-300">
                        Rp {{ number_format($j->gaji_pokok, 0, ',', '.') }}
                    </td>
                    <td class="py-3.5 px-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('jabatan.edit', $j) }}" 
                               class="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/50 transition-colors" title="Edit">
                                <i class="bi bi-pencil text-sm"></i>
                            </a>
                            <form action="{{ route('jabatan.destroy', $j) }}" method="POST" class="inline" onsubmit="return confirm('Hapus jabatan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/50 transition-colors" title="Hapus">
                                    <i class="bi bi-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-12 text-center text-slate-400 text-xs">Belum ada data jabatan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
