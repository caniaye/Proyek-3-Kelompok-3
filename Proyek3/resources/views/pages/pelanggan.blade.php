@extends('layouts.app')
@section('title','Manajemen Pelanggan')

@section('content')
<style>
  .row-selectable { cursor: pointer; }

  /* Highlight pasti keliatan: apply ke semua TD */
  tr.row-selected > td {
    background-color: rgba(120, 200, 190, 0.25) !important;
  }

  /* Biar makin jelas ada garis kiri */
  tr.row-selected > td:first-child {
    box-shadow: inset 4px 0 0 rgba(60, 170, 150, 0.9);
  }
</style>

<div class="panel">

  @if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
  @endif

  {{-- Tombol kanan atas: Tambah + Lihat Detail --}}
  <div class="d-flex justify-content-end gap-2 mb-3">
    <button class="btn-soft btn-green" data-bs-toggle="modal" data-bs-target="#modalTambahPelanggan">
      Tambah
    </button>

    <button id="btnDetail" class="btn-soft btn-blue" type="button" disabled>
      Lihat Detail
    </button>
  </div>

  <div class="card-soft">
    <div class="fw-semibold mb-3">Data Pelanggan</div>

    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="text-muted">
          <tr>
            <th style="width:60px">No</th>
            <th>Nama Pelanggan</th>
            <th>Alamat</th>
            <th style="width:180px">No HP</th>
          </tr>
        </thead>

        <tbody>
          @forelse($pelanggans as $i => $p)
          <tr class="row-selectable"
              data-detail-url="{{ route('pelanggan.show', $p->id) }}">
            <td>{{ $i + 1 }}</td>

            <td>
              <div class="d-flex align-items-center gap-2">
                <img src="{{ $p->fotoUrl() }}" alt="foto"
                     style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                <div class="nama-pelanggan">{{ $p->nama }}</div>
              </div>
            </td>

            <td>{{ $p->alamat }}</td>
            <td>{{ $p->no_hp ?? '-' }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="text-center text-muted py-4">Belum ada data pelanggan.</td>
          </tr>
          @endforelse
        </tbody>

      </table>
    </div>
  </div>
</div>

{{-- MODAL TAMBAH PELANGGAN --}}
<div class="modal fade" id="modalTambahPelanggan" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" method="POST" action="{{ route('pelanggan.store') }}" enctype="multipart/form-data">
      @csrf

      <div class="modal-header">
        <h5 class="modal-title">Tambah Pelanggan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nama</label>
          <input type="text" name="nama" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Alamat</label>
          <textarea name="alamat" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">No HP</label>
          <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx">
        </div>

        <div class="mb-3">
          <label class="form-label">Foto (opsional)</label>
          <input type="file" name="foto" class="form-control" accept="image/*">
          <div class="form-text">jpg/jpeg/png/webp, max 2MB</div>
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
  document.addEventListener('DOMContentLoaded', function () {
    const rows = document.querySelectorAll('tr.row-selectable');
    const btnDetail = document.getElementById('btnDetail');
    let selectedUrl = null;

    function clearSelected() {
      rows.forEach(r => r.classList.remove('row-selected'));
    }

    rows.forEach(row => {
      row.addEventListener('click', function () {
        clearSelected();
        row.classList.add('row-selected');

        selectedUrl = row.dataset.detailUrl || null;
        if (btnDetail) btnDetail.disabled = !selectedUrl;
      });

      row.addEventListener('dblclick', function () {
        const url = row.dataset.detailUrl;
        if (url) window.location.href = url;
      });
    });

    if (btnDetail) {
      btnDetail.addEventListener('click', function () {
        if (selectedUrl) window.location.href = selectedUrl;
      });
    }
  });
</script>
@endsection