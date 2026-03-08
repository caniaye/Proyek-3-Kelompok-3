@extends('layouts.app')
@section('title','Manajemen Pesanan')

@section('content')
<style>
  .row-selectable { cursor: pointer; }
  .row-selected { background: rgba(120, 200, 190, 0.18) !important; }
  .link-name { color:#222; text-decoration:none; font-weight:600; }
  .link-name:hover { text-decoration:underline; }
</style>

<div class="panel">

  @if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
  @endif

  <div class="d-flex justify-content-end gap-2 mb-3">
    <button class="btn-soft btn-green" data-bs-toggle="modal" data-bs-target="#modalTambahPesanan">
      Tambah
    </button>

    <button id="btnDetail" class="btn-soft btn-blue" type="button" disabled>
      Lihat Detail
    </button>
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
            <th style="width:170px">Status</th>
          </tr>
        </thead>

        <tbody>
          @forelse($pesanans as $ps)
          <tr class="row-selectable" data-detail-url="{{ route('pesanan.show', $ps->id) }}">
            <td>
              <a class="link-name" href="{{ route('pesanan.show', $ps->id) }}">
                {{ $ps->pelanggan?->nama ?? '-' }}
              </a>
            </td>
            <td>{{ $ps->kode }}</td>
            <td>{{ $ps->pelanggan?->alamat ?? '-' }}</td>
            <td>
              @if($ps->status === 'berhasil')
                <span class="badge-pill b-success">Berhasil</span>
              @elseif($ps->status === 'dalam_perjalanan')
                <span class="badge-pill b-trip">Dalam Perjalanan</span>
              @elseif($ps->status === 'dibatalkan')
                <span class="badge-pill b-nonactive">Dibatalkan</span>
              @else
                <span class="badge-pill b-warning">Belum Dikirim</span>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="text-center text-muted py-4">Belum ada data pesanan.</td>
          </tr>
          @endforelse
        </tbody>

      </table>
    </div>
  </div>
</div>

@php
  $today = date('Y-m-d');
  $maxDate = date('Y-m-d', strtotime('+1 month'));
@endphp

{{-- MODAL TAMBAH PESANAN --}}
<div class="modal fade" id="modalTambahPesanan" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" method="POST" action="{{ route('pesanan.store') }}">
      @csrf

      <div class="modal-header">
        <h5 class="modal-title">Tambah Pesanan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Pelanggan</label>
          <select name="pelanggan_id" class="form-control" required>
            <option value="">-- Pilih Pelanggan --</option>
            @foreach($pelanggans as $p)
              <option value="{{ $p->id }}">{{ $p->nama }}</option>
            @endforeach
          </select>
        </div>

        <div class="row g-2">
          <div class="col-md-6">
            <label class="form-label">Tabung 3kg</label>
            <input type="number" name="qty_3kg" class="form-control" min="0" value="0" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Tabung 12kg</label>
            <input type="number" name="qty_12kg" class="form-control" min="0" value="0" required>
          </div>
        </div>

        <div class="mt-3">
          <label class="form-label">Tanggal Pesan</label>
          <input
            type="date"
            name="tanggal_pesan"
            class="form-control"
            value="{{ $today }}"
            min="{{ $today }}"
            max="{{ $maxDate }}"
            required
          >
          <div class="form-text">
            Tanggal hanya bisa dari <b>hari ini</b> sampai <b>1 bulan ke depan</b>.
          </div>
        </div>

        <div class="form-text mt-2">
          ID Pesanan otomatis (P001, P002, dst). Status awal: <b>Belum Dikirim</b>.
          Kurir dipilih nanti di <b>Monitoring</b>.
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn-soft" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn-soft btn-green">Simpan</button>
      </div>

    </form>
  </div>
</div>

<script>
  (function () {
    const rows = document.querySelectorAll('tr.row-selectable');
    const btnDetail = document.getElementById('btnDetail');
    let selectedUrl = null;

    function clearSelected() {
      rows.forEach(r => r.classList.remove('row-selected'));
    }

    rows.forEach(row => {
      row.addEventListener('click', function (e) {
        // biar klik link nama tetap jalan, tapi row juga ke-select
        clearSelected();
        row.classList.add('row-selected');
        selectedUrl = row.dataset.detailUrl;
        btnDetail.disabled = !selectedUrl;
      });

      row.addEventListener('dblclick', function () {
        const url = row.dataset.detailUrl;
        if (url) window.location.href = url;
      });
    });

    btnDetail.addEventListener('click', function () {
      if (selectedUrl) window.location.href = selectedUrl;
    });
  })();
</script>
@endsection