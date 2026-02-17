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
            <th style="width:160px">Tanggal</th>
            <th>Kurir</th>
            <th>Pelanggan</th>
            <th style="width:170px">Status</th>
            <th style="width:220px">Waktu Verifikasi</th>
          </tr>
        </thead>

        <tbody>
          @forelse($pengantarans as $pg)
          <tr>
            <td>{{ $pg->created_at?->format('d M Y') }}</td>
            <td>{{ $pg->kurir?->nama ?? '-' }}</td>
            <td>{{ $pg->pesanan?->pelanggan?->nama ?? '-' }}</td>
            <td>
              @if($pg->status === 'berhasil')
                <span class="badge-pill b-success">Berhasil</span>
              @elseif($pg->status === 'dalam_perjalanan')
                <span class="badge-pill b-trip">Dalam Perjalanan</span>
              @else
                <span class="badge-pill b-warning">Belum Dikirim</span>
              @endif
            </td>
            <td>
              {{ $pg->waktu_verifikasi ? \Carbon\Carbon::parse($pg->waktu_verifikasi)->format('d M Y, H:i') : '-' }}
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">Belum ada riwayat pengantaran.</td>
          </tr>
          @endforelse
        </tbody>

      </table>
    </div>
  </div>
</div>
@endsection
