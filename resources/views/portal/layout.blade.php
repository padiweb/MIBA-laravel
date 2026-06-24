<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="theme-color" content="#1d4ed8">
  <title>{{ $app_name ?? 'MIBA' }} · {{ $title ?? 'Portal Santri' }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('media/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/bootstrap-datepicker.min.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/portal.css') }}">
  <script src="{{ asset('media/js/jquery.min.js') }}"></script>
</head>
<body>

<div class="p-shell">

  {{-- ── Top Bar ── --}}
  <header class="p-topbar">
    <div class="p-topbar-title">{{ $title ?? 'Dashboard' }}</div>
    <a href="{{ route('portal.profile') }}" class="p-topbar-avatar" title="Profil">
      @if($student_img ?? null)
        <img src="{{ asset('uploads/student/'.($student_img ?? '')) }}" alt="Foto">
      @else
        {{ strtoupper(substr($student_name ?? 'S', 0, 1)) }}
      @endif
    </a>
  </header>

  {{-- ── Flash Messages ── --}}
  <div style="max-width:480px;margin:0 auto;padding:0 var(--p-space-4)">
    @if(session('success'))
      <div class="p-alert p-alert-success" id="p-flash">
        <i class="fa fa-check-circle"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif
    @if(session('failed'))
      <div class="p-alert p-alert-danger" id="p-flash">
        <i class="fa fa-exclamation-circle"></i>
        <span>{{ session('failed') }}</span>
      </div>
    @endif
    @if($errors->any())
      <div class="p-alert p-alert-danger">
        <i class="fa fa-exclamation-circle"></i>
        <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
      </div>
    @endif
  </div>

  {{-- ── Content ── --}}
  <main class="p-content">
    @yield('content')
  </main>

  {{-- ── Bottom Navigation ── --}}
  <nav class="p-bottomnav">
    <a href="{{ route('portal.dashboard') }}"
       class="p-bnav-item {{ request()->routeIs('portal.dashboard') ? 'active' : '' }}">
      <i class="fa fa-home"></i>
      <span>Beranda</span>
    </a>
    <a href="{{ route('portal.payout') }}"
       class="p-bnav-item {{ request()->routeIs('portal.payout') ? 'active' : '' }}">
      <i class="fa fa-money"></i>
      <span>Tagihan</span>
    </a>
    <a href="{{ route('portal.profile') }}"
       class="p-bnav-item {{ request()->routeIs('portal.profile') ? 'active' : '' }}">
      <i class="fa fa-user-circle"></i>
      <span>Profil</span>
    </a>
    <a href="{{ route('portal.logout') }}"
       class="p-bnav-item"
       onclick="return confirm('Yakin ingin keluar?')">
      <i class="fa fa-sign-out"></i>
      <span>Keluar</span>
    </a>
  </nav>

</div>{{-- /p-shell --}}

<script src="{{ asset('media/js/bootstrap-datepicker.min.js') }}"></script>
<script>
$(function(){
  // Auto-dismiss flash
  setTimeout(function(){ $('#p-flash').fadeOut(400); }, 3500);
  // Datepicker
  $('.date-pick').datepicker({ format:'yyyy-mm-dd', autoclose:true, todayHighlight:true });
});
</script>
@stack('scripts')
</body>
</html>
