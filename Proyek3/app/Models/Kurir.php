<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kurir extends Model
{
    use HasFactory;

    protected $table = 'kurirs';

    protected $fillable = [
        'kode',
        'nama',
        'status', // aktif / nonaktif
    ];

    public function pengantarans()
    {
        return $this->hasMany(Pengantaran::class);
    }
}