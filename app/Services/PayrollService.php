<?php
namespace App\Services;

use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\KomponenGaji;
use App\Models\Penggajian;
use App\Models\DetailPenggajian;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    public function hitungGaji(int $karyawanId, string $periode, int $userId): Penggajian
    {
        return DB::transaction(function () use ($karyawanId, $periode, $userId) {
            $karyawan = Karyawan::with('jabatan')->findOrFail($karyawanId);
            [$tahun, $bulan] = explode('-', $periode);
            $hariDalamBulan = now()->year($tahun)->month($bulan)->daysInMonth;

            $absenHadir = Absensi::where('karyawan_id', $karyawanId)
                ->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)
                ->whereIn('status', ['hadir', 'terlambat'])->count();

            $absenAlfa = Absensi::where('karyawan_id', $karyawanId)
                ->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)
                ->where('status', 'alfa')->count();

            $totalTerlambat = Absensi::where('karyawan_id', $karyawanId)
                ->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)
                ->where('status', 'terlambat')->count();

            $gajiPokok = $karyawan->jabatan->gaji_pokok;
            $totalTunjangan = 0;
            $totalPotongan = 0;
            $totalLembur = 0;
            $totalBonus = 0;
            $totalInsentif = 0;

            $komponenTetap = KomponenGaji::where('aktif', true)->where('sifat', 'tetap')->get();
            foreach ($komponenTetap as $komponen) {
                if ($komponen->tipe === 'penghasilan') {
                    $totalTunjangan += $komponen->nilai;
                } else {
                    $totalPotongan += $komponen->nilai;
                }
            }

            if ($absenAlfa > 0) {
                $potonganAlfa = ($gajiPokok / $hariDalamBulan) * $absenAlfa;
                $totalPotongan += $potonganAlfa;
            }

            if ($totalTerlambat > 0) {
                $potonganTelat = 50000 * $totalTerlambat;
                $totalPotongan += $potonganTelat;
            }

            $totalPajak = ($gajiPokok + $totalTunjangan) * 0.05;
            $totalDiterima = $gajiPokok + $totalTunjangan + $totalLembur + $totalBonus + $totalInsentif - $totalPotongan - $totalPajak;

            $penggajian = Penggajian::create([
                'karyawan_id' => $karyawanId,
                'periode' => $periode,
                'tanggal_penggajian' => now(),
                'gaji_pokok' => $gajiPokok,
                'total_tunjangan' => $totalTunjangan,
                'total_potongan' => $totalPotongan,
                'total_lembur' => $totalLembur,
                'total_bonus' => $totalBonus,
                'total_insentif' => $totalInsentif,
                'total_pajak' => $totalPajak,
                'total_diterima' => $totalDiterima,
                'status' => 'draft',
                'dibuat_oleh' => $userId,
            ]);

            foreach ($komponenTetap as $komponen) {
                DetailPenggajian::create([
                    'penggajian_id' => $penggajian->id,
                    'komponen_gaji_id' => $komponen->id,
                    'nama_komponen' => $komponen->nama,
                    'tipe' => $komponen->tipe,
                    'nilai' => $komponen->nilai,
                ]);
            }

            if ($absenAlfa > 0) {
                DetailPenggajian::create([
                    'penggajian_id' => $penggajian->id,
                    'komponen_gaji_id' => null,
                    'nama_komponen' => "Potongan Alfa ($absenAlfa hari)",
                    'tipe' => 'potongan',
                    'nilai' => ($gajiPokok / $hariDalamBulan) * $absenAlfa,
                ]);
            }

            return $penggajian;
        });
    }

    public function hitungSemuaGaji(string $periode, int $userId): array
    {
        $karyawan = Karyawan::where('aktif', true)->get();
        $hasil = [];
        foreach ($karyawan as $k) {
            $exists = Penggajian::where('karyawan_id', $k->id)->where('periode', $periode)->exists();
            if (!$exists) {
                $hasil[] = $this->hitungGaji($k->id, $periode, $userId);
            }
        }
        return $hasil;
    }
}
