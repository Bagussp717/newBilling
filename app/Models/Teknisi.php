<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teknisi extends Model
{
    use HasFactory;

    protected $table = 'teknisis';
    protected $primaryKey = 'kd_teknisi';
    protected $keyType = 'int';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'kd_isp',
        'kd_user',
        'nm_teknisi',
        't_lahir',
        'tgl_lahir',
        'tgl_aktif',
        'alamat_teknisi',
        'no_telp',
    ];

    // Relasi ke Cabang (Cabang hasMany Teknisi)
    public function cabangs()
    {
        return $this->belongsToMany(Cabang::class, 'cabang_teknisis', 'kd_teknisi', 'kd_cabang');
    }

    // Relasi ke User (User hasMany Teknisi)
    public function user()
    {
        return $this->belongsTo(User::class, 'kd_user', 'id');
    }

    public function isp()
    {
        return $this->belongsTo(ISP::class, 'kd_isp', 'kd_isp');
    }
}
