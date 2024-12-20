<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    use HasFactory;

    protected $table = 'tikets';
    protected $primaryKey = 'kd_tiket';

    protected $fillable = [
        'kd_user',
        'kd_pelanggan',
        'deskripsi_tiket',
        'foto',
        'status_tiket',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'kd_user');
    }

    // Relasi ke Pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'kd_pelanggan');
    }

    // Relasi ke Teknisi
    public function teknisi()
    {
        return $this->belongsTo(Teknisi::class, 'kd_teknisi');
    }

    public function isp()
    {
        return $this->belongsTo(ISP::class, 'kd_isp');
    }
}
