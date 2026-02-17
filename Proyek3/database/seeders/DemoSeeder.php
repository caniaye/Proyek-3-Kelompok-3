<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggan;
use App\Models\Kurir;
use App\Models\Pesanan;
use App\Models\Pengantaran;
use Carbon\Carbon;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ========================
        // PELANGGAN
        // ========================
        $pelanggan1 = Pelanggan::create([
            'nama' => 'Ryder',
            'alamat' => 'JL. PU BARAT GABUSKULON',
            'no_hp' => '081234567890'
        ]);

        $pelanggan2 = Pelanggan::create([
            'nama' => 'Adee va',
            'alamat' => 'Jalan Raya Sumur Watu Blok Pedati',
            'no_hp' => '082198765432'
        ]);

        $pelanggan3 = Pelanggan::create([
            'nama' => 'Oliver',
            'alamat' => 'JL. PU RANCAHAN',
            'no_hp' => '085723456789'
        ]);

        // ========================
        // KURIR
        // ========================
        $kurir1 = Kurir::create([
            'kode' => 'KUR001',
            'nama' => 'Adinata',
            'status' => 'aktif'
        ]);

        $kurir2 = Kurir::create([
            'kode' => 'KUR002',
            'nama' => 'Rion',
            'status' => 'aktif'
        ]);

        $kurir3 = Kurir::create([
            'kode' => 'KUR003',
            'nama' => 'Satya',
            'status' => 'nonaktif'
        ]);

        // ========================
        // PESANAN
        // ========================
        $pesanan1 = Pesanan::create([
            'kode' => 'P001',
            'pelanggan_id' => $pelanggan1->id,
            'jumlah_tabung' => 1,
            'tanggal_pesan' => now(),
            'status' => 'berhasil'
        ]);

        $pesanan2 = Pesanan::create([
            'kode' => 'P002',
            'pelanggan_id' => $pelanggan2->id,
            'jumlah_tabung' => 2,
            'tanggal_pesan' => now(),
            'status' => 'proses'
        ]);

        $pesanan3 = Pesanan::create([
            'kode' => 'P003',
            'pelanggan_id' => $pelanggan3->id,
            'jumlah_tabung' => 1,
            'tanggal_pesan' => now(),
            'status' => 'belum_dikirim'
        ]);

        // ========================
        // PENGANTARAN
        // ========================
        Pengantaran::create([
            'resi' => 'GCV001',
            'pesanan_id' => $pesanan1->id,
            'kurir_id' => $kurir1->id,
            'status' => 'berhasil',
            'waktu_verifikasi' => Carbon::now()
        ]);

        Pengantaran::create([
            'resi' => 'GCV002',
            'pesanan_id' => $pesanan2->id,
            'kurir_id' => $kurir2->id,
            'status' => 'dalam_perjalanan',
            'waktu_verifikasi' => null
        ]);

        Pengantaran::create([
            'resi' => 'GCV003',
            'pesanan_id' => $pesanan3->id,
            'kurir_id' => $kurir1->id,
            'status' => 'belum_dikirim',
            'waktu_verifikasi' => null
        ]);
    }
}
