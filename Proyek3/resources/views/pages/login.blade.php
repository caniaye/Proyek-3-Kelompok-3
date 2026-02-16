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
  <div class="panel">
    <div class="login-wrap">

      {{-- Kiri: gambar/ilustrasi --}}
      <div class="login-box">
        <div class="text-center">
          {{-- ganti ini jadi <img src="..." /> kalau logo sudah ada --}}
          <div class="fw-bold text-primary">[Logo / Gambar LPG di sini]</div>
        </div>
      </div>

      {{-- Kanan: form --}}
      <div class="login-card">
        <h1 class="display-6 fw-bold text-secondary mb-4">ADMIN</h1>

        <div class="mb-3">
          <label class="form-label text-muted">Email / Username</label>
          <input class="form-control inp" type="text" placeholder="">
        </div>

        <div class="mb-4">
          <label class="form-label text-muted">Password</label>
          <input class="form-control inp" type="password" placeholder="">
        </div>

        <button class="btn-login">Login</button>
      </div>

    </div>
  </div>
@endsection
