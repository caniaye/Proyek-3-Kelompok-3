<?php

namespace App\Http\Controllers;

use App\Models\Pengantaran;
use App\Models\Kurir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    public function index()
    {
        $pengantarans = Pengantaran::with(['kurir', 'pesanan.pelanggan'])
            ->orderByRaw("FIELD(status,'dalam_perjalanan','belum_dikirim','berhasil','dibatalkan')")
            ->orderBy('created_at', 'desc')
            ->get();

        $kurirs = Kurir::whereIn('status', ['aktif','nonaktif']) // resign jangan tampil
            ->orderBy('nama','asc')
            ->get();

        return view('pages.monitoring', compact('pengantarans', 'kurirs'));
    }

    public function update(Request $request, Pengantaran $pengantaran)
    {
        $request->validate([
            'kurir_id' => 'nullable|exists:kurirs,id',
            'status'   => 'required|in:belum_dikirim,dalam_perjalanan,berhasil,dibatalkan',
        ]);

        DB::transaction(function () use ($request, $pengantaran) {

            // update pengantaran
            $pengantaran->update([
                'kurir_id' => $request->kurir_id,
                'status'   => $request->status,
                'waktu_verifikasi' => $request->status === 'berhasil' ? now() : null,
            ]);

            // sinkron status pesanan
            $pesanan = $pengantaran->pesanan;
            if (!$pesanan) return;

            $map = [
                'belum_dikirim'     => 'belum_dikirim',
                'dalam_perjalanan'  => 'proses',
                'berhasil'          => 'berhasil',
                'dibatalkan'        => 'dibatalkan',
            ];

            $pesanan->update([
                'status' => $map[$request->status] ?? 'belum_dikirim'
            ]);
        });

        return redirect()->route('monitoring.index')->with('success', 'Monitoring berhasil diupdate!');
    }
}