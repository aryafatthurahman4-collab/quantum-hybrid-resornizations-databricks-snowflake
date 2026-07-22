@extends('layouts.app')
@section('title', 'Data Absensi')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">Data Presensi & Kehadiran</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400">Catatan riwayat presensi harian karyawan.</p>
    </div>
    @if(Auth::user()->isAdmin() || Auth::user()->isAtasan())
    <div class="flex items-center gap-2">
        <a href="{{ route('absensi.create') }}" 
           class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-md shadow-indigo-600/25 transition-all">
            <i class="bi bi-calendar-plus text-sm"></i>
            <span>Catat Absensi Manual</span>
        </a>
        <a href="{{ route('absensi.rekap') }}" 
           class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs transition-all">
            <i class="bi bi-table text-sm"></i>
            <span>Rekap Bulanan</span>
        </a>
    </div>
    @endif
</div>

<div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                    <th class="py-3.5 px-4">Tanggal</th>
                    <th class="py-3.5 px-4">Nama Karyawan</th>
                    <th class="py-3.5 px-4">Jam Masuk</th>
                    <th class="py-3.5 px-4">Jam Pulang</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                @forelse($absensi as $a)
                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                    <td class="py-3.5 px-4 font-mono font-bold text-slate-900 dark:text-white whitespace-nowrap">
                        {{ $a->tanggal?->format('d M Y') }}
                    </td>
                    <td class="py-3.5 px-4 font-semibold text-slate-900 dark:text-white">
                        {{ $a->karyawan->nama_lengkap ?? '-' }}
                    </td>
                    <td class="py-3.5 px-4 font-mono text-slate-600 dark:text-slate-400">
                        {{ $a->jam_masuk ? \Carbon\Carbon::parse($a->jam_masuk)->format('H:i') : '-' }}
                    </td>
                    <td class="py-3.5 px-4 font-mono text-slate-600 dark:text-slate-400">
                        {{ $a->jam_pulang ? \Carbon\Carbon::parse($a->jam_pulang)->format('H:i') : '-' }}
                    </td>
                    <td class="py-3.5 px-4">
                        @php
                            $badgeClass = match($a->status) {
                                'hadir' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
                                'terlambat' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border-amber-200 dark:border-amber-800',
                                'sakit', 'izin', 'cuti' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-400 border-indigo-200 dark:border-indigo-800',
                                'alfa' => 'bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 border-rose-200 dark:border-rose-800',
                                default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border-slate-200 dark:border-slate-700'
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border capitalize {{ $badgeClass }}">
                            {{ str_replace('_', ' ', $a->status) }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('absensi.edit', $a) }}" 
                               class="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/50 transition-colors" title="Edit">
                                <i class="bi bi-pencil text-sm"></i>
                            </a>
                            <form action="{{ route('absensi.destroy', $a) }}" method="POST" class="inline" onsubmit="return confirm('Hapus catatan presensi ini?')">
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
                    <td colspan="6" class="py-12 text-center text-slate-400 text-xs">Belum ada data presensi teratatan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $absensi->links() }}
</div>
@endsection
