@extends('layouts.app')
@section('title', 'Penugasan Kerja')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">Penugasan Kerja</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400">Daftar penugasan dan deadline pekerjaan karyawan.</p>
    </div>
    @if(in_array(Auth::user()->role, ['admin','atasan']))
    <a href="{{ route('tugas.create') }}" 
       class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-md shadow-indigo-600/25 transition-all">
        <i class="bi bi-plus-lg text-sm"></i>
        <span>Buat Tugas Baru</span>
    </a>
    @endif
</div>

<div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                    <th class="py-3.5 px-4">Judul Penugasan</th>
                    <th class="py-3.5 px-4">Penerima Tugas</th>
                    <th class="py-3.5 px-4">Pemberi Tugas</th>
                    <th class="py-3.5 px-4">Tenggat Waktu</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                @forelse($tugas as $t)
                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                    <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-white">
                        {{ $t->judul }}
                    </td>
                    <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-slate-200">
                        {{ $t->karyawan->nama_lengkap ?? '-' }}
                    </td>
                    <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400">
                        {{ $t->pemberi->name ?? '-' }}
                    </td>
                    <td class="py-3.5 px-4 font-mono text-slate-600 dark:text-slate-400 whitespace-nowrap">
                        {{ $t->tenggat ? \Carbon\Carbon::parse($t->tenggat)->format('d/m/Y') : '-' }}
                    </td>
                    <td class="py-3.5 px-4">
                        @php
                            $badgeClass = match($t->status) {
                                'selesai' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
                                'dikerjakan' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-400 border-indigo-200 dark:border-indigo-800',
                                default => 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border-amber-200 dark:border-amber-800'
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border capitalize {{ $badgeClass }}">
                            {{ $t->status }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            @if(in_array(Auth::user()->role, ['admin','atasan']) && $t->status != 'selesai')
                            <form action="{{ route('tugas.update-status', $t) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="selesai">
                                <button type="submit" class="p-1.5 rounded-lg text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/50 transition-colors" title="Tandai Selesai" onclick="return confirm('Tandai tugas ini selesai?')">
                                    <i class="bi bi-check-lg text-base"></i>
                                </button>
                            </form>
                            @endif

                            @if(in_array(Auth::user()->role, ['admin','atasan']))
                            <form action="{{ route('tugas.destroy', $t) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tugas ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/50 transition-colors" title="Hapus">
                                    <i class="bi bi-trash text-sm"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-slate-400 text-xs">Belum ada tugas yang diberikan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $tugas->links() }}
</div>
@endsection
