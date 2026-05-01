<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\RiwayatController;

Route::post('/login-kurir', [AuthController::class, 'login']);
Route::get('/riwayat-kurir/{id}', [RiwayatController::class, 'riwayatKurir']);