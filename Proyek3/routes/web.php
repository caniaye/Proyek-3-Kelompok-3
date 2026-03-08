<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\KurirController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\RiwayatController;

Route::get('/', fn () => redirect()->route('login'));

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pelanggan
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    Route::post('/pelanggan', [PelangganController::class, 'store'])->name('pelanggan.store');
    Route::get('/pelanggan/{pelanggan}', [PelangganController::class, 'show'])->name('pelanggan.show');
    Route::put('/pelanggan/{pelanggan}', [PelangganController::class, 'update'])->name('pelanggan.update');

    // Kurir
    Route::get('/kurir', [KurirController::class, 'index'])->name('kurir.index');
    Route::post('/kurir', [KurirController::class, 'store'])->name('kurir.store');
    Route::put('/kurir/{kurir}', [KurirController::class, 'update'])->name('kurir.update');
    Route::delete('/kurir/{kurir}', [KurirController::class, 'destroy'])->name('kurir.destroy');

    // Pesanan
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    Route::post('/pesanan', [PesananController::class, 'store'])->name('pesanan.store');
    Route::get('/pesanan/{pesanan}', [PesananController::class, 'show'])->name('pesanan.show');
    Route::patch('/pesanan/{pesanan}/batal', [PesananController::class, 'cancel'])->name('pesanan.cancel');

    // Monitoring
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    Route::patch('/monitoring/{pengantaran}', [MonitoringController::class, 'update'])->name('monitoring.update');

    // Riwayat
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
});