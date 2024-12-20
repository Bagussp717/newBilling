<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggans';
    protected $primaryKey = 'kd_pelanggan';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'kd_user',
        'tgl_pemasangan',
        'nm_pelanggan',
        'jenis_identitas',
        'no_identitas',
        'nm_pelanggan',
        't_lahir',
        'tgl_lahir',
        'pekerjaan',
        'alamat',
        'no_telp',
        'kd_cabang',
        'kd_paket',
        'kd_loket',
        'kd_isp',
        'kd_odp',
        'lat',
        'long',
        'foto_rumah',
        'username_pppoe',
        'password_pppoe',
        'service_pppoe',
        'profile_pppoe',
    ];

    // protected $casts = [
    //     'tgl_lahir' => 'date',
    // ];

    // public function getTempatTanggalLahirAttribute()
    // {
    //     $tanggalLahir = $this->tgl_lahir ? $this->tgl_lahir->format('d-m-Y') : '';
    //     return $this->t_lahir . ', ' . $tanggalLahir;
    // }

    protected $dates = [
        'tgl_pemasangan',
        'tgl_lahir',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kd_cabang');
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'kd_paket');
    }

    public function loket()
    {
        return $this->belongsTo(Loket::class, 'kd_loket');
    }

    public function invoice()
    {
        return $this->hasMany(Invoice::class, 'kd_pelanggan', 'kd_pelanggan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'kd_user', 'id');
    }


    // relasi antara pelanggan dengan pembayaran melalui invoice
    public function pembayaran()
    {
        return $this->hasManyThrough(Pembayaran::class, Invoice::class, 'kd_pelanggan', 'kd_invoice', 'kd_pelanggan', 'kd_invoice');
    }

    public function isp()
    {
        return $this->belongsTo(ISP::class, 'kd_isp');
    }

    public function odp()
    {
        return $this->belongsTo(ODP::class, 'kd_odp');
    }

}
