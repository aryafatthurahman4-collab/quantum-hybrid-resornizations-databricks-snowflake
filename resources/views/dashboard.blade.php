@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
@php 
    $user = Auth::user(); 
    $data = $data ?? [];
    $absensiHariIni = $data['absensi_hari_ini'] ?? null;
@endphp

<!-- Attendance Alert Box for Karyawan -->
@if($user && $user->isKaryawan() && !$absensiHariIni)
<div class="mb-6 p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-transparent border border-amber-500/30 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 animate-in">
    <div class="flex items-center gap-4">
        <div class="h-11 w-11 rounded-xl bg-amber-500 text-white flex items-center justify-center text-xl flex-shrink-0 shadow-lg shadow-amber-500/25">
            <i class="bi bi-clock-history"></i>
        </div>
        <div>
            <h3 class="font-bold text-sm text-slate-900 dark:text-white">Presensi Hari Ini Belum Teratat</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400">Silakan lakukan catat kehadiran (Absen Hadir) untuk hari ini.</p>
        </div>
    </div>
    <form method="POST" action="{{ route('absensi.harian') }}">
        @csrf
        <button type="submit" name="status" value="hadir" 
                class="inline-flex items-center gap-2 h-10 px-5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold text-xs shadow-md shadow-amber-500/25 transition-all">
            <i class="bi bi-check-circle-fill"></i>
            <span>Absen Hadir Sekarang</span>
        </button>
    </form>
</div>
@endif

<!-- Stats Cards Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @if($user->isAdmin())
    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Total Karyawan</span>
            <div class="h-10 w-10 rounded-xl bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $data['total_karyawan'] ?? 0 }}</span>
            <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium flex items-center gap-0.5">
                <i class="bi bi-arrow-up-short text-base"></i> Active
            </span>
        </div>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Seluruh Satuan Kerja</p>
    </div>

    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Presensi Hari Ini</span>
            <div class="h-10 w-10 rounded-xl bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg">
                <i class="bi bi-calendar-check-fill"></i>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $data['total_absensi_hari_ini'] ?? 0 }}</span>
            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Pegawai</span>
        </div>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Sudah mengisi kehadiran</p>
    </div>

    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Izin / Cuti Pending</span>
            <div class="h-10 w-10 rounded-xl bg-amber-50 dark:bg-amber-950 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg">
                <i class="bi bi-envelope-exclamation-fill"></i>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $data['total_pengajuan_menunggu'] ?? 0 }}</span>
            <span class="text-xs text-amber-600 dark:text-amber-400 font-medium">Menunggu</span>
        </div>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Memerlukan persetujuan</p>
    </div>

    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Tugas Aktif</span>
            <div class="h-10 w-10 rounded-xl bg-violet-50 dark:bg-violet-950 text-violet-600 dark:text-violet-400 flex items-center justify-center text-lg">
                <i class="bi bi-list-task"></i>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $data['total_tugas_aktif'] ?? 0 }}</span>
            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Proyek</span>
        </div>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Dalam proses pengerjaan</p>
    </div>

    @elseif($user->isAtasan())
    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Tugas Diberikan</span>
            <div class="h-10 w-10 rounded-xl bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg">
                <i class="bi bi-list-check"></i>
            </div>
        </div>
        <span class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $data['total_tugas_diberikan'] ?? 0 }}</span>
    </div>

    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Tugas Aktif</span>
            <div class="h-10 w-10 rounded-xl bg-amber-50 dark:bg-amber-950 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg">
                <i class="bi bi-clock"></i>
            </div>
        </div>
        <span class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $data['tugas_aktif'] ?? 0 }}</span>
    </div>

    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Penilaian Dibuat</span>
            <div class="h-10 w-10 rounded-xl bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg">
                <i class="bi bi-star-fill"></i>
            </div>
        </div>
        <span class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $data['total_penilaian_dibuat'] ?? 0 }}</span>
    </div>

    @else
    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Presensi Hari Ini</span>
            <div class="h-10 w-10 rounded-xl bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg">
                <i class="bi bi-calendar-check"></i>
            </div>
        </div>
        <span class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">
            {{ $data['absensi_hari_ini'] ? ucfirst($data['absensi_hari_ini']->status) : '-' }}
        </span>
    </div>

    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Total Penugasan</span>
            <div class="h-10 w-10 rounded-xl bg-violet-50 dark:bg-violet-950 text-violet-600 dark:text-violet-400 flex items-center justify-center text-lg">
                <i class="bi bi-journal-text"></i>
            </div>
        </div>
        <span class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $data['total_tugas'] ?? 0 }}</span>
    </div>

    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Tugas On Progress</span>
            <div class="h-10 w-10 rounded-xl bg-amber-50 dark:bg-amber-950 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg">
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>
        <span class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $data['tugas_aktif'] ?? 0 }}</span>
    </div>

    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Nilai Akhir Terakhir</span>
            <div class="h-10 w-10 rounded-xl bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg">
                <i class="bi bi-award-fill"></i>
            </div>
        </div>
        <span class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">
            {{ $data['penilaian_terbaru'] ? $data['penilaian_terbaru']->nilai_akhir : '-' }}
        </span>
    </div>
    @endif
