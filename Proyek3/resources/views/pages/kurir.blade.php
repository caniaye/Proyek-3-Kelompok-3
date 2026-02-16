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
          @php
            $rows = [
              ['id'=>'KUR001','nama'=>'Adinata','status'=>'Aktif'],
              ['id'=>'KUR002','nama'=>'Rion','status'=>'Aktif'],
              ['id'=>'KUR003','nama'=>'Satya','status'=>'Non Aktif'],
              ['id'=>'KUR004','nama'=>'Ayres','status'=>'Aktif'],
            ];
          @endphp
          @foreach($rows as $i=>$r)
          <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $r['id'] }}</td>
            <td>{{ $r['nama'] }}</td>
            <td>
              @if($r['status']=='Aktif')
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
          @endforeach
        </tbody>
      </table>
    </div>

  </div>
</div>
@endsection
