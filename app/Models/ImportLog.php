<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    protected $table = 'import_logs';
    protected $fillable = ['user_id', 'tipe_import', 'nama_file', 'total_baris', 'berhasil', 'gagal', 'error_message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
