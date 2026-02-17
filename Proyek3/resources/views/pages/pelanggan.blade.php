@extends('layouts.app')
@section('title','Manajemen Pelanggan')

@section('content')
<div class="panel">
  <div class="d-flex justify-content-end gap-2 mb-3">
    <button class="btn-soft btn-green">Tambah</button>
    <button class="btn-soft btn-blue">Lihat Detail</button>
  </div>

  <div class="card-soft">
    <div class="fw-semibold mb-3">Data Pelanggan</div>

    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="text-muted">
          <tr>
            <th style="width:60px">No</th>
            <th>Nama Pelanggan</th>
            <th>Alamat</th>
            <th style="width:180px">No HP</th>
          </tr>
        </thead>

        <tbody>
          @forelse($pelanggans as $i => $p)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $p->nama }}</td>
            <td>{{ $p->alamat }}</td>
            <td>{{ $p->no_hp ?? '-' }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="text-center text-muted py-4">Belum ada data pelanggan.</td>
          </tr>
          @endforelse
        </tbody>

      </table>
    </div>
  </div>
</div>
@endsection
