<?php
namespace Database\Seeders;

use App\Models\SatuanKerja;
use Illuminate\Database\Seeder;

class SatuanKerjaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_unit' => 'Direksi', 'singkatan' => 'DIR'],
            ['nama_unit' => 'Sumber Daya Manusia', 'singkatan' => 'SDM'],
            ['nama_unit' => 'Teknologi Informasi', 'singkatan' => 'IT'],
            ['nama_unit' => 'Keuangan', 'singkatan' => 'KEU'],
            ['nama_unit' => 'Pemasaran', 'singkatan' => 'MKT'],
            ['nama_unit' => 'Operasional', 'singkatan' => 'OPS'],
            ['nama_unit' => 'Produksi', 'singkatan' => 'PROD'],
            ['nama_unit' => 'Umum', 'singkatan' => 'UMUM'],
        ];
        foreach ($data as $d) SatuanKerja::create($d);
    }
}
