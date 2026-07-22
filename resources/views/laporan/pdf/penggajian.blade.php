<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Penggajian</title>
<style>
    body { font-family: Arial, sans-serif; font-size: 11px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #333; padding: 5px 8px; text-align: center; }
    th { background: #1a237e; color: #fff; font-size: 10px; }
    td { font-size: 10px; }
    h2 { text-align: center; margin-bottom: 4px; }
    .total { text-align: right; margin-top: 10px; font-weight: bold; font-size: 12px; }
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