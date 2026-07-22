@extends('layouts.app')
@section('title', 'Penilaian Kinerja')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h5 class="mb-1" style="font-weight:700">Detail Penilaian</h5><p style="font-size:.82rem;color:var(--gray);margin:0">{{ $penilaian->karyawan->nama_lengkap ?? '-' }} - {{ $penilaian->periode }}</p></div>
    <a href="{{ route('penilaian.index') }}" class="btn btn-custom btn-outline-custom"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>
<div class="row g-3">
    <div class="col-md-4">
        <div class="content-card">
            <div class="card-header"><i class="bi bi-person me-2"></i>Data Karyawan</div>
            <div class="card-body">
                <div style="font-weight:600;font-size:1.1rem">{{ $penilaian->karyawan->nama_lengkap ?? '-' }}</div>
                <div style="color:var(--gray);font-size:.85rem">NIP: {{ $penilaian->karyawan->nip ?? '-' }}</div>
                <div style="color:var(--gray);font-size:.85rem">{{ $penilaian->karyawan->jabatan->nama_jabatan ?? '-' }}</div>
                <hr>
                <div style="font-size:.85rem"><strong>Penilai:</strong> {{ $penilaian->penilai->name ?? '-' }}</div>
                <div style="font-size:.85rem"><strong>Periode:</strong> {{ $penilaian->periode }}</div>
            </div>
        </div>
        <div class="content-card mt-3">
            <div class="card-header"><i class="bi bi-star me-2"></i>Hasil Akhir</div>
            <div class="card-body text-center">
                <div style="font-size:3rem;font-weight:700;color:{{ $penilaian->nilai_akhir>=80?'var(--success)':($penilaian->nilai_akhir>=60?'var(--warning)':'var(--danger)') }}">{{ $penilaian->nilai_akhir ?? '-' }}</div>
                <div style="color:var(--gray);font-size:.85rem">Rata-rata dari 8 kriteria</div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="content-card">
            <div class="card-header"><i class="bi bi-list-check me-2"></i>Kriteria Penilaian</div>
            <div class="card-body p-0">
                <table class="table-custom">
                    <thead><tr><th>Kriteria</th><th style="text-align:center">Skor</th></tr></thead>
                    <tbody>
                        @php
                            $kriteria = [
                                'Kedisiplinan' => $penilaian->nilai_disiplin,
                                'Kualitas Pekerjaan' => $penilaian->nilai_kualitas,
                                'Kuantitas Pekerjaan' => $penilaian->nilai_kuantitas,
                                'Tanggung Jawab' => $penilaian->nilai_tanggung_jawab,
                                'Kerja Sama' => $penilaian->nilai_kerjasama,
                                'Inisiatif' => $penilaian->nilai_inisiatif,
                                'Ketepatan Waktu' => $penilaian->nilai_ketepatan_waktu,
                                'Pencapaian Target' => $penilaian->nilai_target,
                            ];
                        @endphp
                        @foreach($kriteria as $nama => $skor)
                        <tr><td>{{ $nama }}</td><td style="text-align:center">{{ number_format($skor, 2) }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($penilaian->catatan)
        <div class="content-card mt-3">
            <div class="card-header"><i class="bi bi-chat me-2"></i>Catatan</div>
            <div class="card-body">{{ $penilaian->catatan }}</div>
        </div>
        @endif
    </div>
</div>
@endsection
