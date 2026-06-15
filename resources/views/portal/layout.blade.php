<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>{{ $app_name ?? '' }} · Portal Santri{{ isset($title) ? ' · '.$title : '' }}</title>
  <link rel="stylesheet" href="{{ asset('media/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/bootstrap-datepicker.min.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/miba-ui.css') }}">
  <style>
    .miba-sidebar { background-image: linear-gradient(160deg, #1e40af 0%, #1e3a8a 100%); }
    .miba-sidebar-brand .brand-text small { display:block; }
    :root { --primary:#2563eb; --primary-dark:#1d4ed8; --primary-light:#60a5fa; --primary-xlight:#dbeafe; --sidebar-bg:#1e40af; --bg:#f0f7ff; --border:#dbeafe; --border-dark:#93c5fd; }
    .miba-stat-icon.blue { background:#dbeafe; color:#1d4ed8; }
    .btn-primary-miba { background:#2563eb; }
    .btn-primary-miba:hover { background:#1d4ed8; }
    .miba-input:focus, .miba-select:focus, .miba-textarea:focus { border-color:#60a5fa; box-shadow:0 0 0 3px rgba(96,165,250,.15); }
    .miba-card-title i { color:#2563eb; }
  </style>
  <script src="{{ asset('media/js/jquery.min.js') }}"></script>
</head>
<body>
<div class="miba-wrapper">
  <div class="miba-sidebar-overlay" id="sidebarOverlay"></div>
  <aside class="miba-sidebar" id="mainSidebar">
    <a href="{{ route('portal.dashboard') }}" class="miba-sidebar-brand">
      <div class="brand-icon"><i class="fa fa-graduation-cap"></i></div>
      <div class="brand-text">{{ $app_name ?? '' }}<small>Portal Santri</small></div>
    </a>
    <nav class="miba-sidebar-nav">
      <a href="{{ route('portal.dashboard') }}" class="miba-nav-item {{ request()->routeIs('portal.dashboard')?'active':'' }}"><i class="fa fa-th"></i> Dashboard</a>
      <a href="{{ route('portal.payout') }}" class="miba-nav-item {{ request()->routeIs('portal.payout')?'active':'' }}"><i class="fa fa-calendar"></i> Cek Pembayaran</a>
      <a href="{{ route('portal.profile') }}" class="miba-nav-item {{ request()->routeIs('portal.profile*')?'active':'' }}"><i class="fa fa-user"></i> Profil Saya</a>
      <div class="miba-nav-section">Akun</div>
      <a href="{{ route('portal.logout') }}" class="miba-nav-item"><i class="fa fa-sign-out"></i> Keluar</a>
    </nav>
  </aside>
  <div class="miba-main">
    <header class="miba-header">
      <button class="miba-header-toggle" id="sidebarToggle"><i class="fa fa-bars"></i></button>
      <div class="miba-breadcrumb"><span class="page-title">{{ $title ?? 'Dashboard' }}</span></div>
      <div class="miba-header-right">
        <a href="{{ route('login') }}" class="miba-header-icon" title="Login Admin"><i class="fa fa-building"></i></a>
        <div style="display:flex;align-items:center;gap:8px;padding:4px 10px 4px 4px;border:1px solid var(--border);border-radius:999px;background:var(--bg)">
          <div style="width:28px;height:28px;border-radius:50%;background:var(--primary-xlight);display:flex;align-items:center;justify-content:center;color:var(--primary);font-weight:700;font-size:12px">{{ strtoupper(substr($student_name??'S',0,1)) }}</div>
          <span style="font-size:13px;font-weight:500;color:var(--text-primary)">{{ ucwords($student_name??'') }}</span>
        </div>
      </div>
    </header>
    @if(session('success'))<div style="padding:12px 24px 0"><div class="miba-alert miba-alert-success"><i class="fa fa-check-circle"></i> {{ session('success') }}</div></div>@endif
    @if(session('failed'))<div style="padding:12px 24px 0"><div class="miba-alert miba-alert-danger"><i class="fa fa-exclamation-circle"></i> {{ session('failed') }}</div></div>@endif
    @if($errors->any())<div style="padding:12px 24px 0"><div class="miba-alert miba-alert-danger"><i class="fa fa-exclamation-circle"></i> @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div></div>@endif
    <main class="miba-content">@yield('content')</main>
    <footer class="miba-footer"><span>&copy; {{ date('Y') }} {{ $app_name ?? '' }}</span><span>Portal Santri</span></footer>
  </div>
  <nav class="miba-bottom-nav">
    <div class="miba-bottom-nav-inner">
      <a href="{{ route('portal.dashboard') }}" class="miba-bnav-item {{ request()->routeIs('portal.dashboard')?'active':'' }}"><i class="fa fa-home"></i><span>Home</span></a>
      <a href="{{ route('portal.payout') }}" class="miba-bnav-item {{ request()->routeIs('portal.payout')?'active':'' }}"><i class="fa fa-calendar"></i><span>Tagihan</span></a>
      <a href="{{ route('portal.profile') }}" class="miba-bnav-item {{ request()->routeIs('portal.profile*')?'active':'' }}"><i class="fa fa-user-circle"></i><span>Profil</span></a>
    </div>
  </nav>
</div>
<script src="{{ asset('media/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('media/js/bootstrap-datepicker.min.js') }}"></script>
<script>
$(function(){
  $(".date-pick").datepicker({format:"yyyy-mm-dd",autoclose:true,todayHighlight:true});
  $('#sidebarToggle,#sidebarOverlay').on('click',function(){$('#mainSidebar').toggleClass('open');$('#sidebarOverlay').toggleClass('show');});
  setTimeout(function(){$('.miba-alert').fadeOut(400);},4000);
});
</script>
@stack('scripts')
</body>
</html>
