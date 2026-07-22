@extends('layouts.app')
@section('title', 'Penilaian Kinerja')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">Penilaian Kinerja (Performance)</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400">Evaluasi kinerja berkala karyawan ITK berbasis indikator terukur.</p>
    </div>
    @if(in_array(Auth::user()->role, ['admin','atasan']))
    <a href="{{ route('penilaian.create') }}" 
       class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-md shadow-indigo-600/25 transition-all">
        <i class="bi bi-star-fill text-sm"></i>
        <span>Buat Penilaian Baru</span>
    </a>
    @endif
</div>

<div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                    <th class="py-3.5 px-4">Nama Karyawan</th>
                    <th class="py-3.5 px-4">Penilai</th>
                    <th class="py-3.5 px-4">Periode</th>
                    <th class="py-3.5 px-4">Skor Akhir</th>
                    <th class="py-3.5 px-4">Predikat / Status</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                @forelse($penilaian as $p)
                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                    <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-white">
                        {{ $p->karyawan->nama_lengkap ?? '-' }}
                    </td>
                    <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400">
                        {{ $p->penilai->name ?? '-' }}
                    </td>
                    <td class="py-3.5 px-4 font-mono text-slate-600 dark:text-slate-400">
                        {{ $p->periode }}
                    </td>
                    <td class="py-3.5 px-4 font-mono font-bold text-indigo-600 dark:text-indigo-400 text-sm">
                        {{ number_format($p->nilai_akhir, 2) ?? '-' }}
                    </td>
                    <td class="py-3.5 px-4">
                        @php
                            $badgeClass = match(true) {
                                $p->nilai_akhir >= 80 => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
                                $p->nilai_akhir >= 60 => 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border-amber-200 dark:border-amber-800',
                                default => 'bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 border-rose-200 dark:border-rose-800'
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $badgeClass }}">
                            {{ $p->nilai_akhir >= 80 ? 'Sangat Baik' : ($p->nilai_akhir >= 60 ? 'Baik' : 'Cukup') }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-right">
                        <a href="{{ route('penilaian.show', $p) }}" 
                           class="p-1.5 rounded-lg text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 transition-colors" title="Lihat Rincian">
                            <i class="bi bi-eye text-sm"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-slate-400 text-xs">Belum ada data penilaian kinerja.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $penilaian->links() }}
</div>
@endsection
