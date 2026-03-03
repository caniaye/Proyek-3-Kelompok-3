<?php

namespace App\Http\Controllers;

use App\Models\Kurir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KurirController extends Controller
{
    public function index()
    {
        $kurirs = Kurir::orderBy('kode', 'asc')->get();
        return view('pages.kurir', compact('kurirs'));
    }

    // Tambah: kode otomatis + foto optional
    public function store(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
            'foto'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $lastKode = Kurir::orderByRaw("CAST(SUBSTRING(kode, 4) AS UNSIGNED) DESC")->value('kode');
        $lastNumber = $lastKode ? (int) substr($lastKode, 3) : 0;

        $newKode = 'KUR' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        $pathFoto = null;
        if ($request->hasFile('foto')) {
            $pathFoto = $request->file('foto')->store('kurir', 'public');
        }

        Kurir::create([
            'kode'   => $newKode,
            'nama'   => $request->nama,
            'status' => $request->status,
            'foto'   => $pathFoto,
        ]);

        return redirect()->route('kurir.index')->with('success', 'Kurir berhasil ditambahkan!');
    }

    // Edit: hanya nama + status kerja/libur + foto optional
    public function update(Request $request, Kurir $kurir)
    {
        if ($kurir->status === 'resign') {
            return redirect()->route('kurir.index')->with('success', 'Kurir resign tidak bisa diedit.');
        }

        $validated = $request->validate([
            'nama'   => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
            'foto'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // hapus foto lama kalau ada
            if ($kurir->foto && Storage::disk('public')->exists($kurir->foto)) {
                Storage::disk('public')->delete($kurir->foto);
            }
            $validated['foto'] = $request->file('foto')->store('kurir', 'public');
        }

        $kurir->update($validated);

        return redirect()->route('kurir.index')->with('success', 'Kurir berhasil diupdate!');
    }

    // "Hapus" => resign (bukan delete)
    public function destroy(Kurir $kurir)
    {
        $kurir->update(['status' => 'resign']);
        return redirect()->route('kurir.index')->with('success', 'Kurir berhasil dihapus (status menjadi resign).');
    }
}