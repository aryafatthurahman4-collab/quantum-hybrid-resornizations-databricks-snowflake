<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    protected $table = 'penggajian';
    protected $fillable = [
        'karyawan_id', 'periode', 'tanggal_penggajian',
        'gaji_pokok', 'total_tunjangan', 'total_potongan',
        'total_lembur', 'total_bonus', 'total_insentif',
        'total_pajak', 'total_diterima', 'status', 'dibuat_oleh'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function detail()
    {
        return $this->hasMany(DetailPenggajian::class);
    }
}
