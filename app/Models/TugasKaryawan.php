<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TugasKaryawan extends Model
{
    protected $table = 'tugas_karyawan';
    protected $fillable = [
        'karyawan_id', 'pemberi_tugas', 'judul', 'deskripsi',
        'tenggat', 'prioritas', 'status', 'catatan_penyelesaian'
    ];

    protected function casts(): array
    {
        return ['tenggat' => 'date'];
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function pemberi()
    {
        return $this->belongsTo(User::class, 'pemberi_tugas');
    }
}
