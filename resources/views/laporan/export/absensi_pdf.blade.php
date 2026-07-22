<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi</title>
    <style>
        @page { margin: 20mm 15mm; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10px; color: #333; }
        .kop { text-align: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 3px double #1a237e; }
        .kop h2 { margin: 0 0 4px; color: #1a237e; font-size: 16px; }
        .kop p { margin: 0; color: #666; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th { background: #1a237e; color: #fff; padding: 6px 8px; text-align: left; font-size: 9px; text-transform: uppercase; }
        td { padding: 5px 8px; border-bottom: 1px solid #ddd; font-size: 9px; }
        tr:nth-child(even) td { background: #f8f9fa; }
        .section-title { background: #283593; color: #fff; padding: 6px 10px; font-size: 11px; font-weight: bold; margin-top: 20px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8px; color: #999; border-top: 1px solid #ddd; padding-top: 4px; }
    </style>
</head>
<body>
    <div class="kop">
        <h2>LAPORAN ABSENSI & KEHADIRAN</h2>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        <p><small>Dicetak: {{ now()->format('d/m/Y H:i') }}</small></p>
    </div>
    <div class="section-title">REKAPITULASI KEHADIRAN</div>
    <table>
        <thead>
            <tr>
                <th>No</th><th>NIP</th><th>Nama</th><th>Unit</th><th>Hadir</th><th>Terlambat</th><th>Izin</th><th>Sakit</th><th>Cuti</th><th>Dinas</th><th>Alfa</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekap as $i => $r)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $r['nip'] }}</td>
                <td>{{ $r['nama'] }}</td>
                <td>{{ $r['unit'] }}</td>
                <td>{{ $r['hadir'] }}</td>
                <td>{{ $r['terlambat'] }}</td>
                <td>{{ $r['izin'] }}</td>
                <td>{{ $r['sakit'] }}</td>
                <td>{{ $r['cuti'] }}</td>
                <td>{{ $r['dinas_luar'] }}</td>
                <td>{{ $r['alfa'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if($details->count())
    <div class="section-title">RINCIAN DETAIL KEHADIRAN</div>
    <table>
        <thead>
            <tr>
                <th>No</th><th>Tanggal</th><th>NIP</th><th>Nama</th><th>Jabatan</th><th>Jam Masuk</th><th>Jam Pulang</th><th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details as $i => $d)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($d->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $d->karyawan->nip ?? '-' }}</td>
                <td>{{ $d->karyawan->nama_lengkap ?? '-' }}</td>
                <td>{{ $d->karyawan->jabatan->nama_jabatan ?? '-' }}</td>
                <td>{{ $d->jam_masuk ?? '-' }}</td>
                <td>{{ $d->jam_pulang ?? '-' }}</td>
                <td>{{ ucfirst($d->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    <div class="footer">HRIS - Laporan Absensi &mdash; {{ now()->format('d/m/Y') }}</div>
</body>
</html>
