<?php
namespace Database\Seeders;

use App\Models\Karyawan;
use App\Models\User;
use App\Models\Absensi;
use App\Models\PengajuanIzin;
use App\Models\TugasKaryawan;
use App\Models\PenilaianKinerja;
use App\Models\Penggajian;
use App\Models\DetailPenggajian;
use App\Models\KomponenGaji;
use App\Models\Jabatan;
use App\Models\SatuanKerja;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $jabatanList = Jabatan::all();
        $unitList = SatuanKerja::all();
        $komponenGaji = KomponenGaji::all();

        $adminUser = User::where('email', 'admin@hr.com')->first();
        $atasanUser = User::where('email', 'atasan@hr.com')->first();

        $karyawanData = [
            ['nip' => 'STF002', 'nama' => 'Rina Amelia', 'jk' => 'P', 'tmp_lahir' => 'Jakarta', 'tgl_lahir' => '1999-03-15', 'alamat' => 'Jl. Merdeka No. 10', 'telp' => '081234567801', 'agama' => 'Islam', 'pendidikan' => 'S1', 'status_nikah' => 'Belum Kawin', 'tgl_masuk' => '2023-01-10', 'status_peg' => 'tetap', 'jabatan' => 'Staff', 'unit' => 'SDM'],
            ['nip' => 'STF003', 'nama' => 'Budi Santoso', 'jk' => 'L', 'tmp_lahir' => 'Bandung', 'tgl_lahir' => '1998-07-22', 'alamat' => 'Jl. Diponegoro No. 5', 'telp' => '081234567802', 'agama' => 'Islam', 'pendidikan' => 'S1', 'status_nikah' => 'Kawin', 'tgl_masuk' => '2022-06-01', 'status_peg' => 'tetap', 'jabatan' => 'Staff Senior', 'unit' => 'IT'],
            ['nip' => 'STF004', 'nama' => 'Citra Dewi', 'jk' => 'P', 'tmp_lahir' => 'Surabaya', 'tgl_lahir' => '2000-11-08', 'alamat' => 'Jl. Sudirman No. 15', 'telp' => '081234567803', 'agama' => 'Hindu', 'pendidikan' => 'S1', 'status_nikah' => 'Belum Kawin', 'tgl_masuk' => '2024-03-15', 'status_peg' => 'kontrak', 'jabatan' => 'Staff', 'unit' => 'KEU'],
            ['nip' => 'MGR002', 'nama' => 'Denny Pratama', 'jk' => 'L', 'tmp_lahir' => 'Medan', 'tgl_lahir' => '1990-05-12', 'alamat' => 'Jl. Gatot Subroto No. 8', 'telp' => '081234567804', 'agama' => 'Kristen', 'pendidikan' => 'S2', 'status_nikah' => 'Kawin', 'tgl_masuk' => '2019-08-20', 'status_peg' => 'tetap', 'jabatan' => 'Manager', 'unit' => 'KEU'],
            ['nip' => 'SPV001', 'nama' => 'Eka Wijaya', 'jk' => 'P', 'tmp_lahir' => 'Yogyakarta', 'tgl_lahir' => '1995-09-30', 'alamat' => 'Jl. Malioboro No. 3', 'telp' => '081234567805', 'agama' => 'Islam', 'pendidikan' => 'S1', 'status_nikah' => 'Kawin', 'tgl_masuk' => '2021-02-01', 'status_peg' => 'tetap', 'jabatan' => 'Supervisor', 'unit' => 'MKT'],
            ['nip' => 'STF005', 'nama' => 'Fajar Hidayat', 'jk' => 'L', 'tmp_lahir' => 'Semarang', 'tgl_lahir' => '2001-01-18', 'alamat' => 'Jl. Pandanaran No. 12', 'telp' => '081234567806', 'agama' => 'Islam', 'pendidikan' => 'D3', 'status_nikah' => 'Belum Kawin', 'tgl_masuk' => '2024-07-01', 'status_peg' => 'magang', 'jabatan' => 'Magang', 'unit' => 'IT'],
            ['nip' => 'STF006', 'nama' => 'Gita Permata', 'jk' => 'P', 'tmp_lahir' => 'Makassar', 'tgl_lahir' => '1997-04-25', 'alamat' => 'Jl. Veteran No. 7', 'telp' => '081234567807', 'agama' => 'Islam', 'pendidikan' => 'S1', 'status_nikah' => 'Kawin', 'tgl_masuk' => '2022-11-15', 'status_peg' => 'tetap', 'jabatan' => 'Staff', 'unit' => 'OPS'],
            ['nip' => 'ADM002', 'nama' => 'Hendra Gunawan', 'jk' => 'L', 'tmp_lahir' => 'Bogor', 'tgl_lahir' => '1996-08-14', 'alamat' => 'Jl. Raya Bogor No. 20', 'telp' => '081234567808', 'agama' => 'Kristen', 'pendidikan' => 'S1', 'status_nikah' => 'Kawin', 'tgl_masuk' => '2020-05-10', 'status_peg' => 'tetap', 'jabatan' => 'Admin', 'unit' => 'UMUM'],
            ['nip' => 'STF007', 'nama' => 'Indah Lestari', 'jk' => 'P', 'tmp_lahir' => 'Malang', 'tgl_lahir' => '1999-12-01', 'alamat' => 'Jl. Ijen No. 4', 'telp' => '081234567809', 'agama' => 'Islam', 'pendidikan' => 'S1', 'status_nikah' => 'Belum Kawin', 'tgl_masuk' => '2023-09-01', 'status_peg' => 'kontrak', 'jabatan' => 'Staff', 'unit' => 'MKT'],
            ['nip' => 'STF008', 'nama' => 'Joko Wibowo', 'jk' => 'L', 'tmp_lahir' => 'Solo', 'tgl_lahir' => '2000-06-19', 'alamat' => 'Jl. Slamet Riyadi No. 9', 'telp' => '081234567810', 'agama' => 'Islam', 'pendidikan' => 'D3', 'status_nikah' => 'Belum Kawin', 'tgl_masuk' => '2024-01-15', 'status_peg' => 'kontrak', 'jabatan' => 'Staff', 'unit' => 'PROD'],
            ['nip' => 'SPV002', 'nama' => 'Kartika Sari', 'jk' => 'P', 'tmp_lahir' => 'Palembang', 'tgl_lahir' => '1994-03-08', 'alamat' => 'Jl. Kolonel Burlian No. 2', 'telp' => '081234567811', 'agama' => 'Islam', 'pendidikan' => 'S2', 'status_nikah' => 'Kawin', 'tgl_masuk' => '2020-10-01', 'status_peg' => 'tetap', 'jabatan' => 'Supervisor', 'unit' => 'PROD'],
        ];

        foreach ($karyawanData as $kd) {
            $jabatan = $jabatanList->firstWhere('nama_jabatan', $kd['jabatan']);
            $unit = $unitList->firstWhere('singkatan', $kd['unit']);
            if (!$jabatan || !$unit) continue;

            $karyawan = Karyawan::create([
                'nip' => $kd['nip'],
                'nama_lengkap' => $kd['nama'],
                'tempat_lahir' => $kd['tmp_lahir'],
                'tanggal_lahir' => $kd['tgl_lahir'],
                'jenis_kelamin' => $kd['jk'],
                'alamat' => $kd['alamat'],
                'no_telepon' => $kd['telp'],
                'email' => strtolower(str_replace(' ', '', $kd['nama'])) . '@hr.com',
                'agama' => $kd['agama'],
                'pendidikan_terakhir' => $kd['pendidikan'],
                'status_perkawinan' => $kd['status_nikah'],
                'tanggal_masuk' => $kd['tgl_masuk'],
                'status_kepegawaian' => $kd['status_peg'],
                'jabatan_id' => $jabatan->id,
                'satuan_kerja_id' => $unit->id,
                'aktif' => true,
            ]);

            User::create([
                'name' => $kd['nama'],
                'email' => strtolower(str_replace(' ', '', $kd['nama'])) . '@hr.com',
                'password' => Hash::make('password'),
                'role' => 'karyawan',
                'karyawan_id' => $karyawan->id,
            ]);
        }

        $semuaKaryawan = Karyawan::all();

        $statuses = ['hadir', 'hadir', 'hadir', 'hadir', 'hadir', 'hadir', 'terlambat', 'terlambat', 'izin', 'sakit'];
        $startDate = Carbon::now()->subDays(30);
        $now = Carbon::now();

        for ($date = $startDate->copy(); $date->lte($now); $date->addDay()) {
            if ($date->isSunday()) continue;
            foreach ($semuaKaryawan as $k) {
                if (rand(1, 20) == 1) continue;
                $status = $statuses[array_rand($statuses)];
                $jamMasuk = Carbon::parse($date->format('Y-m-d') . ' 07:00:00')->addMinutes(rand(0, 120));
                $jamPulang = Carbon::parse($date->format('Y-m-d') . ' 16:00:00')->addMinutes(rand(0, 60));
                $isTerlambat = $jamMasuk->gt(Carbon::parse($date->format('Y-m-d') . ' 08:00:00'));

                if ($isTerlambat && $status == 'hadir') {
                    $status = 'terlambat';
                }

                if (in_array($status, ['izin', 'sakit'])) {
                    Absensi::create([
                        'karyawan_id' => $k->id,
                        'tanggal' => $date->format('Y-m-d'),
                        'status' => $status,
                        'keterangan' => $status == 'sakit' ? 'Sakit' : 'Izin keperluan pribadi',
                    ]);
                } else {
                    Absensi::create([
                        'karyawan_id' => $k->id,
                        'tanggal' => $date->format('Y-m-d'),
                        'jam_masuk' => $jamMasuk->format('H:i:s'),
                        'jam_pulang' => $jamPulang->format('H:i:s'),
                        'status' => $status,
                    ]);
                }
            }
        }

        foreach ($semuaKaryawan->take(5) as $k) {
            $mulai = Carbon::now()->subDays(rand(5, 20));
            $selesai = $mulai->copy()->addDays(rand(1, 3));
            PengajuanIzin::create([
                'karyawan_id' => $k->id,
                'jenis' => collect(['izin', 'sakit', 'cuti'])->random(),
                'tanggal_mulai' => $mulai->format('Y-m-d'),
                'tanggal_selesai' => $selesai->format('Y-m-d'),
                'alasan' => collect(['Keperluan keluarga', 'Kurang enak badan', 'Cuti tahunan', 'Acara pribadi'])->random(),
                'status' => collect(['menunggu', 'disetujui', 'ditolak'])->random(),
                'approved_by' => $atasanUser ? $atasanUser->id : null,
                'catatan_approval' => 'Disetujui',
            ]);
        }

        $judulTugas = [
            'Menyusun laporan bulanan', 'Membuat presentasi project', 'Review dokumen kontrak',
            'Analisis data penjualan', 'Update sistem database', 'Persiapan meeting klien',
            'Audit inventaris', 'Optimasi proses produksi', 'Rekrutmen karyawan baru',
            'Pelatihan penggunaan software baru',
        ];

        foreach ($semuaKaryawan as $k) {
            if ($atasanUser && rand(1, 3) > 1) {
                TugasKaryawan::create([
                    'karyawan_id' => $k->id,
                    'pemberi_tugas' => $atasanUser->id,
                    'judul' => $judulTugas[array_rand($judulTugas)],
                    'deskripsi' => 'Harap dikerjakan dengan baik dan tepat waktu',
                    'tenggat' => Carbon::now()->addDays(rand(2, 30))->format('Y-m-d'),
                    'prioritas' => collect(['rendah', 'sedang', 'tinggi'])->random(),
                    'status' => collect(['diberikan', 'dikerjakan', 'selesai'])->random(),
                ]);
            }
        }

        $atasanId = $atasanUser ? $atasanUser->id : $adminUser->id;
        $getKaryawan = $semuaKaryawan->where('nip', '!=', 'MGR001');
        foreach ($getKaryawan as $k) {
            if (rand(1, 5) > 3) continue;
            $disiplin = round(rand(70, 100) + rand(0, 100) / 100, 2);
            $kualitas = round(rand(70, 100) + rand(0, 100) / 100, 2);
            $kuantitas = round(rand(70, 100) + rand(0, 100) / 100, 2);
            $tanggungJawab = round(rand(70, 100) + rand(0, 100) / 100, 2);
            $kerjasama = round(rand(70, 100) + rand(0, 100) / 100, 2);
            $inisiatif = round(rand(65, 95) + rand(0, 100) / 100, 2);
            $tepatWaktu = round(rand(70, 100) + rand(0, 100) / 100, 2);
            $target = round(rand(70, 100) + rand(0, 100) / 100, 2);
            $nilaiAkhir = round(($disiplin + $kualitas + $kuantitas + $tanggungJawab + $kerjasama + $inisiatif + $tepatWaktu + $target) / 8, 2);

            PenilaianKinerja::create([
                'karyawan_id' => $k->id,
                'penilai_id' => $atasanId,
                'periode' => Carbon::now()->subMonth()->format('Y-m'),
                'tanggal_penilaian' => Carbon::now()->subDays(rand(1, 10))->format('Y-m-d'),
                'nilai_disiplin' => $disiplin,
                'nilai_kualitas' => $kualitas,
                'nilai_kuantitas' => $kuantitas,
                'nilai_tanggung_jawab' => $tanggungJawab,
                'nilai_kerjasama' => $kerjasama,
                'nilai_inisiatif' => $inisiatif,
                'nilai_ketepatan_waktu' => $tepatWaktu,
                'nilai_target' => $target,
                'nilai_akhir' => $nilaiAkhir,
                'catatan' => collect(['Kinerja baik, tingkatkan lagi', 'Hasil kerja memuaskan', 'Perlu peningkatan pada disiplin waktu', 'Kerja tim sudah baik'])->random(),
            ]);
        }

        $tunjanganKomponen = $komponenGaji->where('tipe', 'penghasilan');
        $potonganKomponen = $komponenGaji->where('tipe', 'potongan');

        foreach ($semuaKaryawan as $k) {
            $periode = Carbon::now()->subMonth()->format('Y-m');
            $gajiPokok = $k->jabatan->gaji_pokok ?? 4500000;
            $totalTunjangan = $tunjanganKomponen->sum('nilai');
            $totalPotongan = $potonganKomponen->sum('nilai');

            $hadirBulanIni = Absensi::where('karyawan_id', $k->id)
                ->whereMonth('tanggal', Carbon::now()->subMonth()->month)
                ->whereYear('tanggal', Carbon::now()->subMonth()->year)
                ->whereIn('status', ['hadir', 'terlambat'])
                ->count();

            $terlambatBulanIni = Absensi::where('karyawan_id', $k->id)
                ->whereMonth('tanggal', Carbon::now()->subMonth()->month)
                ->whereYear('tanggal', Carbon::now()->subMonth()->year)
                ->where('status', 'terlambat')
                ->count();

            $dendaTerlambat = $terlambatBulanIni * 25000;
            $totalLembur = rand(0, 10) * 50000;
            $bonus = $k->status_kepegawaian == 'tetap' ? 200000 : 0;
            $insentif = $k->status_kepegawaian == 'tetap' ? 150000 : 0;
            $pajak = round(($gajiPokok + $totalTunjangan) * 0.05, 2);
            $totalDiterima = $gajiPokok + $totalTunjangan + $totalLembur + $bonus + $insentif - $totalPotongan - $dendaTerlambat - $pajak;

            $penggajian = Penggajian::create([
                'karyawan_id' => $k->id,
                'periode' => $periode,
                'tanggal_penggajian' => Carbon::now()->subDays(rand(1, 5))->format('Y-m-d'),
                'gaji_pokok' => $gajiPokok,
                'total_tunjangan' => $totalTunjangan,
                'total_potongan' => $totalPotongan + $dendaTerlambat,
                'total_lembur' => $totalLembur,
                'total_bonus' => $bonus,
                'total_insentif' => $insentif,
                'total_pajak' => $pajak,
                'total_diterima' => $totalDiterima,
                'status' => collect(['draft', 'dikonfirmasi', 'dibayar'])->random(),
                'dibuat_oleh' => $adminUser->id,
            ]);

            foreach ($tunjanganKomponen as $tk) {
                DetailPenggajian::create([
                    'penggajian_id' => $penggajian->id,
                    'komponen_gaji_id' => $tk->id,
                    'nama_komponen' => $tk->nama,
                    'tipe' => 'penghasilan',
                    'nilai' => $tk->nilai,
                ]);
            }

            foreach ($potonganKomponen as $pk) {
                DetailPenggajian::create([
                    'penggajian_id' => $penggajian->id,
                    'komponen_gaji_id' => $pk->id,
                    'nama_komponen' => $pk->nama,
                    'tipe' => 'potongan',
                    'nilai' => $pk->nilai,
                ]);
            }

            if ($dendaTerlambat > 0) {
                DetailPenggajian::create([
                    'penggajian_id' => $penggajian->id,
                    'komponen_gaji_id' => null,
                    'nama_komponen' => 'Denda Keterlambatan',
                    'tipe' => 'potongan',
                    'nilai' => $dendaTerlambat,
                ]);
            }
        }
    }
}
