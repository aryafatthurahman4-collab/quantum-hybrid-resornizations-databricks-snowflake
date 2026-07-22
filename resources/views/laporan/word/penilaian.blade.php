<!DOCTYPE html>
<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
<head><meta charset="UTF-8"><title>Penilaian Kinerja</title>
<style>
    body { font-family: 'Times New Roman', serif; font-size: 12pt; }
    h2 { text-align: center; font-size: 16pt; }
    table { width: 100%; border-collapse: collapse; margin-top: 12pt; }
    th, td { border: 1px solid #000; padding: 4pt 6pt; text-align: center; font-size: 10pt; }
    th { background: #1a237e; color: #fff; font-weight: bold; }
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