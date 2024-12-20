<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    protected $table = 'pakets';
    protected $primaryKey = 'kd_paket';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'kd_paket',
        'nm_paket',
        'hrg_paket',
        'kd_cabang',
        'local_address',
        'remote_address',
        'rate_limit',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kd_cabang', 'kd_cabang');
    }

    public function pelanggans()
    {
        return $this->hasMany(Pelanggan::class, 'kd_paket', 'kd_paket');
    }

    public function isp()
    {
        return $this->belongsTo(ISP::class, 'kd_isp');
    }

}
