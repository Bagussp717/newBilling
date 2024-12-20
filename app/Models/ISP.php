<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ISP extends Model
{
    use HasFactory;

    protected $table = 'i_s_p_s';
    protected $primaryKey = 'kd_isp';
    protected $keyType = 'int';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'kd_user',
        'nm_isp',
        'nm_brand',
        'alamat',
        'no_telp',
        'logo',
    ];

    public function cabangs()
    {
        return $this->hasMany(Cabang::class, 'kd_isp');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'kd_user', 'id');
    }

    public function pelanggans()
    {
        return $this->hasMany(Pelanggan::class, 'kd_isp');
    }

    public function pakets()
    {
        return $this->hasMany(Paket::class, 'kd_isp');
    }

    public function odps()
    {
        return $this->hasMany(Odp::class, 'kd_isp');
    }

    public function lokets()
    {
        return $this->hasMany(Loket::class, 'kd_isp');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'kd_isp');
    }

    public function tikets()
    {
        return $this->hasMany(Tiket::class, 'kd_isp');
    }

    public function teknisis()
    {
        return $this->hasMany(Teknisi::class, 'kd_isp');
    }
}
