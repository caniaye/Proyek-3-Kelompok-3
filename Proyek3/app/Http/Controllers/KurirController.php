<?php

namespace App\Http\Controllers;

use App\Models\Kurir;

class KurirController extends Controller
{
    public function index()
    {
        $kurirs = Kurir::orderBy('kode', 'asc')->get();
        return view('pages.kurir', compact('kurirs'));
    }
}
