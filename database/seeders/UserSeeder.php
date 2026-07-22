<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\SatuanKerja;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminJabatan = Jabatan::where('nama_jabatan', 'Administrator')->first();
        if (!$adminJabatan) {
            $adminJabatan = Jabatan::create(['nama_jabatan' => 'Administrator', 'level' => 'Staff', 'gaji_pokok' => 5000000]);
        }

        $itUnit = SatuanKerja::where('singkatan', 'IT')->first();
        if (!$itUnit) {
            $itUnit = SatuanKerja::create(['nama_unit' => 'Teknologi Informasi', 'singkatan' => 'IT']);
        }

        $adminKaryawan = Karyawan::updateOrCreate(
            ['nip' => 'ADM001'],
            [
                'nama_lengkap' => 'Administrator',
                'tanggal_masuk' => now(),
                'jabatan_id' => $adminJabatan->id,
                'satuan_kerja_id' => $itUnit->id,
                'aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@hr.com'],
            [
                'name' => 'Admin HRIS',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'karyawan_id' => $adminKaryawan->id,
            ]
        );

        $atasanJabatan = Jabatan::where('nama_jabatan', 'Manager')->first() ?? $adminJabatan;
        $atasanKaryawan = Karyawan::updateOrCreate(
            ['nip' => 'MGR001'],
            [
                'nama_lengkap' => 'Manager HRD',
                'tanggal_masuk' => now(),
                'jabatan_id' => $atasanJabatan->id,
                'satuan_kerja_id' => $itUnit->id,
                'aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'atasan@hr.com'],
            [
                'name' => 'Manager HRD',
                'password' => Hash::make('password'),
                'role' => 'atasan',
                'karyawan_id' => $atasanKaryawan->id,
            ]
        );

        $karyawanJabatan = Jabatan::where('nama_jabatan', 'Staff')->first() ?? $adminJabatan;
        $staffKaryawan = Karyawan::updateOrCreate(
            ['nip' => 'STF001'],
            [
                'nama_lengkap' => 'Karyawan Staff',
                'tanggal_masuk' => now(),
                'jabatan_id' => $karyawanJabatan->id,
                'satuan_kerja_id' => $itUnit->id,
                'aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'karyawan@hr.com'],
            [
                'name' => 'Karyawan Staff',
                'password' => Hash::make('password'),
                'role' => 'karyawan',
                'karyawan_id' => $staffKaryawan->id,
            ]
        );
    }
}
