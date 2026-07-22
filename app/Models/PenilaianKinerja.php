<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianKinerja extends Model
{
    protected $table = 'penilaian_kinerja';
    protected $fillable = [
        'karyawan_id', 'penilai_id', 'periode', 'tanggal_penilaian',
        'nilai_disiplin', 'nilai_kualitas', 'nilai_kuantitas',
        'nilai_tanggung_jawab', 'nilai_kerjasama', 'nilai_inisiatif',
        'nilai_ketepatan_waktu', 'nilai_target', 'nilai_akhir', 'catatan'
    ];

    protected function casts(): array
    {
        return ['tanggal_penilaian' => 'date'];
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function penilai()
    {
        return $this->belongsTo(User::class, 'penilai_id');
    }
}
