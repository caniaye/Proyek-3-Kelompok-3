@extends('layouts.app')
@section('title','Dashboard')

@section('content')
  <div class="panel">
    <div class="row g-3 mb-3">
      <div class="col-md-3">
        <div class="card-soft text-center">
          <div>Total Pesanan</div>
          <div class="display-6 fw-bold">523</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card-soft text-center">
          <div>Total Kurir</div>
          <div class="display-6 fw-bold">13</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card-soft text-center">
          <div>Pengantaran Hari Ini</div>
          <div class="display-6 fw-bold">20</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card-soft text-center">
          <div>Status</div>
          <div class="d-flex justify-content-center gap-4 mt-2">
            <div>
              <div class="small text-muted">Berhasil</div>
              <div class="fs-4 fw-bold">4</div>
            </div>
            <div>
              <div class="small text-muted">Proses</div>
              <div class="fs-4 fw-bold">2</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card-soft">
      <div class="fw-semibold mb-3">Pengantaran Terbaru</div>

      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="text-muted">
            <tr>
              <th style="width:60px">No</th>
              <th style="width:110px">Resi</th>
              <th>Nama Kurir</th>
              <th>Tujuan</th>
              <th style="width:140px">Status</th>
              <th style="width:140px">Tanggal</th>
            </tr>
          </thead>
          <tbody>
            @php
              $rows = [
                ['resi'=>'GCV001','kurir'=>'Adinata','tujuan'=>'Blok Kedungwaru','status'=>'Berhasil','tgl'=>'03-02-2026'],
                ['resi'=>'GCV002','kurir'=>'Rion','tujuan'=>'JL. PU Barat Gabuskulon','status'=>'Berhasil','tgl'=>'03-02-2026'],
                ['resi'=>'GCV003','kurir'=>'Satya','tujuan'=>'Jl. Raya Gabuswetan','status'=>'Proses','tgl'=>'03-02-2026'],
                ['resi'=>'GCV004','kurir'=>'Ayres','tujuan'=>'Drutenkulon','status'=>'Berhasil','tgl'=>'03-02-2026'],
              ];
            @endphp
            @foreach($rows as $i=>$r)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $r['resi'] }}</td>
                <td>{{ $r['kurir'] }}</td>
                <td>{{ $r['tujuan'] }}</td>
                <td>
                  @if($r['status']=='Berhasil')
                    <span class="badge-pill b-success">Berhasil</span>
                  @else
                    <span class="badge-pill b-process">Proses</span>
                  @endif
                </td>
                <td>{{ $r['tgl'] }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    </div>
  </div>
@endsection
