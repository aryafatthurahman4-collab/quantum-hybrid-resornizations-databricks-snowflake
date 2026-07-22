<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Karyawan</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1e293b; }
        h1 { color: #1a237e; margin-bottom: 6px; }
        table { border-collapse: collapse; width: 100%; margin-top: 12px; }
        th { background: #1a237e; color: #fff; padding: 8px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #e2e8f0; }
    </style>
</head>
<body>
<h1>Laporan Data Karyawan</h1>
<p>Hasil data karyawan dari aplikasi HRIS</p>
<table>
    <thead><tr><th>NIP</th><th>Nama</th><th>Jabatan</th><th>Unit</th><th>Status</th></tr></thead>
    <tbody>
    @foreach($karyawan as $k)
        <tr>
            <td>{{ $k->nip }}</td>
            <td>{{ $k->nama_lengkap }}</td>
            <td>{{ $k->jabatan->nama_jabatan ?? '-' }}</td>
            <td>{{ $k->satuanKerja->singkatan ?? '-' }}</td>
            <td>{{ $k->aktif ? 'Aktif' : 'Nonaktif' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
