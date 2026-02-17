<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;

class PesananController extends Controller
{
    public function index()
    {
        // ambil pesanan + relasi pelanggan biar gak N+1
        $pesanans = Pesanan::with('pelanggan')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.pesanan', compact('pesanans'));
    }
}
