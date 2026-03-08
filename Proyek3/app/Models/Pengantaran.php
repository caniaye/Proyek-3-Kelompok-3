<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengantaran extends Model
{
    use HasFactory;

    protected $table = 'pengantarans';

    protected $fillable = [
        'resi',
        'pesanan_id',
        'kurir_id', // nullable
        'status',   // belum_dikirim / dalam_perjalanan / berhasil / dibatalkan
        'waktu_verifikasi',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function kurir()
    {
        return $this->belongsTo(Kurir::class);
    }
}