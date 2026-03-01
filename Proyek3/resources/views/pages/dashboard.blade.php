@extends('layouts.app')
@section('title','Dashboard')

@section('content')
  {{-- Tambahan CSS untuk warna KPI cards (baris total pesanan, kurir, dll) --}}
  <style>
    .kpi{
      border-radius: 16px;
      padding: 22px 18px;
      color: #fff;
      border: 1px solid rgba(255,255,255,.12);
      box-shadow: 0 10px 22px rgba(0,0,0,.06);
    }
    .kpi-title{
      font-weight: 600;
      opacity: .95;
    }
    .kpi-value{
      font-size: 48px;
      font-weight: 800;
      line-height: 1.1;
      margin-top: 6px;
    }
    .kpi-sub{
      color: rgba(255,255,255,.85);
    }
    .kpi-mini{
      font-size: 28px;
      font-weight: 800;
      line-height: 1.1;
      margin-top: 2px;
    }

    /* Warna per card (samain vibe kayak screenshot) */
    .kpi-pesanan{
      background: linear-gradient(135deg, #6c8f8c 0%, #5e827f 100%);
    }
    .kpi-kurir{
      background: linear-gradient(135deg, #86c4a6 0%, #79b99a 100%);
    }
    .kpi-hariini{
      background: linear-gradient(135deg, #b7d8c9 0%, #a7cfbe 100%);
    }
    .kpi-status{
      background: linear-gradient(135deg, #9ac6c7 0%, #88bcbc 100%);
    }
  </style>

  <div class="panel">
    <div class="row g-3 mb-3">
      <div class="col-md-3">
        <div class="kpi kpi-pesanan text-center">
          <div class="kpi-title">Total Pesanan</div>
          <div class="kpi-value">{{ $totalPesanan }}</div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="kpi kpi-kurir text-center">
          <div class="kpi-title">Total Kurir</div>
          <div class="kpi-value">{{ $totalKurir }}</div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="kpi kpi-hariini text-center">
          <div class="kpi-title">Pengantaran Hari Ini</div>
          <div class="kpi-value">{{ $pengantaranHariIni }}</div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="kpi kpi-status text-center">
          <div class="kpi-title">Status</div>
          <div class="d-flex justify-content-center gap-4 mt-2">
            <div>
              <div class="small kpi-sub">Berhasil</div>
              <div class="kpi-mini">{{ $statusBerhasil }}</div>
            </div>
            <div>
              <div class="small kpi-sub">Proses</div>
              <div class="kpi-mini">{{ $statusProses }}</div>
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