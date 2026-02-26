<?php

namespace App\Http\Controllers;

use App\Models\Kurir;
use App\Models\Pesanan;
use App\Models\Pelanggan;
use App\Models\Pengantaran;

class DashboardController extends Controller
{
    public function index()
    {
        // Kartu atas
        $totalPesanan = Pesanan::count();
        $totalKurir   = Kurir::count();

        // Pengantaran hari ini (pakai created_at, karena di pengantarans kamu belum ada tanggal khusus)
        $pengantaranHariIni = Pengantaran::whereDate('created_at', today())->count();

        // Status pengantaran (ambil yang sesuai enum di migration pengantarans)
        $statusBerhasil = Pengantaran::where('status', 'berhasil')->count();
        $statusProses   = Pengantaran::where('status', 'dalam_perjalanan')->count(); // "proses" versi pengantaran

        // Tabel pengantaran terbaru (ambil 10 terbaru)
        // sekalian join relasi kurir & pesanan->pelanggan
        $pengantaranTerbaru = Pengantaran::with(['kurir', 'pesanan.pelanggan'])
            ->latest()
            ->take(10)
            ->get();

        return view('pages.dashboard', compact(
            'totalPesanan',
            'totalKurir',
            'pengantaranHariIni',
            'statusBerhasil',
            'statusProses',
            'pengantaranTerbaru'
        ));
    }
}