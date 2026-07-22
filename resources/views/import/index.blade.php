@extends('layouts.app')
@section('title', 'Import Data Excel')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">Import Data Excel</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400">Unggah berkas Excel (.xlsx/.xls) untuk mengimpor data karyawan dan absensi secara massal.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Import Karyawan Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div class="h-10 w-10 rounded-xl bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white">Import Data Karyawan</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Tambahkan atau perbarui data pegawai baru.</p>
                </div>
            </div>
            <a href="{{ route('import.template-karyawan') }}" 
               class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-700 dark:text-slate-300 font-semibold text-xs transition-colors mb-4">
                <i class="bi bi-download"></i> Unduh Template Karyawan (.xlsx)
            </a>
        </div>

        <form action="{{ route('import.karyawan') }}" method="POST" enctype="multipart/form-data" id="formImportKaryawan">
            @csrf
            <div class="border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl p-6 text-center cursor-pointer hover:border-indigo-500 transition-colors bg-slate-50/50 dark:bg-slate-800/30 mb-4"
                 onclick="document.getElementById('fileKaryawan').click()">
                <input type="file" name="file" id="fileKaryawan" class="hidden" accept=".xlsx,.xls,.csv" required onchange="updateFileName(this, 'karyawanLabel', 'btnImportKaryawan')">
                <i class="bi bi-cloud-arrow-up text-3xl text-indigo-600 dark:text-indigo-400 block mb-2"></i>
                <span id="karyawanLabel" class="text-xs font-semibold text-slate-700 dark:text-slate-300">Pilih berkas Excel karyawan</span>
                <p class="text-[10px] text-slate-400 mt-1">Format .xlsx, .xls, .csv (Maksimal 5MB)</p>
            </div>
            <button type="submit" id="btnImportKaryawan" disabled
                    class="w-full h-10 rounded-xl bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold text-xs shadow-md shadow-indigo-600/25 transition-all">
                Mulai Import Karyawan
            </button>
        </form>
    </div>

    <!-- Import Absensi Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div class="h-10 w-10 rounded-xl bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white">Import Data Presensi</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Unggah rekap absensi mesin/Excel.</p>
                </div>
            </div>
            <a href="{{ route('import.template-absensi') }}" 
               class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-700 dark:text-slate-300 font-semibold text-xs transition-colors mb-4">
                <i class="bi bi-download"></i> Unduh Template Presensi (.xlsx)
            </a>
        </div>

        <form action="{{ route('import.absensi') }}" method="POST" enctype="multipart/form-data" id="formImportAbsensi">
            @csrf
            <div class="border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl p-6 text-center cursor-pointer hover:border-emerald-500 transition-colors bg-slate-50/50 dark:bg-slate-800/30 mb-4"
                 onclick="document.getElementById('fileAbsensi').click()">
                <input type="file" name="file" id="fileAbsensi" class="hidden" accept=".xlsx,.xls,.csv" required onchange="updateFileName(this, 'absensiLabel', 'btnImportAbsensi')">
                <i class="bi bi-cloud-arrow-up text-3xl text-emerald-600 dark:text-emerald-400 block mb-2"></i>
                <span id="absensiLabel" class="text-xs font-semibold text-slate-700 dark:text-slate-300">Pilih berkas Excel absensi</span>
                <p class="text-[10px] text-slate-400 mt-1">Format .xlsx, .xls, .csv (Maksimal 5MB)</p>
            </div>
            <button type="submit" id="btnImportAbsensi" disabled
                    class="w-full h-10 rounded-xl bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold text-xs shadow-md shadow-emerald-600/25 transition-all">
                Mulai Import Presensi
            </button>
        </form>
    </div>
</div>

@if(isset($logs) && $logs->count())
<div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
    <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
        <h3 class="font-bold text-sm text-slate-900 dark:text-white">Riwayat Log Import Terakhir</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                    <th class="py-3.5 px-4">Waktu</th>
                    <th class="py-3.5 px-4">Modul</th>
                    <th class="py-3.5 px-4">Nama File</th>
                    <th class="py-3.5 px-4">Sukses</th>
                    <th class="py-3.5 px-4">Gagal</th>
                    <th class="py-3.5 px-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                @foreach($logs as $l)
                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                    <td class="py-3.5 px-4 font-mono text-slate-500 dark:text-slate-400">
                        {{ $l->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="py-3.5 px-4 font-semibold text-slate-900 dark:text-white capitalize">
                        {{ $l->tipe_import }}
                    </td>
                    <td class="py-3.5 px-4 text-slate-600 dark:text-slate-400 font-mono">
                        {{ $l->nama_file }}
                    </td>
                    <td class="py-3.5 px-4 font-bold text-emerald-600 dark:text-emerald-400">
                        {{ $l->berhasil }}
                    </td>
                    <td class="py-3.5 px-4 font-bold text-rose-600 dark:text-rose-400">
                        {{ $l->gagal }}
                    </td>
                    <td class="py-3.5 px-4">
                        @if($l->gagal == 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                            Sukses
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                            Sebagian Error
                        </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<script>
function updateFileName(input, labelId, btnId) {
    const label = document.getElementById(labelId);
    const btn = document.getElementById(btnId);
    if (input.files && input.files[0]) {
        label.textContent = input.files[0].name;
        btn.disabled = false;
    }
}
</script>
@endsection
