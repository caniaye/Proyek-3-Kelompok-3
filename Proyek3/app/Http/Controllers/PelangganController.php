<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PelangganController extends Controller
{
    public function index()
    {
        // ✅ urutan sesuai database (id)
        $pelanggans = Pelanggan::orderBy('id', 'asc')->get();
        return view('pages.pelanggan', compact('pelanggans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp'  => 'nullable|string|max:20',
            'foto'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $pathFoto = null;
        if ($request->hasFile('foto')) {
            $pathFoto = $request->file('foto')->store('pelanggans', 'public');
        }

        Pelanggan::create([
            'nama'   => $validated['nama'],
            'alamat' => $validated['alamat'],
            'no_hp'  => $validated['no_hp'] ?? null,
            'foto'   => $pathFoto,
        ]);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    public function show(Pelanggan $pelanggan)
    {
        return view('pages.pelanggan_detail', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp'  => 'nullable|string|max:20',
            'foto'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($pelanggan->foto && Storage::disk('public')->exists($pelanggan->foto)) {
                Storage::disk('public')->delete($pelanggan->foto);
            }
            $pelanggan->foto = $request->file('foto')->store('pelanggans', 'public');
        }

        $pelanggan->nama = $validated['nama'];
        $pelanggan->alamat = $validated['alamat'];
        $pelanggan->no_hp = $validated['no_hp'] ?? null;
        $pelanggan->save();

        return redirect()->route('pelanggan.show', $pelanggan->id)->with('success', 'Data pelanggan berhasil diupdate!');
    }
}