@extends('layouts.app')
@section('title','Monitoring Pengantaran')

@section('content')
<div class="panel">
  <div class="card-soft">
    <div class="fw-semibold mb-3">Data Pengiriman</div>

    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="text-muted">
          <tr>
            <th>Nama Kurir</th>
            <th>Nama Pelanggan</th>
            <th style="width:180px">Status</th>
            <th style="width:200px">Waktu Verifikasi</th>
          </tr>
        </thead>
        <tbody>
          @php
            $rows = [
              ['kurir'=>'Adinata','pelanggan'=>'Ryder','status'=>'Berhasil','waktu'=>'10 Feb 2026, 14:43'],
              ['kurir'=>'Rion','pelanggan'=>'Adeeva','status'=>'Berhasil','waktu'=>'11 Feb 2026, 10:03'],
              ['kurir'=>'Satya','pelanggan'=>'Oliver','status'=>'Belum Dikirim','waktu'=>'-'],
              ['kurir'=>'Ayres','pelanggan'=>'Zeeva','status'=>'Dalam Perjalanan','waktu'=>'-'],
            ];
          @endphp

          @foreach($rows as $r)
          <tr>
            <td>{{ $r['kurir'] }}</td>
            <td>{{ $r['pelanggan'] }}</td>
            <td>
              @if($r['status']=='Berhasil')
                <span class="badge-pill b-success">Berhasil</span>
              @elseif($r['status']=='Belum Dikirim')
                <span class="badge-pill b-warning">Belum Dikirim</span>
              @else
                <span class="badge-pill b-trip">Dalam Perjalanan</span>
              @endif
            </td>
            <td>{{ $r['waktu'] }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

  </div>
</div>
@endsection
