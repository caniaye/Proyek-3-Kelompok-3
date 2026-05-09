<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengantaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengantaranController extends Controller
{
    public function list($id)
    {
        $pengantarans = Pengantaran::with([
                'pesanan.pelanggan',
                'pesanan.items',
                'kurir',
            ])
            ->where('kurir_id', $id)
            ->whereIn('status', ['belum_dikirim', 'dalam_perjalanan'])
            ->orderBy('id', 'desc')
            ->get();

        $data = $pengantarans->map(function ($pengantaran) {
            $pesanan = $pengantaran->pesanan;
            $pelanggan = $pesanan?->pelanggan;

            return [
                'id' => $pengantaran->id,
                'resi' => $pengantaran->resi,
                'status' => $pengantaran->status,
                'status_label' => $this->statusLabel($pengantaran->status),

                'pesanan' => [
                    'id' => $pesanan?->id,
                    'kode' => $pesanan?->kode,
                    'jumlah_tabung' => $pesanan?->jumlah_tabung,
                    'tanggal_pesan' => $pesanan?->tanggal_pesan,
                    'status' => $pesanan?->status,
                ],

                'pelanggan' => [
                    'id' => $pelanggan?->id,
                    'nama' => $pelanggan?->nama,
                    'alamat' => $pelanggan?->alamat,
                    'no_hp' => $pelanggan?->no_hp,
                    'foto' => $pelanggan?->foto
                        ? asset('storage/' . $pelanggan->foto)
                        : null,
                ],

                'items' => $pesanan?->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'jenis_tabung' => $item->jenis_tabung,
                        'qty' => $item->qty,
                        'nama' => $item->qty . ' X GAS ' . strtoupper($item->jenis_tabung),
                    ];
                })->values() ?? [],
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Data pengantaran berhasil diambil',
            'data' => $data,
        ]);
    }

    public function detail($id)
    {
        $pengantaran = Pengantaran::with([
                'pesanan.pelanggan',
                'pesanan.items',
                'kurir',
            ])
            ->find($id);

        if (!$pengantaran) {
            return response()->json([
                'status' => false,
                'message' => 'Data pengantaran tidak ditemukan',
            ], 404);
        }

        $pesanan = $pengantaran->pesanan;
        $pelanggan = $pesanan?->pelanggan;

        return response()->json([
            'status' => true,
            'message' => 'Detail pengantaran berhasil diambil',
            'data' => [
                'id' => $pengantaran->id,
                'resi' => $pengantaran->resi,
                'status' => $pengantaran->status,
                'status_label' => $this->statusLabel($pengantaran->status),
                'waktu_verifikasi' => $pengantaran->waktu_verifikasi,

                'pesanan' => [
                    'id' => $pesanan?->id,
                    'kode' => $pesanan?->kode,
                    'jumlah_tabung' => $pesanan?->jumlah_tabung,
                    'tanggal_pesan' => $pesanan?->tanggal_pesan,
                    'status' => $pesanan?->status,
                ],

                'pelanggan' => [
                    'id' => $pelanggan?->id,
                    'nama' => $pelanggan?->nama,
                    'alamat' => $pelanggan?->alamat,
                    'no_hp' => $pelanggan?->no_hp,
                    'foto' => $pelanggan?->foto
                        ? asset('storage/' . $pelanggan->foto)
                        : null,
                ],

                'items' => $pesanan?->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'jenis_tabung' => $item->jenis_tabung,
                        'qty' => $item->qty,
                        'nama' => $item->qty . ' X GAS ' . strtoupper($item->jenis_tabung),
                    ];
                })->values() ?? [],
            ],
        ]);
    }

    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $pengantaran = Pengantaran::with('pesanan.pelanggan')->find($id);

        if (!$pengantaran) {
            return response()->json([
                'status' => false,
                'message' => 'Data pengantaran tidak ditemukan',
            ], 404);
        }

        $pesanan = $pengantaran->pesanan;

        if (!$pesanan) {
            return response()->json([
                'status' => false,
                'message' => 'Data pesanan tidak ditemukan',
            ], 404);
        }

        $pathFoto = $request->file('foto')->store('verifikasi_penerima', 'public');

        /*
         * SEMENTARA:
         * Ini belum face recognition asli.
         * Untuk tahap ini foto disimpan dan status langsung berhasil.
         * Nanti bagian ini diganti dengan AI face recognition.
         */
        $pengantaran->update([
            'status' => 'berhasil',
            'waktu_verifikasi' => now(),
        ]);

        $pesanan->update([
            'status' => 'berhasil',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Pengantaran berhasil diverifikasi',
            'data' => [
                'pengantaran_id' => $pengantaran->id,
                'pesanan_id' => $pesanan->id,
                'status_pengantaran' => $pengantaran->fresh()->status,
                'status_pesanan' => $pesanan->fresh()->status,
                'foto_verifikasi' => asset('storage/' . $pathFoto),
                'waktu_verifikasi' => $pengantaran->fresh()->waktu_verifikasi,
                'face_match' => true,
            ],
        ]);
    }

    private function statusLabel($status)
    {
        return match ($status) {
            'belum_dikirim' => 'Belum Dikirim',
            'dalam_perjalanan' => 'Dalam Perjalanan',
            'berhasil' => 'Berhasil',
            'dibatalkan' => 'Dibatalkan',
            default => $status,
        };
    }
}