<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Karyawan</title>
    <style>
        @page { margin: 20mm 15mm; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #333; }
        .kop { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px double #1a237e; }
        .kop h2 { margin: 0 0 4px; color: #1a237e; font-size: 18px; }
        .kop p { margin: 0; color: #666; font-size: 11px; }
        .kop hr { border: none; border-top: 2px solid #1a237e; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #1a237e; color: #fff; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; }
        td { padding: 6px 10px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) td { background: #f8f9fa; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #ddd; padding-top: 6px; }
    </style>
</head>
<body>
    <div class="kop">
        <h2>LAPORAN DATA KARYAWAN</h2>
        <p>HRIS - Human Resource Information System</p>
        <p><small>Dicetak: {{ now()->format('d/m/Y H:i') }}</small></p>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIP</th>
                <th>Nama Lengkap</th>
                <th>Jabatan</th>
                <th>Unit Kerja</th>
                <th>Tgl Masuk</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($karyawan as $i => $k)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $k->nip }}</td>
                <td>{{ $k->nama_lengkap }}</td>
                <td>{{ $k->jabatan->nama_jabatan ?? '-' }}</td>
                <td>{{ $k->satuanKerja->singkatan ?? '-' }}</td>
                <td>{{ $k->tanggal_masuk ?: '-' }}</td>
                <td>{{ $k->aktif ? 'Aktif' : 'Nonaktif' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">HRIS - Laporan Data Karyawan &mdash; {{ now()->format('d/m/Y') }}</div>
</body>
</html>
