@extends('layouts.app')
@section('title','Dashboard')

@section('content')
  <div class="panel">
    <div class="row g-3 mb-3">
      <div class="col-md-3">
        <div class="card-soft text-center">
          <div>Total Pesanan</div>
          <div class="display-6 fw-bold">{{ $totalPesanan }}</div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card-soft text-center">
          <div>Total Kurir</div>
          <div class="display-6 fw-bold">{{ $totalKurir }}</div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card-soft text-center">
          <div>Pengantaran Hari Ini</div>
          <div class="display-6 fw-bold">{{ $pengantaranHariIni }}</div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card-soft text-center">
          <div>Status</div>
          <div class="d-flex justify-content-center gap-4 mt-2">
            <div>
              <div class="small text-muted">Berhasil</div>
              <div class="fs-4 fw-bold">{{ $statusBerhasil }}</div>
            </div>
            <div>
              <div class="small text-muted">Proses</div>
              <div class="fs-4 fw-bold">{{ $statusProses }}</div>
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
            @forelse($pengantaranTerbaru as $i => $p)
              @php
                // Tujuan: ambil dari pelanggan kalau ada alamat, kalau belum ada kolom alamat ya fallback "-"
                $tujuan = $p->pesanan?->pelanggan?->alamat ?? '-';

                // Status label (sesuaikan enum kamu)
                $statusLabel = match($p->status){
                  'berhasil' => 'Berhasil',
                  'dalam_perjalanan' => 'Proses',
                  default => 'Belum Dikirim',
                };

                $badgeClass = match($p->status){
                  'berhasil' => 'b-success',
                  'dalam_perjalanan' => 'b-process',
                  default => 'b-warning',
                };
              @endphp

              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $p->resi }}</td>
                <td>{{ $p->kurir?->nama ?? '-' }}</td>
                <td>{{ $tujuan }}</td>
                <td>
                  <span class="badge-pill {{ $badgeClass }}">{{ $statusLabel }}</span>
                </td>
                <td>{{ optional($p->created_at)->format('d-m-Y') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">
                  Belum ada data pengantaran.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>
  </div>
@endsection