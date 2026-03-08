<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananItem extends Model
{
    use HasFactory;

    protected $table = 'pesanan_items';

    protected $fillable = [
        'pesanan_id',
        'jenis_tabung', // '3kg' / '12kg'
        'qty',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}