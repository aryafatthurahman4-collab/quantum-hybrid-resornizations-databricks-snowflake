<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            JabatanSeeder::class,
            SatuanKerjaSeeder::class,
            KomponenGajiSeeder::class,
            UserSeeder::class,
            DemoDataSeeder::class,
        ]);
    }
}
