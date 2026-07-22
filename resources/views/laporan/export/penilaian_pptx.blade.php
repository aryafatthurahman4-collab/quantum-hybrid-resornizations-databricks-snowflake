<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penilaian</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1e293b; }
        h1 { color: #1a237e; margin-bottom: 6px; }
        table { border-collapse: collapse; width: 100%; margin-top: 12px; }
        th { background: #1a237e; color: #fff; padding: 8px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #e2e8f0; }
    </style>
</head>
<body>
<h1>Laporan Penilaian Kinerja</h1>
<table>
    <thead><tr><th>Nama</th><th>NIP</th><th>Periode</th><th>Nilai Akhir</th><th>Grade</th></tr></thead>
    <tbody>
    @foreach($penilaian as $p)
        <tr>
            <td>{{ $p->karyawan->nama_lengkap ?? '-' }}</td>
            <td>{{ $p->karyawan->nip ?? '-' }}</td>
            <td>{{ $p->periode }}</td>
            <td>{{ number_format($p->nilai_akhir, 2) }}</td>
            <td>{{ $p->nilai_akhir >= 80 ? 'Sangat Baik' : ($p->nilai_akhir >= 60 ? 'Cukup' : 'Perlu Perbaikan') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
