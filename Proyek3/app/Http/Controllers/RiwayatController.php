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
        $pengantarans = Pengantaran::with([
                'kurir',
                'pesanan.pelanggan',
                'pesanan.items',
            ])
            ->where('kurir_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $pengantarans->map(function ($item) {
            $pesanan = $item->pesanan;
            $pelanggan = $pesanan?->pelanggan;

            return [
                'id' => $item->id,
                'resi' => $item->resi,
                'nama_penerima' => $pelanggan?->nama ?? '-',
                'alamat' => $pelanggan?->alamat ?? '-',
                'no_hp' => $pelanggan?->no_hp ?? '-',
                'status' => $item->status ?? '-',
                'tanggal' => $item->created_at
                    ? $item->created_at->format('d M Y')
                    : '-',
                'waktu_verifikasi' => $item->waktu_verifikasi
                    ? \Carbon\Carbon::parse($item->waktu_verifikasi)->format('d M Y, H:i')
                    : '-',
                'foto_verifikasi' => $item->foto_verifikasi
                    ? asset('storage/' . $item->foto_verifikasi)
                    : null,
                'pesanan' => [
                    'id' => $pesanan?->id,
                    'kode' => $pesanan?->kode,
                    'jumlah_tabung' => $pesanan?->jumlah_tabung,
                    'status' => $pesanan?->status,
                ],
                'items' => $pesanan?->items->map(function ($itemPesanan) {
                    return [
                        'id' => $itemPesanan->id,
                        'jenis_tabung' => $itemPesanan->jenis_tabung,
                        'qty' => $itemPesanan->qty,
                        'nama' => $itemPesanan->qty . ' X GAS ' . strtoupper($itemPesanan->jenis_tabung),
                    ];
                })->values() ?? [],
            ];
        });

        return response()->json([
            'data' => $data
        ]);
    }
}