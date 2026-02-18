<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Pangkalan LPG 3KG')</title>

  {{-- Bootstrap --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Bootstrap Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root{
      --sidebar-w: 280px;
      --soft: #f4f6f6;
      --primary-soft: #cfe8e6;
      --text: #444;
    }
    body { background:#fff; color:var(--text); }
    .app-wrap { display:flex; min-height:100vh; }
    .sidebar {
      width: var(--sidebar-w);
      border-right: 1px solid #eaeaea;
      background:#fff;
    }
    .brand {
      padding: 28px 22px;
      font-weight: 800;
      line-height: 1.1;
      color:#1677b7;
      letter-spacing:.2px;
    }
    .brand small { display:block; font-weight:700; font-size: 13px; color:#1677b7; opacity:.9; margin-top:4px;}
    .navy a {
      display:flex; align-items:center; gap:14px;
      padding: 16px 20px;
      color:#6b6b6b;
      text-decoration:none;
      border-radius: 0;
    }
    .navy a:hover { background: #f7fbfb; }
    .navy a.active {
      background: var(--primary-soft);
      color:#2d6b6a;
      font-weight:600;
    }
    .navy .ico { font-size: 26px; width:34px; text-align:center; opacity:.75; }

    .main { flex:1; background:#fff; }
    .topbar {
      height:64px;
      border-bottom: 1px solid #eaeaea;
      display:flex;
      justify-content:flex-end;
      align-items:center;
      padding: 0 24px;
      gap:16px;
    }
    .content { padding: 22px 26px; background:#fff; }
    .panel {
      background: var(--soft);
      border-radius: 12px;
      padding: 18px;
    }
    .card-soft {
      background:#fff;
      border-radius: 12px;
      padding: 16px;
      border: 1px solid #f0f0f0;
    }

    .badge-pill{
      border-radius: 999px;
      padding: 8px 16px;
      font-weight: 600;
      display:inline-block;
      min-width: 130px;
      text-align:center;
    }
    .b-success{ background:#35b164; color:#fff; }
    .b-warning{ background:#c8c400; color:#fff; }
    .b-process{ background:#2c86a6; color:#fff; }
    .b-trip{ background:#3b2fb3; color:#fff; }
    .b-active{ background:#f3a3ad; color:#111; }
    .b-nonactive{ background:#ff3b3b; color:#111; }

    .btn-soft {
      border:none;
      border-radius: 12px;
      padding: 8px 18px;
      font-weight: 600;
    }
    .btn-green { background:#43e06c; color:#0a3b17; }
    .btn-blue { background:#79b7ff; color:#08345d; }
    .btn-gray { background:#d9d9d9; color:#222; }
    .btn-dark { background:#7a6f6f; color:#111; }

    footer {
      padding: 14px 26px;
      border-top: 1px solid #f0f0f0;
      color:#888;
      font-size: 13px;
    }
  </style>

  @stack('styles')
</head>
<body>

<div class="app-wrap">
  {{-- SIDEBAR --}}
  <aside class="sidebar">
    <div class="brand">
      PANGKALAN LPG 3KG
      <small>AAS ASNIAH</small>
    </div>

    <nav class="navy">
      <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <span class="ico"><i class="bi bi-house-door"></i></span> Dashboard
      </a>

      <a href="{{ route('pelanggan.index') }}" class="{{ request()->routeIs('pelanggan.*') ? 'active' : '' }}">
        <span class="ico"><i class="bi bi-briefcase"></i></span> Manajemen Pelanggan
      </a>

      <a href="{{ route('kurir.index') }}" class="{{ request()->routeIs('kurir.*') ? 'active' : '' }}">
        <span class="ico"><i class="bi bi-person-gear"></i></span> Manajemen Kurir
      </a>

      <a href="{{ route('pesanan.index') }}" class="{{ request()->routeIs('pesanan.*') ? 'active' : '' }}">
        <span class="ico"><i class="bi bi-clipboard-check"></i></span> Manajemen Pesanan
      </a>

      <a href="{{ route('monitoring.index') }}" class="{{ request()->routeIs('monitoring.*') ? 'active' : '' }}">
        <span class="ico"><i class="bi bi-truck"></i></span> Monitoring Pengantaran
      </a>

      <a href="{{ route('riwayat.index') }}" class="{{ request()->routeIs('riwayat.*') ? 'active' : '' }}">
        <span class="ico"><i class="bi bi-clock-history"></i></span> Riwayat Pengantaran
      </a>
    </nav>
  </aside>

  {{-- MAIN --}}
  <main class="main">
    {{-- HEADER / TOPBAR --}}
    <header class="topbar">
      @auth
        <button class="btn btn-link text-secondary p-0" title="Notifikasi">
          <i class="bi bi-bell fs-4"></i>
        </button>

        <div class="dropdown">
          <button class="btn btn-link text-secondary p-0" data-bs-toggle="dropdown" aria-expanded="false" title="Akun">
            <i class="bi bi-person-circle fs-4"></i>
          </button>

          <ul class="dropdown-menu dropdown-menu-end">
            <li class="px-3 py-2 text-muted small">
              {{ auth()->user()->name ?? 'Admin' }}
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item text-danger">Logout</button>
              </form>
            </li>
          </ul>
        </div>
      @endauth

      @guest
        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary">
          Login
        </a>
      @endguest
    </header>


    {{-- CONTENT --}}
    <section class="content">
      @yield('content')
    </section>

    {{-- FOOTER --}}
    <footer>
      © {{ date('Y') }} Pangkalan LPG 3KG — AAS ASNIAH
    </footer>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
