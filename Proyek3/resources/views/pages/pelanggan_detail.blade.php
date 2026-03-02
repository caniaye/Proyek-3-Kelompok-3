@extends('layouts.app')
@section('title','Detail Pelanggan')

@section('content')
<div class="panel">

  @if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
  @endif

  <div class="card-soft">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="fw-semibold">Detail Pelanggan</div>
      <a href="{{ route('pelanggan.index') }}" class="btn-soft">Kembali</a>
    </div>

    <form method="POST" action="{{ route('pelanggan.update', $pelanggan->id) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="d-flex align-items-center gap-3 mb-3">
        <img src="{{ $pelanggan->fotoUrl() }}" alt="foto"
             style="width:80px;height:80px;border-radius:50%;object-fit:cover;">
        <div class="flex-grow-1">
          <label class="form-label">Ganti Foto (opsional)</label>
          <input type="file" name="foto" class="form-control" accept="image/*">
        </div>
      </div>

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nama</label>
          <input type="text" name="nama" class="form-control"
                 value="{{ old('nama', $pelanggan->nama) }}" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">No HP</label>
          <input type="text" name="no_hp" class="form-control"
                 value="{{ old('no_hp', $pelanggan->no_hp) }}">
        </div>

        <div class="col-12">
          <label class="form-label">Alamat</label>
          <textarea name="alamat" class="form-control" rows="4" required>{{ old('alamat', $pelanggan->alamat) }}</textarea>
        </div>
      </div>

      <div class="d-flex justify-content-end mt-3">
        <button type="submit" class="btn-soft btn-green">Simpan Perubahan</button>
      </div>
    </form>
  </div>

</div>
@endsection