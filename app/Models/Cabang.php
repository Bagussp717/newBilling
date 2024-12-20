<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;
    protected $table = 'cabangs';
    protected $primaryKey = 'kd_cabang';
    protected $keyType = 'int';
    protected $fillable = [
        'nm_cabang',
        'alamat_cabang',
        'username_mikrotik',
        'ip_mikrotik',
        'password_mikrotik',
        'kd_isp',
    ];

    public $timestamps = true;
    public $incrementing = true;

    public function isp()
    {
        return $this->belongsTo(ISP::class, 'kd_isp', 'kd_isp');
    }

    public function pelanggans()
    {
        return $this->hasMany(Pelanggan::class, 'kd_cabang');
    }

    public function lokets()
    {
        return $this->hasMany(Loket::class, 'kd_cabang');
    }

    public function teknisis()
    {
        return $this->belongsToMany(Teknisi::class, 'cabang_teknisis', 'kd_cabang', 'kd_teknisi');
    }

    // public function invoices()
    // {
    //     return $this->hasMany(Invoice::class, 'kd_cabang', 'id');
    // }

}
