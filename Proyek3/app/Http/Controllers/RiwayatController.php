<?php

namespace App\Http\Controllers;

use App\Models\Pengantaran;

class RiwayatController extends Controller
{
    public function index()
    {
        $pengantarans = Pengantaran::with(['kurir', 'pesanan.pelanggan'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.riwayat', compact('pengantarans'));
    }

    public function riwayatKurir($id)
    {
        $pengantarans = Pengantaran::with(['kurir', 'pesanan.pelanggan'])
            ->where('kurir_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $pengantarans->map(function ($item) {
            return [
                'nama_penerima' => $item->pesanan?->pelanggan?->nama ?? '-',
                'alamat' => $item->pesanan?->pelanggan?->alamat ?? '-',
                'status' => $item->status ?? '-',
                'tanggal' => $item->created_at
                    ? $item->created_at->format('d M Y')
                    : '-',
            ];
        });

        return response()->json([
            'data' => $data
        ]);
    }
}