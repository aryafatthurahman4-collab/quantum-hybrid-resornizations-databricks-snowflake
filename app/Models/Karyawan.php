<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawan';
    protected $fillable = [
        'nip', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir',
        'jenis_kelamin', 'alamat', 'no_telepon', 'email', 'agama',
        'pendidikan_terakhir', 'status_perkawinan', 'tanggal_masuk',
        'status_kepegawaian', 'jabatan_id', 'satuan_kerja_id', 'foto', 'aktif'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'aktif' => 'boolean',
    ];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function satuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function pengajuanIzin()
    {
        return $this->hasMany(PengajuanIzin::class);
    }

    public function tugas()
    {
        return $this->hasMany(TugasKaryawan::class);
    }

    public function penilaianKinerja()
    {
        return $this->hasMany(PenilaianKinerja::class);
    }

    public function penggajian()
    {
        return $this->hasMany(Penggajian::class);
    }
}
