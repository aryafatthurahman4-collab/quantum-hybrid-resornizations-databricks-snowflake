<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatuanKerja extends Model
{
    protected $table = 'satuan_kerja';
    protected $fillable = ['nama_unit', 'singkatan', 'keterangan'];

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }
}
