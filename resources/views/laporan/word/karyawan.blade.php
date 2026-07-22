<!DOCTYPE html>
<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
<head><meta charset="UTF-8"><title>Data Karyawan</title>
<style>
    body { font-family: 'Times New Roman', serif; font-size: 12pt; }
    h2 { text-align: center; font-size: 16pt; }
    table { width: 100%; border-collapse: collapse; margin-top: 12pt; }
    th, td { border: 1px solid #000; padding: 4pt 6pt; text-align: center; font-size: 10pt; }
    th { background: #1a237e; color: #fff; font-weight: bold; }
</style>
</head>
<body>
    <h2>DATA KARYAWAN</h2>
    <table>
        <thead><tr><th>No</th><th>NIP</th><th>Nama</th><th>Jabatan</th><th>Unit</th><th>Tgl Masuk</th><th>Status</th></tr></thead>
        <tbody>
            @foreach($karyawan as $i => $k)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $k->nip }}</td>
                <td style="text-align:left">{{ $k->nama_lengkap }}</td>
                <td>{{ $k->jabatan?->nama_jabatan }}</td>
                <td>{{ $k->satuanKerja?->singkatan }}</td>
                <td>{{ $k->tanggal_masuk?->format('d-m-Y') }}</td>
                <td>{{ $k->aktif ? 'Aktif' : 'Nonaktif' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>