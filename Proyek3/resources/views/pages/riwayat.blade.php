@extends('layouts.app')
@section('title','Riwayat Pengantaran')

@section('content')
<div class="panel">
  <div class="card-soft">
    <div class="fw-semibold mb-3">Data Riwayat Pengantaran</div>

    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="text-muted">
          <tr>
            <th style="width:140px">Tanggal</th>
            <th>Kurir</th>
            <th>Pelanggan</th>
            <th style="width:170px">Status</th>
            <th style="width:200px">Waktu</th>
          </tr>
        </thead>
        <tbody>
          @php
            $rows = [
              ['tgl'=>'10 Feb 2026','kurir'=>'Adinata','pelanggan'=>'Ryder','status'=>'Berhasil','waktu'=>'10 Feb 2026, 14:43'],
              ['tgl'=>'11 Feb 2026','kurir'=>'Rion','pelanggan'=>'Adeeva','status'=>'Berhasil','waktu'=>'11 Feb 2026, 10:03'],
              ['tgl'=>'17 Feb 2026','kurir'=>'Aileen','pelanggan'=>'Ozora','status'=>'Belum Dikirim','waktu'=>'-'],
              ['tgl'=>'17 Feb 2026','kurir'=>'Neandro','pelanggan'=>'Hazel','status'=>'Dalam Perjalanan','waktu'=>'-'],
            ];
          @endphp

          @foreach($rows as $r)
          <tr>
            <td>{{ $r['tgl'] }}</td>
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
