@extends('layouts.app')
@section('title','Manajemen Kurir')

@section('content')
<div class="panel">
  <div class="d-flex justify-content-end mb-3">
    <button class="btn-soft btn-green">Tambah</button>
  </div>

  <div class="card-soft">
    <div class="fw-semibold mb-3">Data Kurir</div>

    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="text-muted">
          <tr>
            <th style="width:60px">No</th>
            <th style="width:120px">ID Kurir</th>
            <th>Nama Kurir</th>
            <th style="width:160px">Status</th>
            <th style="width:220px">Aksi</th>
          </tr>
        </thead>

        <tbody>
          @forelse($kurirs as $i => $kurir)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $kurir->kode }}</td>
            <td>{{ $kurir->nama }}</td>
            <td>
              @if($kurir->status === 'aktif')
                <span class="badge-pill b-active">Aktif</span>
              @else
                <span class="badge-pill b-nonactive">Non Aktif</span>
              @endif
            </td>
            <td class="d-flex gap-2">
              <button class="btn-soft btn-gray">Edit</button>
              <button class="btn-soft btn-dark">Hapus</button>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">Belum ada data kurir.</td>
          </tr>
          @endforelse
        </tbody>

      </table>
    </div>

  </div>
</div>
@endsection
