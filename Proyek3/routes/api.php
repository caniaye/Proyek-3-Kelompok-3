<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PengantaranController;
use App\Http\Controllers\RiwayatController;

Route::post('/login-kurir', [AuthController::class, 'login']);

Route::get('/riwayat-kurir/{id}', [RiwayatController::class, 'riwayatKurir']);

Route::get('/kurir/{id}/pengantaran', [PengantaranController::class, 'list']);
Route::get('/pengantaran/{id}/detail', [PengantaranController::class, 'detail']);
Route::post('/pengantaran/{id}/mulai', [PengantaranController::class, 'mulai']);
Route::post('/pengantaran/{id}/verifikasi', [PengantaranController::class, 'verifikasi']);