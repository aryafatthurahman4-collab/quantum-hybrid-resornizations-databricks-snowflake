@extends('layouts.app')
@section('title', 'Buat Penilaian Kinerja')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="p-2.5 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                <i class="bi bi-star-fill text-xl"></i>
            </span>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Input Penilaian Kinerja KPI</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Evaluasi dan skor kinerja bulanan karyawan</p>
            </div>
        </div>
        <a href="{{ route('penilaian.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-semibold shadow-sm transition-all">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 md:p-8">
        <form action="{{ route('penilaian.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Section 1: Informasi Karyawan & Periode -->
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 pb-2 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
                    <i class="bi bi-person-check text-indigo-500"></i> Informasi Karyawan & Periode Evaluasi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Pilih Karyawan</label>
                        <select name="karyawan_id" required class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                            <option value="">- Pilih Karyawan -</option>
                            @foreach($karyawan as $k)
                                <option value="{{ $k->id }}" {{ old('karyawan_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nik ?? $k->nip }} &mdash; {{ $k->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Periode Evaluasi</label>
                        <input type="month" name="periode" value="{{ old('periode', date('Y-m')) }}" required class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Tanggal Penilaian</label>
                        <input type="date" name="tanggal_penilaian" value="{{ old('tanggal_penilaian', date('Y-m-d')) }}" required class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                    </div>
                </div>
            </div>

            <!-- Section 2: Kriteria Penilaian (0-100) -->
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 pb-2 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
                    <i class="bi bi-sliders text-indigo-500"></i> Kriteria Penilaian KPI (Skala 0 - 100)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @php 
                        $kriteria = [
                            'nilai_disiplin' => 'Kedisiplinan',
                            'nilai_kualitas' => 'Kualitas Pekerjaan',
                            'nilai_kuantitas' => 'Kuantitas Pekerjaan',
                            'nilai_tanggung_jawab' => 'Tanggung Jawab',
                            'nilai_kerjasama' => 'Kerja Sama Team',
                            'nilai_inisiatif' => 'Inisiatif & Kreativitas',
                            'nilai_ketepatan_waktu' => 'Ketepatan Waktu',
                            'nilai_target' => 'Pencapaian Target'
                        ]; 
                    @endphp
                    @foreach($kriteria as $field => $label)
                        <div>
                            <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">{{ $label }}</label>
                            <input type="number" name="{{ $field }}" value="{{ old($field, 80) }}" min="0" max="100" step="0.01" required placeholder="0-100" class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Section 3: Catatan evaluasi -->
            <div>
                <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Catatan Evaluasi Manager / Evaluator</label>
                <textarea name="catatan" rows="3" placeholder="Masukan dan evaluasi untuk karyawan..." class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all">{{ old('catatan') }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('penilaian.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-semibold transition-all">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-md shadow-indigo-600/20 transition-all">
                    <i class="bi bi-check2-circle text-base"></i> Simpan Penilaian
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
