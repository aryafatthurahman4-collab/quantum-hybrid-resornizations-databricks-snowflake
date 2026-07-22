<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'role', 'karyawan_id'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isAtasan()
    {
        return $this->role === 'atasan';
    }

    public function isKaryawan()
    {
        return $this->role === 'karyawan';
    }

    public function penilaianDibuat()
    {
        return $this->hasMany(PenilaianKinerja::class, 'penilai_id');
    }

    public function tugasDiberikan()
    {
        return $this->hasMany(TugasKaryawan::class, 'pemberi_tugas');
    }
}
