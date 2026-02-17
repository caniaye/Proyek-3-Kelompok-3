<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanans';

    protected $fillable = [
        'kode',
        'pelanggan_id',
        'jumlah_tabung',
        'tanggal_pesan',
        'status', // belum_dikirim / proses / berhasil
    ];

    // 1 pesanan milik 1 pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    // 1 pesanan punya 1 pengantaran
    public function pengantaran()
    {
        return $this->hasOne(Pengantaran::class);
    }
}
