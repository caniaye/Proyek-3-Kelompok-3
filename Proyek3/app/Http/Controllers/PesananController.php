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
        $pesanans = Pesanan::with(['pelanggan'])
            ->orderBy('created_at', 'desc')
            ->get();

        $pelanggans = Pelanggan::orderBy('id', 'asc')->get();

        return view('pages.pesanan', compact('pesanans', 'pelanggans'));
    }

    public function store(Request $request)
    {
        $maxDate = date('Y-m-d', strtotime('+1 month'));

        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'qty_3kg'      => 'required|integer|min:0|max:100',
            'qty_12kg'     => 'required|integer|min:0|max:100',
            'tanggal_pesan'=> "required|date|after_or_equal:today|before_or_equal:$maxDate",
        ]);

        if ((int)$request->qty_3kg + (int)$request->qty_12kg <= 0) {
            return back()->withErrors(['qty_3kg' => 'Minimal pilih 1 tabung (3kg/12kg).'])->withInput();
        }

        DB::transaction(function () use ($request) {

            // kode otomatis P001, P002...
            $lastKode = Pesanan::orderByRaw("CAST(SUBSTRING(kode, 2) AS UNSIGNED) DESC")->value('kode');
            $lastNumber = $lastKode ? (int) substr($lastKode, 1) : 0;
            $newKode = 'P' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

            $total = (int)$request->qty_3kg + (int)$request->qty_12kg;

            $pesanan = Pesanan::create([
                'kode'          => $newKode,
                'pelanggan_id'  => $request->pelanggan_id,
                'jumlah_tabung' => $total,
                'tanggal_pesan' => $request->tanggal_pesan,
                'status'        => 'belum_dikirim',
            ]);

            // simpan detail jenis tabung ke pesanan_items
            PesananItem::updateOrCreate(
                ['pesanan_id' => $pesanan->id, 'jenis_tabung' => '3kg'],
                ['qty' => (int)$request->qty_3kg]
            );

            PesananItem::updateOrCreate(
                ['pesanan_id' => $pesanan->id, 'jenis_tabung' => '12kg'],
                ['qty' => (int)$request->qty_12kg]
            );

            // otomatis buat pengantaran (kurir dipilih nanti)
            $lastResi = Pengantaran::orderByRaw("CAST(SUBSTRING(resi, 4) AS UNSIGNED) DESC")->value('resi');
            $lastResiNum = $lastResi ? (int) substr($lastResi, 3) : 0;
            $newResi = 'GCV' . str_pad($lastResiNum + 1, 3, '0', STR_PAD_LEFT);

            Pengantaran::create([
                'resi'       => $newResi,
                'pesanan_id' => $pesanan->id,
                'kurir_id'   => null,
                'status'     => 'belum_dikirim',
                'waktu_verifikasi' => null,
            ]);
        });

        return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil ditambahkan!');
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['pelanggan', 'pengantaran.kurir', 'items']);
        return view('pages.pesanan_detail', compact('pesanan'));
    }

    // batalkan pesanan (admin)
    public function cancel(Pesanan $pesanan)
    {
        DB::transaction(function () use ($pesanan) {
            // kalau sudah berhasil, jangan boleh dibatalkan
            if ($pesanan->status === 'berhasil') {
                return;
            }

            $pesanan->update(['status' => 'dibatalkan']);

            // sekalian tandai pengantaran dibatalkan juga (biar monitoring konsisten)
            if ($pesanan->pengantaran) {
                $pesanan->pengantaran->update([
                    'status' => 'dibatalkan',
                    'waktu_verifikasi' => null,
                ]);
            }
        });

        return redirect()->route('pesanan.show', $pesanan->id)->with('success', 'Pesanan berhasil dibatalkan!');
    }
}