</div>

<!-- Admin Dashboard Tables Grid -->
@if($user->isAdmin())
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Presensi Terbaru -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="bi bi-calendar-check text-indigo-600 dark:text-indigo-400 text-lg"></i>
                <h3 class="font-bold text-sm text-slate-900 dark:text-white">Presensi Hari Ini</h3>
            </div>
            <a href="{{ route('absensi.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                        <th class="py-3 px-4">Karyawan</th>
                        <th class="py-3 px-4">Jam Masuk</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                    @forelse($data['absensi_terbaru'] ?? [] as $a)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="py-3 px-4 font-semibold text-slate-900 dark:text-white">
                            {{ $a->karyawan->nama_lengkap ?? '-' }}
                        </td>
                        <td class="py-3 px-4 text-slate-500 dark:text-slate-400">
                            {{ $a->jam_masuk ? \Carbon\Carbon::parse($a->jam_masuk)->format('H:i') : '-' }}
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $badgeClass = match($a->status) {
                                    'hadir' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
                                    'terlambat' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border-amber-200 dark:border-amber-800',
                                    default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border-slate-200 dark:border-slate-700'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border capitalize {{ $badgeClass }}">
                                {{ $a->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-8 text-center text-slate-400 text-xs">Belum ada data presensi hari ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pengajuan Izin Menunggu -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="bi bi-file-earmark-text text-amber-500 text-lg"></i>
                <h3 class="font-bold text-sm text-slate-900 dark:text-white">Pengajuan Izin Pending</h3>
            </div>
            <a href="{{ route('pengajuan-izin.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                        <th class="py-3 px-4">Karyawan</th>
                        <th class="py-3 px-4">Jenis</th>
                        <th class="py-3 px-4">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                    @forelse($data['pengajuan_terbaru'] ?? [] as $p)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="py-3 px-4 font-semibold text-slate-900 dark:text-white">
                            {{ $p->karyawan->nama_lengkap ?? '-' }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 capitalize">
                                {{ $p->jenis }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-slate-500 dark:text-slate-400">
                            {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m') }} - {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d/m/Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-8 text-center text-slate-400 text-xs">Tidak ada pengajuan izin yang menunggu persetujuan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Atasan Dashboard Tables Grid -->
@if($user->isAtasan())
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="bi bi-file-earmark-text text-amber-500 text-lg"></i>
                <h3 class="font-bold text-sm text-slate-900 dark:text-white">Pengajuan Menunggu Persetujuan</h3>
            </div>
            <a href="{{ route('pengajuan-izin.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                        <th class="py-3 px-4">Karyawan</th>
                        <th class="py-3 px-4">Jenis</th>
                        <th class="py-3 px-4">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                    @forelse($data['pengajuan_menunggu'] ?? [] as $p)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="py-3 px-4 font-semibold text-slate-900 dark:text-white">
                            {{ $p->karyawan->nama_lengkap ?? '-' }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 capitalize">
                                {{ $p->jenis }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-slate-500 dark:text-slate-400">
                            {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m') }} - {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d/m/Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-8 text-center text-slate-400 text-xs">Tidak ada pengajuan izin yang menunggu persetujuan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
