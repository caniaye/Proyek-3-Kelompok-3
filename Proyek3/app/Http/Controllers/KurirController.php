<?php

namespace App\Http\Controllers;

use App\Models\Kurir;
use Illuminate\Http\Request;

class KurirController extends Controller
{
    public function index()
    {
        $kurirs = Kurir::orderBy('kode', 'asc')->get();
        return view('pages.kurir', compact('kurirs'));
    }

    // ✅ Tambah: kode otomatis KUR001, KUR002, dst
    public function store(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            // status untuk kerja/libur
            'status' => 'required|in:aktif,nonaktif',
        ]);

        // ambil kode terakhir berdasarkan angka terbesar di belakang "KUR"
        $lastKode = Kurir::orderByRaw("CAST(SUBSTRING(kode, 4) AS UNSIGNED) DESC")->value('kode');

        $lastNumber = 0;
        if ($lastKode) {
            $lastNumber = (int) substr($lastKode, 3); // "KUR003" => 3
        }

        $newNumber = $lastNumber + 1;
        $newKode = 'KUR' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        Kurir::create([
            'kode'   => $newKode,
            'nama'   => $request->nama,
            'status' => $request->status, // aktif/nonaktif
        ]);

        return redirect()->route('kurir.index')->with('success', 'Kurir berhasil ditambahkan!');
    }

    // ✅ Edit: hanya boleh aktif/nonaktif (kerja/libur)
    // kalau sudah resign, tidak boleh diubah lagi
    public function update(Request $request, Kurir $kurir)
    {
        if ($kurir->status === 'resign') {
            return redirect()->route('kurir.index')->with('success', 'Kurir resign tidak bisa diedit.');
        }

        $validated = $request->validate([
            // kode boleh diedit (kalau mau dikunci bilang aja)
            'kode'   => 'required|string|max:20|unique:kurirs,kode,' . $kurir->id,
            'nama'   => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif', // kerja/libur
        ]);

        $kurir->update($validated);

        return redirect()->route('kurir.index')->with('success', 'Kurir berhasil diupdate!');
    }

    // ✅ "Hapus" tapi tidak delete → status jadi resign
    public function destroy(Kurir $kurir)
    {
        $kurir->update(['status' => 'resign']);

        return redirect()->route('kurir.index')
            ->with('success', 'Kurir berhasil dihapus (status menjadi resign).');
    }
}