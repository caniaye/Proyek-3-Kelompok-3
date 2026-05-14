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
            <th style="width:150px">Foto</th>
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
              @elseif($pg->status === 'dibatalkan')
                <span class="badge-pill b-nonactive">Dibatalkan</span>
              @else
                <span class="badge-pill b-warning">Belum Dikirim</span>
              @endif
            </td>
            <td>
              {{ $pg->waktu_verifikasi ? \Carbon\Carbon::parse($pg->waktu_verifikasi)->format('d M Y, H:i') : '-' }}
            </td>
            <td>
              @if($pg->foto_verifikasi)
                <button
                  type="button"
                  class="btn-soft btn-blue"
                  data-bs-toggle="modal"
                  data-bs-target="#modalFoto{{ $pg->id }}"
                >
                  Lihat Foto
                </button>

                <div class="modal fade" id="modalFoto{{ $pg->id }}" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">
                          Foto Penerima - {{ $pg->pesanan?->pelanggan?->nama ?? '-' }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body text-center">
                        <img
                          src="{{ asset('storage/' . $pg->foto_verifikasi) }}"
                          alt="Foto Verifikasi"
                          style="max-width:100%; max-height:70vh; border-radius:16px;"
                        >
                      </div>
                    </div>
                  </div>
                </div>
              @else
                <span class="text-muted">-</span>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-4">
              Belum ada riwayat pengantaran.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    setInterval(function () {
      const modalOpen = document.querySelector('.modal.show');

      if (!modalOpen) {
        window.location.reload();
      }
    }, 5000);
  });
</script>
@endsection