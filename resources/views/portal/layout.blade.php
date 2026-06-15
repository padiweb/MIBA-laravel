<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>{{ $app_name ?? '' }} · {{ $title ?? 'Portal Santri' }}</title>
  <link rel="stylesheet" href="{{ asset('media/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/bootstrap-datepicker.min.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/miba-ui.css') }}">
  <script src="{{ asset('media/js/jquery.min.js') }}"></script>
  <style>
    /* ── Portal color override ── */
    :root {
      --primary: #2563eb; --primary-dark: #1d4ed8;
      --primary-light: #60a5fa; --primary-xlight: #dbeafe;
      --sidebar-bg: #1e40af; --bg: #f0f7ff;
      --border: #dbeafe; --border-dark: #93c5fd;
    }
    .miba-sidebar { background: linear-gradient(160deg,#1e40af 0%,#1e3a8a 100%); }
    .miba-card-title i, .miba-stat-icon.blue i { color: #2563eb; }
    .btn-primary-miba { background: #2563eb; }
    .btn-primary-miba:hover { background: #1d4ed8; box-shadow: 0 4px 14px rgba(37,99,235,.3); }
    .miba-input:focus,.miba-select:focus,.miba-textarea:focus { border-color:#60a5fa; box-shadow:0 0 0 3px rgba(96,165,250,.15); }
    .miba-nav-item.active::before { background: #60a5fa; }

    /* ════════════════════════════════════
       MOBILE APP-LIKE OVERRIDES (≤768px)
       ════════════════════════════════════ */
    @media (max-width: 768px) {
      /* Sembunyikan sidebar di mobile, ganti dengan bottom nav */
      .miba-sidebar { display: none !important; }
      .miba-sidebar-overlay { display: none !important; }
      .miba-main { margin-left: 0 !important; }

      /* Header mobile — compact top bar */
      .miba-header {
        height: 56px;
        padding: 0 16px;
        background: #1e40af;
        border-bottom: none;
        box-shadow: 0 2px 12px rgba(30,64,175,.25);
      }
      .miba-header-toggle { display: none !important; }
      .miba-breadcrumb .page-title {
        color: #fff !important;
        font-size: 16px;
        font-weight: 700;
      }
      .miba-user-btn {
        background: rgba(255,255,255,.15) !important;
        border-color: rgba(255,255,255,.2) !important;
        color: #fff !important;
        padding: 4px 10px 4px 4px;
      }
      .miba-user-btn span { color: #fff !important; font-size:12px; }
      .miba-header-icon {
        background: rgba(255,255,255,.15) !important;
        border-color: rgba(255,255,255,.2) !important;
        color: #fff !important;
      }

      /* Content area */
      .miba-content {
        padding: 14px 14px 80px;
        background: var(--bg);
      }

      /* Bottom nav — app style */
      .miba-bottom-nav {
        display: block !important;
        background: #fff;
        border-top: none;
        box-shadow: 0 -4px 24px rgba(0,0,0,.1);
        height: 64px;
        padding-bottom: env(safe-area-inset-bottom, 0px);
      }
      .miba-bottom-nav-inner { height: 100%; align-items: stretch; }
      .miba-bnav-item {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        color: #94a3b8;
        font-size: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: color .2s;
        position: relative;
      }
      .miba-bnav-item i { font-size: 22px; line-height: 1; }
      .miba-bnav-item.active { color: #2563eb; }
      .miba-bnav-item.active i { color: #2563eb; }
      /* Active indicator dot */
      .miba-bnav-item.active::after {
        content: '';
        position: absolute;
        bottom: 4px;
        width: 4px; height: 4px;
        border-radius: 50%;
        background: #2563eb;
      }

      /* Cards jadi lebih compact di mobile */
      .miba-card { border-radius: 16px; margin-bottom: 12px; }
      .miba-card-header { padding: 14px 16px; }
      .miba-card-title { font-size: 14px; }
      .miba-card-body { padding: 14px 16px; }

      /* Stat grid 2 kolom di mobile */
      .miba-stat-grid { grid-template-columns: 1fr 1fr !important; gap: 10px !important; }
      .miba-stat { padding: 14px; border-radius: 14px; }
      .miba-stat-icon { width: 38px; height: 38px; border-radius: 10px; font-size: 16px; }
      .miba-stat-value { font-size: 15px !important; }
      .miba-stat-label { font-size: 11px; }

      /* Table scroll horizontal */
      .miba-table-wrap { -webkit-overflow-scrolling: touch; }
      .miba-table th, .miba-table td { padding: 10px 12px; font-size: 12px; }

      /* Alert */
      .miba-alert { font-size: 12px; padding: 10px 14px; border-radius: 10px; }

      /* Footer hide di mobile */
      .miba-footer { display: none; }
    }

    /* ── Badge counter untuk bottom nav ── */
    .bnav-badge {
      position: absolute;
      top: 4px;
      right: 24px;
      background: #ef4444;
      color: #fff;
      font-size: 9px;
      font-weight: 700;
      width: 16px; height: 16px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      border: 2px solid #fff;
    }
  </style>
</head>
<body>
<div class="miba-wrapper">

  {{-- Sidebar (hanya tampil tablet/desktop) --}}
  <div class="miba-sidebar-overlay" id="sidebarOverlay"></div>
  <aside class="miba-sidebar" id="mainSidebar">
    <a href="{{ route('portal.dashboard') }}" class="miba-sidebar-brand">
      <div class="brand-icon"><i class="fa fa-graduation-cap"></i></div>
      <div class="brand-text">{{ $app_name ?? '' }}<small>Portal Santri</small></div>
    </a>
    <nav class="miba-sidebar-nav">
      <a href="{{ route('portal.dashboard') }}" class="miba-nav-item {{ request()->routeIs('portal.dashboard')?'active':'' }}">
        <i class="fa fa-home"></i> Dashboard
      </a>
      <a href="{{ route('portal.payout') }}" class="miba-nav-item {{ request()->routeIs('portal.payout')?'active':'' }}">
        <i class="fa fa-money"></i> Tagihan & Pembayaran
      </a>
      <a href="{{ route('portal.profile') }}" class="miba-nav-item {{ request()->routeIs('portal.profile*')?'active':'' }}">
        <i class="fa fa-user-circle"></i> Profil Saya
      </a>
      <div class="miba-nav-section">Akun</div>
      <a href="{{ route('portal.logout') }}" class="miba-nav-item">
        <i class="fa fa-sign-out"></i> Keluar
      </a>
    </nav>
  </aside>

  <div class="miba-main">
    {{-- Header --}}
    <header class="miba-header">
      <button class="miba-header-toggle" id="sidebarToggle"><i class="fa fa-bars"></i></button>
      <div class="miba-breadcrumb">
        <span class="page-title">{{ $title ?? 'Dashboard' }}</span>
      </div>
      <div class="miba-header-right">
        {{-- Avatar siswa --}}
        <div style="display:flex;align-items:center;gap:8px;padding:4px 10px 4px 4px;border:1px solid rgba(255,255,255,.2);border-radius:999px;background:rgba(255,255,255,.1)">
          @if($student_img ?? null)
            <img src="{{ asset('uploads/student/'.($student_img ?? '')) }}" style="width:28px;height:28px;border-radius:50%;object-fit:cover">
          @else
            <div style="width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:12px">
              {{ strtoupper(substr($student_name ?? 'S', 0, 1)) }}
            </div>
          @endif
          <span style="font-size:12px;font-weight:600;color:#fff;max-width:100px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
            {{ ucwords($student_name ?? '') }}
          </span>
        </div>
      </div>
    </header>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div style="padding:12px 14px 0">
      <div class="miba-alert miba-alert-success"><i class="fa fa-check-circle"></i> {{ session('success') }}</div>
    </div>
    @endif
    @if(session('failed'))
    <div style="padding:12px 14px 0">
      <div class="miba-alert miba-alert-danger"><i class="fa fa-exclamation-circle"></i> {{ session('failed') }}</div>
    </div>
    @endif
    @if($errors->any())
    <div style="padding:12px 14px 0">
      <div class="miba-alert miba-alert-danger">
        <i class="fa fa-exclamation-circle"></i>
        <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
      </div>
    </div>
    @endif

    {{-- Content --}}
    <main class="miba-content">
      @yield('content')
    </main>

    <footer class="miba-footer">
      <span>&copy; {{ date('Y') }} {{ $app_name ?? '' }}</span>
      <span>Portal Santri</span>
    </footer>
  </div>

  {{-- Bottom Nav (mobile only) --}}
  <nav class="miba-bottom-nav">
    <div class="miba-bottom-nav-inner">
      <a href="{{ route('portal.dashboard') }}"
         class="miba-bnav-item {{ request()->routeIs('portal.dashboard') ? 'active' : '' }}">
        <i class="fa fa-home"></i>
        <span>Beranda</span>
      </a>
      <a href="{{ route('portal.payout') }}"
         class="miba-bnav-item {{ request()->routeIs('portal.payout') ? 'active' : '' }}">
        <i class="fa fa-money"></i>
        <span>Tagihan</span>
      </a>
      <a href="{{ route('portal.profile') }}"
         class="miba-bnav-item {{ request()->routeIs('portal.profile') ? 'active' : '' }}">
        <i class="fa fa-user-circle"></i>
        <span>Profil</span>
      </a>
      <a href="{{ route('portal.logout') }}" class="miba-bnav-item"
         onclick="return confirm('Yakin ingin keluar?')">
        <i class="fa fa-sign-out"></i>
        <span>Keluar</span>
      </a>
    </div>
  </nav>

</div>

<script src="{{ asset('media/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('media/js/bootstrap-datepicker.min.js') }}"></script>
<script>
$(function(){
  // Datepicker
  $(".date-pick, .input-group.date").datepicker({format:"yyyy-mm-dd", autoclose:true, todayHighlight:true});

  // Sidebar toggle (tablet/desktop)
  $('#sidebarToggle, #sidebarOverlay').on('click', function(){
    $('#mainSidebar').toggleClass('open');
    $('#sidebarOverlay').toggleClass('show');
  });

  // Auto-dismiss alerts
  setTimeout(function(){ $('.miba-alert').slideUp(300); }, 4000);
});
</script>
@stack('scripts')
</body>
</html>
