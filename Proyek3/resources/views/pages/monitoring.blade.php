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
            <th style="width:220px">Atur Kurir</th>
            <th style="width:220px">Status</th>
            <th style="width:220px">Waktu Verifikasi</th>
            <th style="width:140px">Aksi</th>
          </tr>
        </thead>

        <tbody>
          @forelse($pengantarans as $pg)
          <tr>
            <td>{{ $pg->kurir?->nama ?? 'Belum dipilih' }}</td>
            <td>{{ $pg->pesanan?->pelanggan?->nama ?? '-' }}</td>

            <td>
              <form method="POST" action="{{ route('monitoring.update', $pg->id) }}" class="d-flex gap-2">
                @csrf
                @method('PATCH')

                <select name="kurir_id" class="form-control">
                  <option value="">-- pilih kurir --</option>
                  @foreach($kurirs as $k)
                    <option value="{{ $k->id }}" @selected($pg->kurir_id === $k->id)>
                      {{ $k->nama }}
                    </option>
                  @endforeach
                </select>
            </td>

            <td>
                <select name="status" class="form-control">
                  <option value="belum_dikirim" @selected($pg->status==='belum_dikirim')>Belum Dikirim</option>
                  <option value="dalam_perjalanan" @selected($pg->status==='dalam_perjalanan')>Dalam Perjalanan</option>
                  <option value="berhasil" @selected($pg->status==='berhasil')>Berhasil</option>
                  <option value="dibatalkan" @selected($pg->status==='dibatalkan')>Dibatalkan</option>
                </select>
            </td>

            <td>
              {{ $pg->waktu_verifikasi ? \Carbon\Carbon::parse($pg->waktu_verifikasi)->format('d M Y, H:i') : '-' }}
            </td>

            <td>
                <button type="submit" class="btn-soft btn-blue">Update</button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-4">Belum ada data pengantaran.</td>
          </tr>
          @endforelse
        </tbody>

      </table>
    </div>
  </div>
</div>
@endsection