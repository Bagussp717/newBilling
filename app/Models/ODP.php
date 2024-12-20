<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ODP extends Model
{
    use HasFactory;

    protected $table = 'odps';
    protected $primaryKey = 'kd_odp';
    public $timestamps = true;
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'kd_cabang',
        'nm_odp',
        'lat',
        'long',
        'foto_odp',
    ];


    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kd_cabang', 'kd_cabang');
    }

    public function isp()
    {
        return $this->belongsTo(ISP::class, 'kd_isp');
    }

    public function pelanggan()
    {
        return $this->hasOne(Pelanggan::class, 'kd_odp');
    }
}

