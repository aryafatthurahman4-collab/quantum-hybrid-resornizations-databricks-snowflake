<?php
namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_jabatan' => 'Direktur', 'level' => 'Direksi', 'gaji_pokok' => 15000000],
            ['nama_jabatan' => 'Manager', 'level' => 'Manager', 'gaji_pokok' => 8000000],
            ['nama_jabatan' => 'Supervisor', 'level' => 'Supervisor', 'gaji_pokok' => 6000000],
            ['nama_jabatan' => 'Staff Senior', 'level' => 'Staff', 'gaji_pokok' => 5000000],
            ['nama_jabatan' => 'Staff', 'level' => 'Staff', 'gaji_pokok' => 4500000],
            ['nama_jabatan' => 'Admin', 'level' => 'Staff', 'gaji_pokok' => 4200000],
            ['nama_jabatan' => 'Magang', 'level' => 'Magang', 'gaji_pokok' => 2500000],
        ];
        foreach ($data as $d) Jabatan::create($d);
    }
}
