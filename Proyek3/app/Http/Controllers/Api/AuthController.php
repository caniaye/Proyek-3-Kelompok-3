<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Kurir;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'kode' => 'required|string',
            'password' => 'required|string',
        ], [
            'kode.required' => 'ID Kurir wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $kurir = Kurir::where('kode', $request->kode)->first();

        if (!$kurir) {
            return response()->json([
                'status' => false,
                'message' => 'ID Kurir tidak ditemukan',
            ], 401);
        }

        if ($kurir->status !== 'aktif') {
            return response()->json([
                'status' => false,
                'message' => 'Kurir tidak aktif',
            ], 403);
        }

        if (!$kurir->password || !Hash::check($request->password, $kurir->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Password salah',
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'data' => [
                'id' => $kurir->id,
                'kode' => $kurir->kode,
                'nama' => $kurir->nama,
                'foto' => $kurir->foto
                    ? url('storage/' . $kurir->foto)
                    : url('image/default-avatar.png'),
                'status' => $kurir->status,
            ],
        ], 200);
    }
}