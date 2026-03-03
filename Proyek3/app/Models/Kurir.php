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
        'foto',
        'status', // aktif / nonaktif / resign
    ];

    public function pengantarans()
    {
        return $this->hasMany(Pengantaran::class);
    }

    public function fotoUrl(): string
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        return asset('image/default-avatar.png'); // kamu sudah punya di public/image
    }
}