@extends('layouts.app')
@section('title','Login')

@push('styles')
<style>
  .login-wrap{
    display:grid;
    grid-template-columns: 1.1fr .9fr;
    gap: 22px;
  }
  .login-box{
    background:#cfe8e6;
    border-radius:18px;
    padding:28px;
    min-height: 520px;
    display:flex;
    align-items:center;
    justify-content:center;
  }
  .login-card{
    background:#cfe8e6;
    border-radius:18px;
    padding:34px;
    min-height: 520px;
  }
  .inp{
    background:rgba(0,0,0,.08);
    border:none;
    border-radius:999px;
    padding:14px 18px;
  }
  .btn-login{
    background:#2f6d6b;
    color:#fff;
    border:none;
    border-radius:999px;
    padding:12px 18px;
    width: 180px;
  }
  @media(max-width: 992px){
    .login-wrap{ grid-template-columns: 1fr; }
  }
</style>
@endpush

@section('content')
<div class="login-wrap">

  {{-- Kiri --}}
  <div class="login-box">
    <div class="text-center w-100">
      <img
        src="{{ asset('image/Logo Berdiri.png') }}"
        alt="Logo Pangkalan LPG 3KG"
        style="max-width: 320px; width: 100%; height: auto;"
      />
    </div>
  </div>

  {{-- Kanan --}}
  <div class="login-card">
    <h1 class="display-6 fw-bold text-secondary mb-4">ADMIN</h1>

    <form method="POST" action="{{ route('login.post') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label text-muted">Email</label>
        <input class="form-control inp" type="email" name="email" value="{{ old('email') }}" required>
        @error('email')
          <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-4">
        <label class="form-label text-muted">Password</label>
        <input class="form-control inp" type="password" name="password" required>
        @error('password')
          <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
      </div>

      <button class="btn-login" type="submit">Login</button>
    </form>
  </div>

</div>
@endsection
