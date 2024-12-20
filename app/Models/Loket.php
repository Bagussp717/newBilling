<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loket extends Model
{
    protected $table = 'lokets';
    protected $primaryKey = 'kd_loket';
    protected $keyType = 'int';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'kd_user',
        'kd_isp',
        'kd_cabang',
        'nm_loket',
        'alamat_loket',
        'jenis_komisi',
        'jml_komisi',
    ];

    // Relasi ke User (Loket belongs to User)
    // Relasi ke Cabang (Loket belongs to Cabang)
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kd_cabang');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'kd_loket');
    }

    public function pelanggans()
    {
        return $this->hasMany(Pelanggan::class, 'kd_cabang');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'kd_loket');
    }

    // Relasi Loket ke tabel invoice melalui pelanggan
    public function invoice()
    {
        return $this->hasManyThrough(Invoice::class, Pelanggan::class, 'kd_loket', 'kd_pelanggan', 'kd_loket', 'kd_pelanggan');
    }

    public function isp()
    {
        return $this->belongsTo(ISP::class, 'kd_isp');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'kd_user', 'id');
    }
}
