@extends('layouts.app')
@section('title', 'Izin & Cuti')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">Pengajuan Izin & Cuti</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400">Pengajuan serta status persetujuan alokasi izin/cuti karyawan.</p>
    </div>
    <a href="{{ route('pengajuan-izin.create') }}" 
       class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-md shadow-indigo-600/25 transition-all">
        <i class="bi bi-plus-lg text-sm"></i>
        <span>Buat Pengajuan Baru</span>
    </a>
</div>

<div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                    <th class="py-3.5 px-4">Nama Karyawan</th>
                    <th class="py-3.5 px-4">Jenis Izin</th>
                    <th class="py-3.5 px-4">Periode Tanggal</th>
                    <th class="py-3.5 px-4">Alasan</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                @forelse($pengajuan as $p)
                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                    <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-white">
                        {{ $p->karyawan->nama_lengkap ?? '-' }}
                    </td>
                    <td class="py-3.5 px-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 capitalize">
                            {{ $p->jenis }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 font-mono text-slate-600 dark:text-slate-400 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m/Y') }} &mdash; {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d/m/Y') }}
                    </td>
                    <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400 max-w-xs truncate">
                        {{ $p->alasan ?: '-' }}
                    </td>
                    <td class="py-3.5 px-4">
                        @php
                            $badgeClass = match($p->status) {
                                'disetujui' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
                                'ditolak' => 'bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 border-rose-200 dark:border-rose-800',
                                default => 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border-amber-200 dark:border-amber-800'
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border capitalize {{ $badgeClass }}">
                            {{ $p->status }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-right">
                        @if(in_array(Auth::user()->role, ['admin','atasan']) && $p->status == 'menunggu')
                        <form action="{{ route('pengajuan-izin.approve', $p) }}" method="POST" class="inline" onsubmit="return confirm('Setujui pengajuan izin ini?')">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm transition-all">
                                <i class="bi bi-check-lg"></i>
                                <span>Setujui</span>
                            </button>
                        </form>
                        @else
                        <span class="text-slate-400 text-xs">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-slate-400 text-xs">Belum ada data pengajuan izin/cuti.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $pengajuan->links() }}
</div>
@endsection
