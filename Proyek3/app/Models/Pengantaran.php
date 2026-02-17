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
        'kurir_id',
        'status', // belum_dikirim / dalam_perjalanan / berhasil
        'waktu_verifikasi',
    ];

    // 1 pengantaran milik 1 pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    // 1 pengantaran milik 1 kurir
    public function kurir()
    {
        return $this->belongsTo(Kurir::class);
    }
}
