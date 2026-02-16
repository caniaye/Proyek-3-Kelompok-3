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
          @php
            $rows = [
              ['nama'=>'Ryder','id'=>'P001','alamat'=>'JL. PU BARAT GABUSKULON','status'=>'Berhasil'],
              ['nama'=>'Adeeva','id'=>'P002','alamat'=>'Jalan Raya Sumur Watu Blok Pedati','status'=>'Berhasil'],
              ['nama'=>'Oliver','id'=>'P003','alamat'=>'JL. PU RANCAHAN','status'=>'Proses'],
              ['nama'=>'Zeeva','id'=>'P004','alamat'=>'Drutenkulon','status'=>'Belum Dikirim'],
            ];
          @endphp

          @foreach($rows as $r)
          <tr>
            <td>{{ $r['nama'] }}</td>
            <td>{{ $r['id'] }}</td>
            <td>{{ $r['alamat'] }}</td>
            <td>
              @if($r['status']=='Berhasil')
                <span class="badge-pill b-success">Berhasil</span>
              @elseif($r['status']=='Proses')
                <span class="badge-pill b-process">Proses</span>
              @else
                <span class="badge-pill b-warning">Belum Dikirim</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

  </div>
</div>
@endsection
