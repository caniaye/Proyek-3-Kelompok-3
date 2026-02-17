<?php

namespace App\Http\Controllers;

use App\Models\Pengantaran;

class MonitoringController extends Controller
{
    public function index()
    {
        $pengantarans = Pengantaran::with(['kurir', 'pesanan.pelanggan'])
            // biasanya monitoring fokus yang belum selesai
            ->orderByRaw("FIELD(status,'dalam_perjalanan','belum_dikirim','berhasil')")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.monitoring', compact('pengantarans'));
    }
}
