@extends('layouts.app')
@section('title','Monitoring Pengantaran')

@section('content')
<div class="panel">

  @if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
  @endif

  <div class="card-soft">
    <div class="fw-semibold mb-3">Data Pengiriman</div>

    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="text-muted">
          <tr>
            <th>Nama Kurir</th>
            <th>Nama Pelanggan</th>
            <th style="width:180px">Status</th>
            <th style="width:220px">Waktu Verifikasi</th>
            <th style="width:140px">Aksi</th>
          </tr>
        </thead>

        <tbody>
          @forelse($pengantarans as $pg)
          <tr>
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
            <td>
              <button class="btn-soft btn-gray btnUpdate"
                      data-bs-toggle="modal" data-bs-target="#modalUpdate"
                      data-id="{{ $pg->id }}"
                      data-kurir="{{ $pg->kurir_id ?? '' }}"
                      data-status="{{ $pg->status }}">
                Update
              </button>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">Belum ada data pengantaran.</td>
          </tr>
          @endforelse
        </tbody>

      </table>
    </div>
  </div>
</div>

{{-- MODAL UPDATE --}}
<div class="modal fade" id="modalUpdate" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form id="formUpdate" class="modal-content" method="POST">
      @csrf
      @method('PUT')

      <div class="modal-header">
        <h5 class="modal-title">Update Pengantaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Kurir</label>
          <select name="kurir_id" id="kurir_id" class="form-control">
            <option value="">-- Belum ditentukan --</option>
            @foreach($kurirs as $k)
              <option value="{{ $k->id }}">{{ $k->kode }} - {{ $k->nama }}</option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" id="status" class="form-control" required>
            <option value="belum_dikirim">Belum Dikirim</option>
            <option value="dalam_perjalanan">Dalam Perjalanan</option>
            <option value="berhasil">Berhasil</option>
          </select>
          <div class="form-text">
            Kalau status jadi <b>Berhasil</b>, waktu verifikasi akan otomatis terisi.
          </div>
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
    const modal = document.getElementById('modalUpdate');
    const form = document.getElementById('formUpdate');
    const kurirSelect = document.getElementById('kurir_id');
    const statusSelect = document.getElementById('status');

    modal.addEventListener('show.bs.modal', function (event) {
      const btn = event.relatedTarget;

      const id = btn.getAttribute('data-id');
      const kurir = btn.getAttribute('data-kurir');
      const status = btn.getAttribute('data-status');

      form.action = `/monitoring/${id}`;
      kurirSelect.value = kurir || '';
      statusSelect.value = status || 'belum_dikirim';
    });
  });
</script>
@endsection