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
        'foto',
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

    /**
     * URL foto pelanggan:
     * - kalau ada foto upload -> /storage/...
     * - kalau belum ada -> default avatar beda-beda berdasarkan id
     */
    public function fotoUrl(): string
    {
        // Kalau sudah ada foto upload, pakai itu
        if (!empty($this->foto)) {
            return asset('storage/' . $this->foto);
        }

        // Kalau belum ada foto, pakai default yang beda-beda (muter)
        $avatars = [
            'image/default-avatar.png',
            'image/default-avatar2.png',
            'image/default-avatar3.png',
        ];

        // index 0..2 berdasarkan id
        $idx = (($this->id ?? 1) - 1) % count($avatars);

        return asset($avatars[$idx]);
    }
}