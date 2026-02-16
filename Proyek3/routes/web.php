<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::view('/login', 'pages.login')->name('login');

Route::view('/dashboard', 'pages.dashboard')->name('dashboard');

Route::view('/pelanggan', 'pages.pelanggan')->name('pelanggan.index');

Route::view('/kurir', 'pages.kurir')->name('kurir.index');

Route::view('/pesanan', 'pages.pesanan')->name('pesanan.index');

Route::view('/monitoring', 'pages.monitoring')->name('monitoring.index');

Route::view('/riwayat', 'pages.riwayat')->name('riwayat.index');
