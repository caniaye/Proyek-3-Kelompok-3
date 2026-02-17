<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KurirController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\RiwayatController;


Route::get('/', fn() => redirect()->route('login'));

Route::view('/login', 'pages.login')->name('login');

Route::view('/dashboard', 'pages.dashboard')->name('dashboard');

Route::get('/kurir', [KurirController::class, 'index'])->name('kurir.index');

Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');

Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');

Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');

Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
