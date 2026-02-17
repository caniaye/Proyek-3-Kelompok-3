@extends('layouts.app')
@section('title','Manajemen Pesanan')

@section('content')
<div class="panel">
  <div class="d-flex justify-content-end mb-3">
    <button class="btn-soft btn-blue">Lihat Detail</button>
  </div>

  <div class="card-soft">
    <div class="fw-semibold mb-3">Data Pesanan</div>

    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="text-muted">
          <tr>
            <th>Nama Pelanggan</th>
            <th style="width:140px">ID Pesanan</th>
            <th>Alamat</th>
            <th style="width:160px">Status</th>
          </tr>
        </thead>

        <tbody>
          @forelse($pesanans as $ps)
          <tr>
            <td>{{ $ps->pelanggan?->nama ?? '-' }}</td>
            <td>{{ $ps->kode }}</td>
            <td>{{ $ps->pelanggan?->alamat ?? '-' }}</td>
            <td>
              @if($ps->status === 'berhasil')
                <span class="badge-pill b-success">Berhasil</span>
              @elseif($ps->status === 'proses')
                <span class="badge-pill b-process">Proses</span>
              @else
                <span class="badge-pill b-warning">Belum Dikirim</span>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="text-center text-muted py-4">Belum ada data pesanan.</td>
          </tr>
          @endforelse
        </tbody>

      </table>
    </div>
  </div>
</div>
@endsection
