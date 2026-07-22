<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penggajian</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1e293b; }
        h1 { color: #1a237e; margin-bottom: 6px; }
        table { border-collapse: collapse; width: 100%; margin-top: 12px; }
        th { background: #1a237e; color: #fff; padding: 8px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #e2e8f0; }
    </style>
</head>
<body>
<h1>Laporan Penggajian</h1>
<p>Total Keseluruhan: Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</p>
<table>
    <thead><tr><th>Nama</th><th>NIP</th><th>Periode</th><th>Total Gaji</th><th>Status</th></tr></thead>
    <tbody>
    @foreach($penggajian as $p)
        <tr>
            <td>{{ $p->karyawan->nama_lengkap ?? '-' }}</td>
            <td>{{ $p->karyawan->nip ?? '-' }}</td>
            <td>{{ $p->periode }}</td>
            <td>Rp {{ number_format($p->total_diterima, 0, ',', '.') }}</td>
            <td>{{ ucfirst($p->status) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
