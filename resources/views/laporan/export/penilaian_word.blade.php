<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Laporan Penilaian Kinerja</title>
<style>
    body { font-family: 'Calibri', Arial, sans-serif; font-size: 11pt; color: #333; }
    .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double #1a237e; padding-bottom: 10px; }
    .header h2 { margin: 0 0 4px; color: #1a237e; font-size: 18pt; }
    .header p { margin: 0; color: #666; font-size: 10pt; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th { background: #1a237e; color: #fff; padding: 8px 10px; text-align: left; font-size: 10pt; }
    td { padding: 6px 10px; border-bottom: 1px solid #ddd; }
    tr:nth-child(even) td { background: #f4f4f4; }
    .footer { text-align: center; font-size: 9pt; color: #999; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 6px; }
</style></head>
<body>
    <div class="header">
        <h2>LAPORAN PENILAIAN KINERJA</h2>
        <p>HRIS - Human Resource Information System</p>
        <p><small>Dicetak: {{ now()->format('d/m/Y H:i') }}</small></p>
    </div>
    <table>
        <thead><tr><th>No</th><th>Nama Karyawan</th><th>NIP</th><th>Penilai</th><th>Periode</th><th>Nilai Akhir</th><th>Grade</th></tr></thead>
        <tbody>
            @foreach($penilaian as $i => $p)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $p->karyawan->nama_lengkap ?? '-' }}</td>
                <td>{{ $p->karyawan->nip ?? '-' }}</td>
                <td>{{ $p->penilai->name ?? '-' }}</td>
                <td>{{ $p->periode }}</td>
                <td>{{ number_format($p->nilai_akhir, 2) }}</td>
                <td>{{ $p->nilai_akhir >= 80 ? 'Sangat Baik' : ($p->nilai_akhir >= 60 ? 'Cukup' : 'Perlu Perbaikan') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">HRIS - Laporan Penilaian Kinerja &mdash; {{ now()->format('d/m/Y') }}</div>
</body>
</html>
