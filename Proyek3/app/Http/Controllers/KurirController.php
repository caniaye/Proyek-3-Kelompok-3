<?php

namespace App\Http\Controllers;

use App\Models\Kurir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class KurirController extends Controller
{
    public function index()
    {
        $kurirs = Kurir::orderBy('kode', 'asc')->get();
        return view('pages.kurir', compact('kurirs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'password' => 'required|string|min:6|max:255',
            'status' => 'required|in:aktif,nonaktif',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nama.required' => 'Nama kurir wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat jpg, jpeg, png, atau webp.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $lastKode = Kurir::orderByRaw("CAST(SUBSTRING(kode, 4) AS UNSIGNED) DESC")->value('kode');
        $lastNumber = $lastKode ? (int) substr($lastKode, 3) : 0;
        $newKode = 'KUR' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        $pathFoto = null;
        if ($request->hasFile('foto')) {
            $pathFoto = $request->file('foto')->store('kurir', 'public');
        }

        Kurir::create([
            'kode' => $newKode,
            'nama' => $request->nama,
            'password' => Hash::make($request->password),
            'status' => $request->status,
            'foto' => $pathFoto,
        ]);

        return redirect()->route('kurir.index')->with('success', 'Kurir berhasil ditambahkan!');
    }

    public function update(Request $request, Kurir $kurir)
    {
        if ($kurir->status === 'resign') {
            return redirect()->route('kurir.index')->with('success', 'Kurir resign tidak bisa diedit.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'password' => 'nullable|string|min:6|max:255',
            'status' => 'required|in:aktif,nonaktif',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nama.required' => 'Nama kurir wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat jpg, jpeg, png, atau webp.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        if ($request->hasFile('foto')) {
            if ($kurir->foto && Storage::disk('public')->exists($kurir->foto)) {
                Storage::disk('public')->delete($kurir->foto);
            }

            $validated['foto'] = $request->file('foto')->store('kurir', 'public');
        }

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $kurir->update($validated);

        return redirect()->route('kurir.index')->with('success', 'Kurir berhasil diupdate!');
    }

    public function destroy(Kurir $kurir)
    {
        $kurir->update([
            'status' => 'resign',
        ]);

        return redirect()->route('kurir.index')->with('success', 'Kurir berhasil dihapus (status menjadi resign).');
    }
}