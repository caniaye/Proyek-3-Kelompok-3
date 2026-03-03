@extends('layouts.app')
@section('title','Detail Pesanan')

@section('content')
<div class="panel">

  @if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
  @endif

  <div class="card-soft">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="fw-semibold">Detail Pesanan</div>
      <div class="d-flex gap-2">
        @if($pesanan->status !== 'berhasil' && $pesanan->status !== 'dibatalkan')
          <form method="POST" action="{{ route('pesanan.cancel', $pesanan->id) }}"
                onsubmit="return confirm('Yakin batalkan pesanan ini?');">
            @csrf
            <button type="submit" class="btn-soft btn-dark">Batalkan</button>
          </form>
        @endif
        <a href="{{ route('pesanan.index') }}" class="btn-soft btn-gray" style="text-decoration:none;">Kembali</a>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-md-6">
        <div class="mb-2"><b>ID Pesanan:</b> {{ $pesanan->kode }}</div>
        <div class="mb-2"><b>Pelanggan:</b> {{ $pesanan->pelanggan?->nama ?? '-' }}</div>
        <div class="mb-2"><b>Alamat:</b> {{ $pesanan->pelanggan?->alamat ?? '-' }}</div>
        <div class="mb-2"><b>Tanggal Pesan:</b> {{ \Carbon\Carbon::parse($pesanan->tanggal_pesan)->format('d M Y') }}</div>
      </div>

      <div class="col-md-6">
        <div class="mb-2"><b>Tabung 3kg:</b> {{ $pesanan->qty3kg() }}</div>
        <div class="mb-2"><b>Tabung 12kg:</b> {{ $pesanan->qty12kg() }}</div>
        <div class="mb-2"><b>Total Tabung:</b> {{ $pesanan->jumlah_tabung }}</div>

        <div class="mb-2"><b>Status Pesanan:</b>
          @if($pesanan->status === 'berhasil')
            <span class="badge-pill b-success">Berhasil</span>
          @elseif($pesanan->status === 'proses')
            <span class="badge-pill b-process">Proses</span>
          @elseif($pesanan->status === 'dibatalkan')
            <span class="badge-pill b-nonactive">Dibatalkan</span>
          @else
            <span class="badge-pill b-warning">Belum Dikirim</span>
          @endif
        </div>
      </div>
    </div>

    <hr>

    <div class="fw-semibold mb-2">Info Pengantaran</div>
    <div class="mb-2"><b>Resi:</b> {{ $pesanan->pengantaran?->resi ?? '-' }}</div>
    <div class="mb-2"><b>Kurir:</b> {{ $pesanan->pengantaran?->kurir?->nama ?? '-' }}</div>
    <div class="mb-2"><b>Status Pengantaran:</b>
      @php $st = $pesanan->pengantaran?->status; @endphp
      @if($st === 'berhasil')
        <span class="badge-pill b-success">Berhasil</span>
      @elseif($st === 'dalam_perjalanan')
        <span class="badge-pill b-trip">Dalam Perjalanan</span>
      @elseif($st === 'dibatalkan')
        <span class="badge-pill b-nonactive">Dibatalkan</span>
      @else
        <span class="badge-pill b-warning">Belum Dikirim</span>
      @endif
    </div>
  </div>

</div>
@endsection