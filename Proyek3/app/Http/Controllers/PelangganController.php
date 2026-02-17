<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::orderBy('nama', 'asc')->get();
        return view('pages.pelanggan', compact('pelanggans'));
    }
}
