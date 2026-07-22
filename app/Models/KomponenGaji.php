<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomponenGaji extends Model
{
    protected $table = 'komponen_gaji';
    protected $fillable = ['kode', 'nama', 'tipe', 'sifat', 'nilai', 'keterangan', 'aktif'];

    protected function casts(): array
    {
        return ['aktif' => 'boolean', 'nilai' => 'decimal:2'];
    }

    public function detailPenggajian()
    {
        return $this->hasMany(DetailPenggajian::class);
    }
}
