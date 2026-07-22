<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatan';
    protected $fillable = ['nama_jabatan', 'level', 'gaji_pokok'];

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }
}
