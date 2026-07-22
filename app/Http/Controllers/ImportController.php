<?php
namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\Jabatan;
use App\Models\SatuanKerja;
use App\Models\ImportLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportController extends Controller
{
    public function index()
    {
        $logs = ImportLog::with('user')->latest()->paginate(10);
        return view('import.index', compact('logs'));
    }

    public function importKaryawan(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        array_shift($rows);

        $berhasil = 0;
        $gagal = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            if (empty(array_filter($row))) continue;
            try {
                $nip = trim($row[0] ?? '');
                if (empty($nip)) {
                    $gagal++;
                    $errors[] = "Baris " . ($index + 2) . ": NIP kosong.";
                    continue;
                }

                $namaLengkap = trim($row[1] ?? '');
                if (empty($namaLengkap)) {
                    $gagal++;
                    $errors[] = "Baris " . ($index + 2) . ": Nama lengkap kosong.";
                    continue;
                }

                $jabatan = Jabatan::where('nama_jabatan', trim($row[5] ?? ''))->first();
                $unit = SatuanKerja::where('nama_unit', trim($row[6] ?? ''))->first();

                if (!$jabatan) {
                    $gagal++;
                    $errors[] = "Baris " . ($index + 2) . ": Jabatan '" . trim($row[5] ?? '') . "' tidak ditemukan.";
                    continue;
                }
                if (!$unit) {
                    $gagal++;
                    $errors[] = "Baris " . ($index + 2) . ": Unit Kerja '" . trim($row[6] ?? '') . "' tidak ditemukan.";
                    continue;
                }

                Karyawan::updateOrCreate(
                    ['nip' => $nip],
                    [
                        'nama_lengkap' => $namaLengkap,
                        'tempat_lahir' => trim($row[2] ?? ''),
                        'tanggal_lahir' => !empty($row[3]) ? $this->parseDate($row[3]) : null,
                        'jenis_kelamin' => strtoupper(substr(trim($row[4] ?? ''), 0, 1)) === 'L' ? 'L' : 'P',
                        'jabatan_id' => $jabatan->id,
                        'satuan_kerja_id' => $unit->id,
                        'tanggal_masuk' => !empty($row[7]) ? $this->parseDate($row[7]) : now(),
                        'no_telepon' => trim($row[8] ?? ''),
                        'alamat' => trim($row[9] ?? ''),
                    ]
                );
                $berhasil++;
            } catch (\Exception $e) {
                $gagal++;
                $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        ImportLog::create([
            'user_id' => Auth::id(),
            'tipe_import' => 'karyawan',
            'nama_file' => $file->getClientOriginalName(),
            'total_baris' => count($rows),
            'berhasil' => $berhasil,
            'gagal' => $gagal,
            'error_message' => !empty($errors) ? implode("\n", array_slice($errors, 0, 50)) : null,
        ]);

        if ($berhasil > 0 && $gagal === 0) {
            return redirect()->route('import.index')->with('success', "Import karyawan selesai. Berhasil: $berhasil baris.");
        } elseif ($berhasil > 0 && $gagal > 0) {
            return redirect()->route('import.index')->with('warning', "Import selesai. Berhasil: $berhasil, Gagal: $gagal. Lihat detail di riwayat.");
        } else {
            return redirect()->route('import.index')->with('error', "Import gagal seluruhnya. $gagal baris bermasalah. Lihat detail di riwayat.");
        }
    }

    public function importAbsensi(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        array_shift($rows);

        $berhasil = 0;
        $gagal = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            if (empty(array_filter($row))) continue;
            try {
                $nip = trim($row[0] ?? '');
                $tanggal = !empty($row[1]) ? $this->parseDate($row[1]) : null;

                if (empty($nip)) {
                    $gagal++;
                    $errors[] = "Baris " . ($index + 2) . ": NIP kosong.";
                    continue;
                }

                if (!$tanggal) {
                    $gagal++;
                    $errors[] = "Baris " . ($index + 2) . ": Tanggal tidak valid atau kosong.";
                    continue;
                }

                $karyawan = Karyawan::where('nip', $nip)->first();
                if (!$karyawan) {
                    $gagal++;
                    $errors[] = "Baris " . ($index + 2) . ": Karyawan dengan NIP '$nip' tidak ditemukan.";
                    continue;
                }

                $status = trim($row[4] ?? '');
                $validStatuses = ['hadir', 'terlambat', 'izin', 'sakit', 'cuti', 'dinas_luar', 'alfa'];
                if (!in_array($status, $validStatuses)) {
                    $gagal++;
                    $errors[] = "Baris " . ($index + 2) . ": Status '$status' tidak valid. Gunakan: " . implode(', ', $validStatuses);
                    continue;
                }

                $jamMasuk = $row[2] ?? null;
                $jamPulang = $row[3] ?? null;

                if ($jamMasuk && preg_match('/^\d{4}-\d{2}-\d{2}/', (string)$jamMasuk)) {
                    $jamMasuk = date('H:i:s', strtotime($jamMasuk));
                }
                if ($jamPulang && preg_match('/^\d{4}-\d{2}-\d{2}/', (string)$jamPulang)) {
                    $jamPulang = date('H:i:s', strtotime($jamPulang));
                }

                Absensi::updateOrCreate(
                    ['karyawan_id' => $karyawan->id, 'tanggal' => $tanggal],
                    [
                        'jam_masuk' => $jamMasuk,
                        'jam_pulang' => $jamPulang,
                        'status' => $status,
                        'keterangan' => !empty($row[5]) ? trim($row[5]) : null,
                    ]
                );
                $berhasil++;
            } catch (\Exception $e) {
                $gagal++;
                $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        ImportLog::create([
            'user_id' => Auth::id(),
            'tipe_import' => 'absensi',
            'nama_file' => $file->getClientOriginalName(),
            'total_baris' => count($rows),
            'berhasil' => $berhasil,
            'gagal' => $gagal,
            'error_message' => !empty($errors) ? implode("\n", array_slice($errors, 0, 50)) : null,
        ]);

        if ($berhasil > 0 && $gagal === 0) {
            return redirect()->route('import.index')->with('success', "Import absensi selesai. Berhasil: $berhasil baris.");
        } elseif ($berhasil > 0 && $gagal > 0) {
            return redirect()->route('import.index')->with('warning', "Import selesai. Berhasil: $berhasil, Gagal: $gagal. Lihat detail di riwayat.");
        } else {
            return redirect()->route('import.index')->with('error', "Import gagal seluruhnya. $gagal baris bermasalah. Lihat detail di riwayat.");
        }
    }

    public function downloadTemplateKaryawan()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Karyawan');

        $headers = ['NIP', 'Nama Lengkap', 'Tempat Lahir', 'Tanggal Lahir', 'Jenis Kelamin', 'Jabatan', 'Unit Kerja', 'Tanggal Masuk', 'No Telepon', 'Alamat'];
        $sheet->fromArray([$headers], null, 'A1');

        $jabatanList = Jabatan::pluck('nama_jabatan')->implode(', ');
        $unitList = SatuanKerja::pluck('nama_unit')->implode(', ');

        $sheet->fromArray([
            ['100001', 'Budi Santoso', 'Jakarta', '1990-05-15', 'Laki-laki', 'Staff', 'Teknologi Informasi', '2020-01-06', '081234567890', 'Jl. Merdeka No. 10, Jakarta']
        ], null, 'A2');

        $sheet->getStyle('A1:J1')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('1A237E');
        $sheet->getStyle('A1:J1')->getFont()->getColor()->setRGB('FFFFFF');

        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $infoSheet = $spreadsheet->createSheet();
        $infoSheet->setTitle('Petunjuk');
        $infoSheet->setCellValue('A1', 'PETUNJUK IMPORT KARYAWAN');
        $infoSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $infoSheet->setCellValue('A3', '1. Baris pertama adalah header, jangan diubah atau dihapus.');
        $infoSheet->setCellValue('A4', '2. NIP harus unik dan diisi.');
        $infoSheet->setCellValue('A5', '3. Jenis Kelamin: Laki-laki / Perempuan');
        $infoSheet->setCellValue('A6', '4. Format Tanggal: YYYY-MM-DD (contoh: 1990-05-15)');
        $infoSheet->setCellValue('A7', '5. Jabatan harus sesuai dengan data jabatan di sistem:');
        $infoSheet->setCellValue('A8', '   ' . $jabatanList);
        $infoSheet->setCellValue('A9', '6. Unit Kerja harus sesuai dengan data satuan kerja di sistem:');
        $infoSheet->setCellValue('A10', '   ' . $unitList);
        $infoSheet->getColumnDimension('A')->setWidth(80);

        $fileName = 'template-import-karyawan.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'template');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tempFile);
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    public function downloadTemplateAbsensi()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Absensi');

        $headers = ['NIP', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status', 'Keterangan'];
        $sheet->fromArray([$headers], null, 'A1');

        $sheet->fromArray([
            ['100001', '2026-07-20', '08:00', '17:00', 'hadir', ''],
            ['100001', '2026-07-21', '08:15', '17:00', 'terlambat', 'Macet di jalan'],
        ], null, 'A2');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('2E7D32');
        $sheet->getStyle('A1:F1')->getFont()->getColor()->setRGB('FFFFFF');

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $infoSheet = $spreadsheet->createSheet();
        $infoSheet->setTitle('Petunjuk');
        $infoSheet->setCellValue('A1', 'PETUNJUK IMPORT ABSENSI');
        $infoSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $infoSheet->setCellValue('A3', '1. Baris pertama adalah header, jangan diubah atau dihapus.');
        $infoSheet->setCellValue('A4', '2. NIP harus terdaftar di data karyawan.');
        $infoSheet->setCellValue('A5', '3. Format Tanggal: YYYY-MM-DD (contoh: 2026-07-20)');
        $infoSheet->setCellValue('A6', '4. Format Jam: HH:MM (contoh: 08:00)');
        $infoSheet->setCellValue('A7', '5. Status yang diperbolehkan:');
        $infoSheet->setCellValue('A8', '   hadir, terlambat, izin, sakit, cuti, dinas_luar, alfa');
        $infoSheet->setCellValue('A9', '6. Keterangan bersifat opsional.');
        $infoSheet->getColumnDimension('A')->setWidth(80);

        $fileName = 'template-import-absensi.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'template');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tempFile);
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    private function parseDate($value)
    {
        if ($value instanceof \DateTime) return $value->format('Y-m-d');
        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
        }
        $strtotime = strtotime(trim($value));
        return $strtotime ? date('Y-m-d', $strtotime) : null;
    }
}
