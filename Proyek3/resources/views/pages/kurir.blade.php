@extends('layouts.app')
@section('title','Manajemen Kurir')

@section('content')
<div class="panel">

  @if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
  @endif

  <div class="d-flex justify-content-end mb-3">
    <button class="btn-soft btn-green" data-bs-toggle="modal" data-bs-target="#modalTambahKurir">
      Tambah
    </button>
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
            <th style="width:240px">Aksi</th>
          </tr>
        </thead>

        <tbody>
          @forelse($kurirs as $i => $kurir)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $kurir->kode }}</td>

            {{-- ✅ Nama + Foto --}}
            <td>
              <div class="d-flex align-items-center gap-2">
                <img src="{{ $kurir->fotoUrl() }}" alt="foto"
                     style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                <div>{{ $kurir->nama }}</div>
              </div>
            </td>

            <td>
              @if($kurir->status === 'aktif')
                <span class="badge-pill b-active">Aktif</span>
              @elseif($kurir->status === 'nonaktif')
                <span class="badge-pill b-nonactive">Non Aktif</span>
              @else
                <span class="badge-pill bg-dark text-white">Resign</span>
              @endif
            </td>

            <td class="d-flex gap-2">

              @if($kurir->status === 'resign')
                <button class="btn-soft btn-gray" disabled style="opacity:.55;cursor:not-allowed;">Edit</button>
                <button class="btn-soft btn-dark" disabled style="opacity:.55;cursor:not-allowed;">Hapus</button>
              @else
                <button
                  class="btn-soft btn-gray"
                  data-bs-toggle="modal"
                  data-bs-target="#modalEditKurir"
                  data-id="{{ $kurir->id }}"
                  data-kode="{{ $kurir->kode }}"
                  data-nama="{{ $kurir->nama }}"
                  data-status="{{ $kurir->status }}"
                >
                  Edit
                </button>

                <form action="{{ route('kurir.destroy', $kurir->id) }}" method="POST"
                      onsubmit="return confirm('Yakin hapus kurir ini? (status akan menjadi resign)')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-soft btn-dark">Hapus</button>
                </form>
              @endif

            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">Belum ada data kurir.</td>
          </tr>
          @endforelse
        </tbody>

      </table>
    </div>
  </div>
</div>

{{-- =======================
     MODAL TAMBAH KURIR
======================= --}}
<div class="modal fade" id="modalTambahKurir" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" method="POST" action="{{ route('kurir.store') }}" enctype="multipart/form-data">
      @csrf

      <div class="modal-header">
        <h5 class="modal-title">Tambah Kurir</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nama Kurir</label>
          <input type="text" name="nama" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Status (Kerja/Libur)</label>
          <select name="status" class="form-control" required>
            <option value="aktif">Aktif (Kerja)</option>
            <option value="nonaktif">Non Aktif (Libur)</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Foto (opsional)</label>
          <input type="file" name="foto" class="form-control" accept="image/*">
          <div class="form-text">jpg/jpeg/png/webp, max 2MB</div>
        </div>

        <div class="form-text">
          ID Kurir dibuat otomatis (KUR001, KUR002, dst).
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn-soft" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn-soft btn-green">Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- =======================
     MODAL EDIT KURIR
======================= --}}
<div class="modal fade" id="modalEditKurir" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form id="formEditKurir" class="modal-content" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="modal-header">
        <h5 class="modal-title">Edit Kurir</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div class="mb-3">
          <label class="form-label">ID Kurir (Kode)</label>
          <input type="text" id="edit_kode_view" class="form-control" disabled>
        </div>

        <div class="mb-3">
          <label class="form-label">Nama Kurir</label>
          <input type="text" id="edit_nama" name="nama" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Status (Kerja/Libur)</label>
          <select id="edit_status" name="status" class="form-control" required>
            <option value="aktif">Aktif (Kerja)</option>
            <option value="nonaktif">Non Aktif (Libur)</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Ganti Foto (opsional)</label>
          <input type="file" name="foto" class="form-control" accept="image/*">
          <div class="form-text">Kalau tidak pilih, foto lama tetap.</div>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn-soft" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn-soft btn-green">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const modalEdit = document.getElementById('modalEditKurir');
    const formEdit = document.getElementById('formEditKurir');

    const editKodeView = document.getElementById('edit_kode_view');
    const editNama = document.getElementById('edit_nama');
    const editStatus = document.getElementById('edit_status');

    modalEdit.addEventListener('show.bs.modal', function (event) {
      const btn = event.relatedTarget;

      const id = btn.getAttribute('data-id');
      const kode = btn.getAttribute('data-kode');
      const nama = btn.getAttribute('data-nama');
      const status = btn.getAttribute('data-status');

      formEdit.action = `/kurir/${id}`;

      editKodeView.value = kode;
      editNama.value = nama;
      editStatus.value = status;
    });
  });
</script>
@endsection