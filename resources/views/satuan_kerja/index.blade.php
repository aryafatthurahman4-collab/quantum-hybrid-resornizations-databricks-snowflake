@extends('layouts.app')
@section('title', 'Satuan Kerja')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">Satuan Kerja</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400">Daftar unit kerja dan departemen di lingkungan ITK.</p>
    </div>
    <a href="{{ route('satuan-kerja.create') }}" 
       class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-md shadow-indigo-600/25 transition-all">
        <i class="bi bi-plus-lg text-sm"></i>
        <span>Tambah Unit Kerja</span>
    </a>
</div>

<div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                    <th class="py-3.5 px-4">Nama Unit Kerja</th>
                    <th class="py-3.5 px-4">Singkatan / Kode</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                @forelse($satuanKerja as $s)
                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                    <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-white">
                        {{ $s->nama_unit }}
                    </td>
                    <td class="py-3.5 px-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-mono font-semibold bg-violet-50 text-violet-700 dark:bg-violet-950/60 dark:text-violet-400 border border-violet-200 dark:border-violet-800">
                            {{ $s->singkatan }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('satuan-kerja.edit', $s) }}" 
                               class="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/50 transition-colors" title="Edit">
                                <i class="bi bi-pencil text-sm"></i>
                            </a>
                            <form action="{{ route('satuan-kerja.destroy', $s) }}" method="POST" class="inline" onsubmit="return confirm('Hapus unit kerja ini?')">
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
                    <td colspan="3" class="py-12 text-center text-slate-400 text-xs">Belum ada unit kerja terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $satuanKerja->links() }}
</div>
@endsection
