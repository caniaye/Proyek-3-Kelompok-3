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
        'status',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function pengantaran()
    {
        return $this->hasOne(Pengantaran::class);
    }

    public function items()
    {
        return $this->hasMany(PesananItem::class);
    }

    public function qty3kg(): int
    {
        return (int) ($this->items()->where('jenis_tabung', '3kg')->value('qty') ?: 0);
    }

    public function qty12kg(): int
    {
        return (int) ($this->items()->where('jenis_tabung', '12kg')->value('qty') ?: 0);
    }
}