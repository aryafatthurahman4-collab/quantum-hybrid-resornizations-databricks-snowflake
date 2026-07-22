<?php
namespace Database\Seeders;

use App\Models\KomponenGaji;
use Illuminate\Database\Seeder;

class KomponenGajiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode' => 'TUNJ-JAB', 'nama' => 'Tunjangan Jabatan', 'tipe' => 'penghasilan', 'sifat' => 'tetap', 'nilai' => 500000],
            ['kode' => 'TUNJ-TRANS', 'nama' => 'Tunjangan Transportasi', 'tipe' => 'penghasilan', 'sifat' => 'tetap', 'nilai' => 300000],
            ['kode' => 'TUNJ-MAKAN', 'nama' => 'Uang Makan', 'tipe' => 'penghasilan', 'sifat' => 'tetap', 'nilai' => 350000],
            ['kode' => 'TUNJ-KESEHATAN', 'nama' => 'Tunjangan Kesehatan', 'tipe' => 'penghasilan', 'sifat' => 'tetap', 'nilai' => 200000],
            ['kode' => 'BPJS', 'nama' => 'BPJS Ketenagakerjaan', 'tipe' => 'potongan', 'sifat' => 'tetap', 'nilai' => 150000],
            ['kode' => 'BPJS-KES', 'nama' => 'BPJS Kesehatan', 'tipe' => 'potongan', 'sifat' => 'tetap', 'nilai' => 100000],
        ];

        foreach ($data as $d) {
            KomponenGaji::updateOrCreate(
                ['kode' => $d['kode']],
                [
                    'nama' => $d['nama'],
                    'tipe' => $d['tipe'],
                    'sifat' => $d['sifat'],
                    'nilai' => $d['nilai'],
                    'aktif' => true,
                ]
            );
        }
    }
}
