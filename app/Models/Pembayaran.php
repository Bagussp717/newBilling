<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';
    protected $primaryKey = 'kd_pembayaran';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'kd_invoice',
        'jml_bayar',
        'tgl_bayar',
        'kd_loket',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class,  'kd_invoice');
    }

    public function loket()
    {
        return $this->belongsTo(Loket::class, 'kd_loket');
    }
}
