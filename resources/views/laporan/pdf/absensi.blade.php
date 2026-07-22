<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Rekap Absensi</title>
<style>
    body { font-family: Arial, sans-serif; font-size: 11px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #333; padding: 5px 8px; text-align: center; }
    th { background: #1a237e; color: #fff; font-size: 10px; }
    td { font-size: 10px; }
    h2 { text-align: center; margin-bottom: 4px; }
    .periode { text-align: center; font-size: 11px; color: #555; margin-bottom: 16px; }
</style>
</head>
<body>
    <h2>REKAPITULASI ABSENSI KARYAWAN</h2>
    <div class="periode">Periode: {{ $periode }}</div>
    <table>
        <thead><tr><th>No</th><th>NIP</th><th>Nama</th><th>Unit</th><th>Hadir</th><th>Terlambat</th><th>Izin</th><th>Sakit</th><th>Cuti</th><th>D.Luar</th><th>Alfa</th></tr></thead>
        <tbody>
            @foreach($rekap as $i => $r)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $r['nip'] }}</td>
                <td style="text-align:left">{{ $r['nama'] }}</td>
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
</body>
</html>