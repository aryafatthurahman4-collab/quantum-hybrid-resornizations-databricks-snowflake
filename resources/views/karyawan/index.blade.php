@extends('layouts.app')
@section('title', 'Data Karyawan')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">Data Karyawan</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400">Kelola informasi profil, jabatan, unit kerja, dan status pegawai ITK.</p>
    </div>
    <a href="{{ route('karyawan.create') }}" 
       class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-md shadow-indigo-600/25 transition-all">
        <i class="bi bi-person-plus-fill text-sm"></i>
        <span>Tambah Karyawan Baru</span>
    </a>
</div>

<!-- Shadcn UI Table Card -->
<div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                    <th class="py-3.5 px-4">NIP</th>
                    <th class="py-3.5 px-4">Nama Lengkap</th>
                    <th class="py-3.5 px-4">Jabatan</th>
                    <th class="py-3.5 px-4">Satuan Kerja</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                @forelse($karyawan as $k)
                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                    <td class="py-3.5 px-4 font-mono text-slate-500 dark:text-slate-400 font-semibold">
                        {{ $k->nip }}
                    </td>
                    <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-white">
                        <div class="flex items-center gap-2.5">
                            <div class="h-7 w-7 rounded-full bg-slate-100 dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 font-bold flex items-center justify-center text-xs">
                                {{ strtoupper(substr($k->nama_lengkap, 0, 1)) }}
                            </div>
                            <span>{{ $k->nama_lengkap }}</span>
                        </div>
                    </td>
                    <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300">
                        {{ $k->jabatan->nama_jabatan ?? '-' }}
                    </td>
                    <td class="py-3.5 px-4">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                            {{ $k->satuanKerja->singkatan ?? '-' }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4">
                        @if($k->aktif)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Aktif
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200 dark:border-rose-800">
                            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Nonaktif
                        </span>
                        @endif
                    </td>
                    <td class="py-3.5 px-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('karyawan.show', $k) }}" 
                               class="p-1.5 rounded-lg text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 transition-colors" title="Detail">
                                <i class="bi bi-eye text-sm"></i>
                            </a>
                            <a href="{{ route('karyawan.edit', $k) }}" 
                               class="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/50 transition-colors" title="Edit">
                                <i class="bi bi-pencil text-sm"></i>
                            </a>
                            <form action="{{ route('karyawan.destroy', $k) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data karyawan ini?')">
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
                    <td colspan="6" class="py-12 text-center text-slate-400 text-xs">Belum ada data karyawan terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $karyawan->links() }}
</div>
@endsection
