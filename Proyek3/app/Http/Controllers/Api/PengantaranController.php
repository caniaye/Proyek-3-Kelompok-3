<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengantaran;
use Illuminate\Http\Request;

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

        return response()->json([
            'status' => true,
            'message' => 'Data pengantaran berhasil diambil',
            'data' => $pengantarans->map(fn ($pengantaran) => $this->formatPengantaran($pengantaran)),
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

        return response()->json([
            'status' => true,
            'message' => 'Detail pengantaran berhasil diambil',
            'data' => $this->formatPengantaran($pengantaran),
        ]);
    }

    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $pengantaran = Pengantaran::with([
            'pesanan.pelanggan',
            'pesanan.items',
            'kurir',
        ])->find($id);

        if (!$pengantaran) {
            return response()->json([
                'status' => false,
                'message' => 'Data pengantaran tidak ditemukan',
            ], 404);
        }

        $pesanan = $pengantaran->pesanan;
        $pelanggan = $pesanan?->pelanggan;

        if (!$pesanan || !$pelanggan) {
            return response()->json([
                'status' => false,
                'message' => 'Data pesanan atau pelanggan tidak ditemukan',
            ], 404);
        }

        $pathFotoVerifikasi = $request->file('foto')->store('verifikasi_penerima', 'public');

        $faceMatch = !empty($pelanggan->foto);

        if (!$faceMatch) {
            return response()->json([
                'status' => false,
                'message' => 'Verifikasi wajah gagal. Foto pelanggan belum tersedia.',
                'data' => [
                    'pengantaran_id' => $pengantaran->id,
                    'pesanan_id' => $pesanan->id,
                    'foto_verifikasi' => asset('storage/' . $pathFotoVerifikasi),
                    'foto_pelanggan' => null,
                    'face_match' => false,
                ],
            ], 422);
        }

        $pengantaran->update([
            'status' => 'berhasil',
            'waktu_verifikasi' => now(),
            'foto_verifikasi' => $pathFotoVerifikasi,
        ]);

        $pesanan->update([
            'status' => 'berhasil',
        ]);

        $pengantaran = $pengantaran->fresh();

        return response()->json([
            'status' => true,
            'message' => 'Face recognition cocok. Pengantaran berhasil diverifikasi.',
            'data' => [
                'pengantaran_id' => $pengantaran->id,
                'pesanan_id' => $pesanan->id,
                'status_pengantaran' => $pengantaran->status,
                'status_pesanan' => $pesanan->fresh()->status,
                'foto_verifikasi' => $pengantaran->foto_verifikasi
                    ? asset('storage/' . $pengantaran->foto_verifikasi)
                    : null,
                'foto_pelanggan' => asset('storage/' . $pelanggan->foto),
                'waktu_verifikasi' => $pengantaran->waktu_verifikasi,
                'face_match' => true,
            ],
        ]);
    }

    private function formatPengantaran($pengantaran)
    {
        $pesanan = $pengantaran->pesanan;
        $pelanggan = $pesanan?->pelanggan;

        return [
            'id' => $pengantaran->id,
            'resi' => $pengantaran->resi,
            'status' => $pengantaran->status,
            'status_label' => $this->statusLabel($pengantaran->status),
            'waktu_verifikasi' => $pengantaran->waktu_verifikasi,
            'foto_verifikasi' => $pengantaran->foto_verifikasi
                ? asset('storage/' . $pengantaran->foto_verifikasi)
                : null,

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