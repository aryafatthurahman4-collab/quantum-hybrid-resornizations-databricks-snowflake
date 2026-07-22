<!DOCTYPE html>
<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
<head><meta charset="UTF-8"><title>Penggajian</title>
<style>
    body { font-family: 'Times New Roman', serif; font-size: 12pt; }
    h2 { text-align: center; font-size: 16pt; }
    table { width: 100%; border-collapse: collapse; margin-top: 12pt; }
    th, td { border: 1px solid #000; padding: 4pt 6pt; text-align: center; font-size: 10pt; }
    th { background: #1a237e; color: #fff; font-weight: bold; }
    .total { text-align: right; margin-top: 10pt; font-weight: bold; font-size: 12pt; }
</style>
</head>
<body>
    <h2>LAPORAN PENGGAJIAN</h2>
    <table>
        <thead><tr><th>No</th><th>Nama</th><th>NIP</th><th>Periode</th><th>Gaji Pokok</th><th>Total Diterima</th><th>Status</th></tr></thead>
        <tbody>
            @foreach($penggajian as $i => $p)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="text-align:left">{{ $p->karyawan->nama_lengkap ?? '-' }}</td>
                <td>{{ $p->karyawan->nip ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($p->periode.'-01')->format('F Y') }}</td>
                <td style="text-align:right">Rp {{ number_format($p->gaji_pokok,0,',','.') }}</td>
                <td style="text-align:right">Rp {{ number_format($p->total_diterima,0,',','.') }}</td>
                <td>{{ ucfirst($p->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="total">Total Keseluruhan: Rp {{ number_format($totalKeseluruhan,0,',','.') }}</div>
</body>
</html>