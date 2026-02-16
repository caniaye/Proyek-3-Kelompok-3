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
          @php
            $rows = [
              ['nama'=>'Ryder','alamat'=>'JL. PU BARAT GABUSKULON','hp'=>'0812-3456-7890'],
              ['nama'=>'Adeeva','alamat'=>'Jalan Raya Sumur Watu Blok Pedati','hp'=>'0821-9876-5432'],
              ['nama'=>'Oliver','alamat'=>'JL. PU RANCAHAN','hp'=>'0857-2345-6789'],
              ['nama'=>'Zeeva','alamat'=>'Drutenkulon','hp'=>'0813-4567-8901'],
            ];
          @endphp
          @foreach($rows as $i=>$r)
          <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $r['nama'] }}</td>
            <td>{{ $r['alamat'] }}</td>
            <td>{{ $r['hp'] }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
