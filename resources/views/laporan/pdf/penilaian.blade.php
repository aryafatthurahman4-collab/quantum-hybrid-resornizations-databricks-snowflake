<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Penilaian Kinerja</title>
<style>
    body { font-family: Arial, sans-serif; font-size: 11px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #333; padding: 5px 8px; text-align: center; }
    th { background: #1a237e; color: #fff; font-size: 10px; }
    td { font-size: 10px; }
    h2 { text-align: center; margin-bottom: 4px; }
</style>
</head>
<body>
    <h2>LAPORAN PENILAIAN KINERJA</h2>
    <table>
        <thead><tr><th>No</th><th>Nama</th><th>NIP</th><th>Penilai</th><th>Periode</th><th>N.Akhir</th><th>Grade</th></tr></thead>
        <tbody>
            @foreach($penilaian as $i => $p)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="text-align:left">{{ $p->karyawan->nama_lengkap ?? '-' }}</td>
                <td>{{ $p->karyawan->nip ?? '-' }}</td>
                <td>{{ $p->penilai->name ?? '-' }}</td>
                <td>{{ $p->periode }}</td>
                <td>{{ number_format($p->nilai_akhir, 2) }}</td>
                <td>{{ $p->nilai_akhir >= 80 ? 'Sangat Baik' : ($p->nilai_akhir >= 60 ? 'Cukup' : 'Perlu Perbaikan') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>