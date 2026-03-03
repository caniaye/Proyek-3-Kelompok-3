<?php

namespace App\Http\Controllers;

use App\Models\Pengantaran;
use App\Models\Kurir;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        $pengantarans = Pengantaran::with(['kurir', 'pesanan.pelanggan'])
            ->orderByRaw("FIELD(status,'dalam_perjalanan','belum_dikirim','berhasil')")
            ->orderBy('created_at', 'desc')
            ->get();

        // dropdown kurir (yang tidak resign)
        $kurirs = Kurir::where('status', '!=', 'resign')->orderBy('kode', 'asc')->get();

        return view('pages.monitoring', compact('pengantarans', 'kurirs'));
    }

    public function update(Request $request, Pengantaran $pengantaran)
    {
        $request->validate([
            'kurir_id' => 'nullable|exists:kurirs,id',
            'status'   => 'required|in:belum_dikirim,dalam_perjalanan,berhasil',
        ]);

        $pengantaran->update([
            'kurir_id' => $request->kurir_id,
            'status'   => $request->status,
            'waktu_verifikasi' => $request->status === 'berhasil' ? now() : null,
        ]);

        // sinkron status ke pesanan
        $pesananStatus = match ($request->status) {
            'belum_dikirim'     => 'belum_dikirim',
            'dalam_perjalanan'  => 'proses',
            'berhasil'          => 'berhasil',
            default             => 'belum_dikirim',
        };

        $pengantaran->pesanan()->update(['status' => $pesananStatus]);

        return redirect()->route('monitoring.index')->with('success', 'Monitoring berhasil diupdate!');
    }
}