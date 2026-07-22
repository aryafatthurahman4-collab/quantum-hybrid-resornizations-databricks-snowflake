<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenggajian extends Model
{
    protected $table = 'detail_penggajian';
    protected $fillable = ['penggajian_id', 'komponen_gaji_id', 'nama_komponen', 'tipe', 'nilai'];

    public function penggajian()
    {
        return $this->belongsTo(Penggajian::class);
    }

    public function komponenGaji()
    {
        return $this->belongsTo(KomponenGaji::class);
    }
}
