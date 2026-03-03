<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\PesananItem;
use App\Models\Pengantaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index()
    {
        $pesanans = Pesanan::with(['pelanggan', 'items'])
            ->orderBy('created_at', 'desc')
            ->get();

        $pelanggans = Pelanggan::orderBy('id', 'asc')->get();

        return view('pages.pesanan', compact('pesanans', 'pelanggans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id'  => 'required|exists:pelanggans,id',
            'qty_3kg'       => 'nullable|integer|min:0|max:100',
            'qty_12kg'      => 'nullable|integer|min:0|max:100',
            'tanggal_pesan' => 'required|date|after_or_equal:today',
        ]);

        $qty3  = (int) ($request->qty_3kg ?? 0);
        $qty12 = (int) ($request->qty_12kg ?? 0);

        if (($qty3 + $qty12) <= 0) {
            return back()->withErrors(['qty_3kg' => 'Minimal pesan 1 tabung (3kg atau 12kg).'])->withInput();
        }

        // batas maksimal tanggal = 1 bulan dari hari ini
        $maxDate = now()->addMonth()->toDateString();
        if ($request->tanggal_pesan > $maxDate) {
            return back()->withErrors(['tanggal_pesan' => 'Tanggal pesan maksimal 1 bulan dari hari ini.'])->withInput();
        }

        DB::transaction(function () use ($request, $qty3, $qty12) {

            // kode otomatis: P001, P002, dst
            $lastKode = Pesanan::orderByRaw("CAST(SUBSTRING(kode, 2) AS UNSIGNED) DESC")->value('kode');
            $lastNumber = $lastKode ? (int) substr($lastKode, 1) : 0;
            $newKode = 'P' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

            $pesanan = Pesanan::create([
                'kode'          => $newKode,
                'pelanggan_id'  => $request->pelanggan_id,
                'jumlah_tabung' => $qty3 + $qty12,
                'tanggal_pesan' => $request->tanggal_pesan,
                'status'        => 'belum_dikirim',
            ]);

            // simpan item 3kg
            if ($qty3 > 0) {
                PesananItem::create([
                    'pesanan_id'   => $pesanan->id,
                    'jenis_tabung' => '3kg',
                    'qty'          => $qty3,
                ]);
            }

            // simpan item 12kg
            if ($qty12 > 0) {
                PesananItem::create([
                    'pesanan_id'   => $pesanan->id,
                    'jenis_tabung' => '12kg',
                    'qty'          => $qty12,
                ]);
            }

            // OPTIONAL: kalau kamu mau langsung buat record pengantaran saat pesanan dibuat
            // Kalau belum mau assign kurir sekarang, bisa set kurir_id nullable (tapi migration kamu sekarang kurir_id wajib)
            // Jadi untuk sekarang: TUNGGU monitoring assign kurir -> nanti kita atur di tahap monitoring.
        });

        return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil ditambahkan!');
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['pelanggan', 'items', 'pengantaran.kurir']);
        return view('pages.pesanan_detail', compact('pesanan'));
    }

    // ADMIN BATALKAN PESANAN (tanpa hapus)
    public function cancel(Pesanan $pesanan)
    {
        // Kalau sudah berhasil, biasanya tidak boleh dibatalkan (opsional aturan)
        if ($pesanan->status === 'berhasil') {
            return back()->with('success', 'Pesanan sudah berhasil, tidak bisa dibatalkan.');
        }

        DB::transaction(function () use ($pesanan) {
            $pesanan->update(['status' => 'dibatalkan']);

            // kalau ada pengantaran, ikut dibatalkan (opsional)
            if ($pesanan->pengantaran) {
                $pesanan->pengantaran->update([
                    'status' => 'dibatalkan',
                    'waktu_verifikasi' => null,
                ]);
            }
        });

        return redirect()->route('pesanan.show', $pesanan->id)->with('success', 'Pesanan dibatalkan.');
    }
}