<?php

namespace App\Http\Controllers;

use App\Models\Pengantaran;

class RiwayatController extends Controller
{
    public function index()
    {
        // riwayat fokus yang selesai (berhasil), tapi boleh tampilkan semua
        $pengantarans = Pengantaran::with(['kurir', 'pesanan.pelanggan'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.riwayat', compact('pengantarans'));
    }
}
