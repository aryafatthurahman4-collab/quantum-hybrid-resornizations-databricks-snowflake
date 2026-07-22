<!DOCTYPE html>
<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
<head><meta charset="UTF-8"><title>Rekap Absensi</title>
<style>
    body { font-family: 'Times New Roman', serif; font-size: 12pt; }
    h2 { text-align: center; font-size: 16pt; margin-bottom: 4pt; }
    .periode { text-align: center; font-size: 11pt; margin-bottom: 16pt; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 4pt 6pt; text-align: center; font-size: 10pt; }
    th { background: #1a237e; color: #fff; font-weight: bold; }
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