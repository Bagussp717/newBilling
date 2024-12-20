<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';
    protected $primaryKey = 'kd_invoice';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;


    // Tentukan kolom yang dapat diisi secara massal
    protected $fillable = [
        'kd_pelanggan',
        'kd_isp',
        'tgl_invoice',
        'tgl_akhir',
        'status_pppoe',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'kd_pelanggan');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'kd_invoice');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kd_cabang', 'id');
    }

    public function isp()
    {
        return $this->belongsTo(ISP::class, 'kd_isp');
    }


}
