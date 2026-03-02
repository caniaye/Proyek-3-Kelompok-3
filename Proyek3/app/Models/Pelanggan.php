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

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }

    // ✅ foto upload kalau ada, kalau tidak avatar default berdasarkan ID
    public function fotoUrl(): string
    {
        if (!empty($this->foto)) {
            return asset('storage/' . $this->foto);
        }

        $avatars = [
            'image/default-avatar.png',
            'image/default-avatar2.png',
            'image/default-avatar3.png',
        ];

        // id 1 -> avatar1, id 2 -> avatar2, id 3 -> avatar3, id 4 -> avatar1, dst
        $idx = (($this->id ?? 1) - 1) % count($avatars);

        return asset($avatars[$idx]);
    }
}