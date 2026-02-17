<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggans';

    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
    ];

    /**
     * Relasi ke Pesanan
     * 1 Pelanggan bisa punya banyak Pesanan
     */
    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }
}
