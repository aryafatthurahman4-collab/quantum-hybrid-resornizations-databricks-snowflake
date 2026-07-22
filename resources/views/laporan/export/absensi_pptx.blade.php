<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1e293b; }
        h1 { color: #1a237e; margin-bottom: 6px; }
        table { border-collapse: collapse; width: 100%; margin-top: 12px; }
        th { background: #1a237e; color: #fff; padding: 8px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #e2e8f0; }
    </style>
</head>
<body>
<h1>Laporan Absensi & Kehadiran</h1>
<p>Periode {{ $startDate }} s/d {{ $endDate }}</p>
<table>
    <thead><tr><th>NIP</th><th>Nama</th><th>Hadir</th><th>Terlambat</th><th>Izin</th><th>Sakit</th><th>Cuti</th><th>Dinas Luar</th><th>Alfa</th></tr></thead>
    <tbody>
    @foreach($rekap as $r)
        <tr>
            <td>{{ $r['nip'] }}</td>
            <td>{{ $r['nama'] }}</td>
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
</body>
</html>
