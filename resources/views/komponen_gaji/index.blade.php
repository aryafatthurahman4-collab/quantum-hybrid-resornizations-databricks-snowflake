@extends('layouts.app')
@section('title', 'Komponen Gaji')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">Komponen Gaji</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400">Master data tunjangan, bonus, dan potongan payroll.</p>
    </div>
    <a href="{{ route('komponen-gaji.create') }}" 
       class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-md shadow-indigo-600/25 transition-all">
        <i class="bi bi-plus-lg text-sm"></i>
        <span>Tambah Komponen</span>
    </a>
</div>

<div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                    <th class="py-3.5 px-4">Kode</th>
                    <th class="py-3.5 px-4">Nama Komponen</th>
                    <th class="py-3.5 px-4">Tipe</th>
                    <th class="py-3.5 px-4">Sifat</th>
                    <th class="py-3.5 px-4">Nilai Staf</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                @forelse($komponen as $k)
                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                    <td class="py-3.5 px-4 font-mono font-bold text-slate-500 dark:text-slate-400">
                        {{ $k->kode }}
                    </td>
                    <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-white">
                        {{ $k->nama }}
                    </td>
                    <td class="py-3.5 px-4">
                        @if($k->tipe == 'penghasilan')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 capitalize">
                            Penghasilan
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200 dark:border-rose-800 capitalize">
                            Potongan
                        </span>
                        @endif
                    </td>
                    <td class="py-3.5 px-4 text-slate-600 dark:text-slate-400 capitalize">
                        {{ $k->sifat }}
                    </td>
                    <td class="py-3.5 px-4 font-mono font-semibold text-slate-700 dark:text-slate-300">
                        Rp {{ number_format($k->nilai, 0, ',', '.') }}
                    </td>
                    <td class="py-3.5 px-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('komponen-gaji.edit', $k) }}" 
                               class="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/50 transition-colors" title="Edit">
                                <i class="bi bi-pencil text-sm"></i>
                            </a>
                            <form action="{{ route('komponen-gaji.destroy', $k) }}" method="POST" class="inline" onsubmit="return confirm('Hapus komponen gaji ini?')">
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
                    <td colspan="6" class="py-12 text-center text-slate-400 text-xs">Belum ada komponen gaji terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $komponen->links() }}
</div>
@endsection
