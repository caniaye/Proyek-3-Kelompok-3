@extends('layouts.app')
@section('title','Manajemen Pesanan')

@section('content')
<style>
  .row-selectable { cursor: pointer; }
  .row-selected { background: rgba(120, 200, 190, 0.18) !important; }
</style>

<div class="panel">

  @if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger mb-3">
      <ul class="mb-0">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
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
            <th style="width:160px">Status</th>
          </tr>
        </thead>

        <tbody>
          @forelse($pesanans as $ps)
          <tr class="row-selectable"
              data-detail-url="{{ route('pesanan.show', $ps->id) }}">
            <td>{{ $ps->pelanggan?->nama ?? '-' }}</td>
            <td>{{ $ps->kode }}</td>
            <td>{{ $ps->pelanggan?->alamat ?? '-' }}</td>
            <td>
              @if($ps->status === 'berhasil')
                <span class="badge-pill b-success">Berhasil</span>
              @elseif($ps->status === 'proses')
                <span class="badge-pill b-process">Proses</span>
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
              <option value="{{ $p->id }}" {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>
                {{ $p->nama }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="row g-2">
          <div class="col-md-6">
            <label class="form-label">Tabung 3kg</label>
            <input type="number" name="qty_3kg" class="form-control" min="0" value="{{ old('qty_3kg', 0) }}">
          </div>
          <div class="col-md-6">
            <label class="form-label">Tabung 12kg</label>
            <input type="number" name="qty_12kg" class="form-control" min="0" value="{{ old('qty_12kg', 0) }}">
          </div>
        </div>

        <div class="mb-3 mt-3">
          <label class="form-label">Tanggal Pesan</label>
          <input
            id="tanggal_pesan"
            type="date"
            name="tanggal_pesan"
            class="form-control"
            value="{{ old('tanggal_pesan', date('Y-m-d')) }}"
            required>
          <div class="form-text">Tanggal hanya bisa dipilih sampai 1 bulan ke depan.</div>
        </div>

        <div class="form-text">
          ID Pesanan dibuat otomatis (P001, P002, dst). Status awal: <b>Belum Dikirim</b>
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

    function setSelected(row) {
      clearSelected();
      row.classList.add('row-selected');
      selectedUrl = row.dataset.detailUrl;
      btnDetail.disabled = !selectedUrl;
    }

    rows.forEach(row => {
      row.addEventListener('click', function () {
        setSelected(row);
      });

      row.addEventListener('dblclick', function () {
        const url = row.dataset.detailUrl;
        if (url) window.location.href = url;
      });
    });

    btnDetail.addEventListener('click', function () {
      if (selectedUrl) window.location.href = selectedUrl;
    });

    // batas tanggal (hari ini s/d 1 bulan)
    const inputDate = document.getElementById('tanggal_pesan');
    if (inputDate) {
      const today = new Date();
      const yyyy = today.getFullYear();
      const mm = String(today.getMonth() + 1).padStart(2, '0');
      const dd = String(today.getDate()).padStart(2, '0');

      const min = `${yyyy}-${mm}-${dd}`;
      const maxDate = new Date(today);
      maxDate.setMonth(maxDate.getMonth() + 1);

      const yyyy2 = maxDate.getFullYear();
      const mm2 = String(maxDate.getMonth() + 1).padStart(2, '0');
      const dd2 = String(maxDate.getDate()).padStart(2, '0');
      const max = `${yyyy2}-${mm2}-${dd2}`;

      inputDate.min = min;
      inputDate.max = max;
    }
  })();
</script>
@endsection