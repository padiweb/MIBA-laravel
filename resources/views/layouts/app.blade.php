<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>{{ $app_name ?? config('app.name') }}{{ isset($title) ? ' · '.$title : '' }}</title>
  <link rel="stylesheet" href="{{ asset('media/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/bootstrap-datepicker.min.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/miba-ui.css') }}">
  <script src="{{ asset('media/js/jquery.min.js') }}"></script>
</head>
<body>
<div class="miba-wrapper">

  {{-- ── Sidebar ── --}}
  <div class="miba-sidebar-overlay" id="sidebarOverlay"></div>
  <aside class="miba-sidebar" id="mainSidebar">
    <a href="{{ route('dashboard') }}" class="miba-sidebar-brand">
      <div class="brand-icon">
        @if(($app_logo ?? '') && file_exists(public_path('uploads/school/'.($app_logo ?? ''))))
          <img src="{{ asset('uploads/school/'.($app_logo ?? '')) }}" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">
        @else
          <i class="fa fa-graduation-cap"></i>
        @endif
      </div>
      <div class="brand-text">
        {{ $app_name ?? config('app.name') }}
        <small>Sistem Informasi</small>
      </div>
    </a>

    <nav class="miba-sidebar-nav">
      @include('layouts.sidebar')
    </nav>
  </aside>

  {{-- ── Main ── --}}
  <div class="miba-main">

    {{-- Header --}}
    <header class="miba-header">
      <button class="miba-header-toggle" id="sidebarToggle">
        <i class="fa fa-bars"></i>
      </button>
      <div class="miba-breadcrumb">
        <span class="page-title">{{ $title ?? 'Dashboard' }}</span>
      </div>
      <div class="miba-header-right">
        <a href="{{ route('portal.login') }}" class="miba-header-icon" title="Portal Siswa">
          <i class="fa fa-graduation-cap"></i>
        </a>
        <div style="position:relative">
          <button class="miba-user-btn" id="userMenuBtn" type="button">
            @if(session('user_image'))
              <img src="{{ asset('uploads/users/'.session('user_image')) }}" class="miba-avatar">
            @else
              <div class="miba-avatar" style="background:var(--primary-xlight);display:flex;align-items:center;justify-content:center;color:var(--primary);font-weight:700;font-size:12px;">
                {{ strtoupper(substr(session('user_fullname','?'),0,1)) }}
              </div>
            @endif
            <span>{{ ucwords(session('user_fullname')) }}</span>
            <i class="fa fa-chevron-down" style="font-size:10px;color:var(--text-muted)"></i>
          </button>
          <div class="miba-user-dropdown" id="userDropdown">
            <div class="miba-dropdown-header">
              <div class="name">{{ ucwords(session('user_fullname')) }}</div>
              <div class="role">{{ ucfirst(session('user_rolename')) }}</div>
            </div>
            <a href="{{ route('profile.index') }}" class="miba-dropdown-item">
              <i class="fa fa-user-circle"></i> Profil Saya
            </a>
            <div class="miba-dropdown-divider"></div>
            <a href="{{ route('logout') }}" class="miba-dropdown-item danger">
              <i class="fa fa-sign-out"></i> Keluar
            </a>
          </div>
        </div>
      </div>
    </header>

    {{-- Flash messages --}}
    @if(session('success'))
    <div style="padding:12px 24px 0">
      <div class="miba-alert miba-alert-success">
        <i class="fa fa-check-circle"></i>
        <span>{{ session('success') }}</span>
      </div>
    </div>
    @endif
    @if(session('failed'))
    <div style="padding:12px 24px 0">
      <div class="miba-alert miba-alert-danger">
        <i class="fa fa-exclamation-circle"></i>
        <span>{{ session('failed') }}</span>
      </div>
    </div>
    @endif
    @if($errors->any())
    <div style="padding:12px 24px 0">
      <div class="miba-alert miba-alert-danger">
        <i class="fa fa-exclamation-circle"></i>
        <div>
          @foreach($errors->all() as $e)
            <div>{{ $e }}</div>
          @endforeach
        </div>
      </div>
    </div>
    @endif

    {{-- Content --}}
    <main class="miba-content">
      @yield('content')
    </main>

    <footer class="miba-footer">
      <span>&copy; {{ date('Y') }} {{ $app_name ?? config('app.name') }}</span>
      <span>MIBA v2.0</span>
    </footer>
  </div>

  {{-- Mobile bottom nav --}}
  <nav class="miba-bottom-nav">
    <div class="miba-bottom-nav-inner">
      <a href="{{ route('dashboard') }}" class="miba-bnav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fa fa-home"></i><span>Home</span>
      </a>
      @if(($user_role_id ?? 0) != 3)
      <a href="{{ route('payout.index') }}" class="miba-bnav-item {{ request()->routeIs('payout.*') ? 'active' : '' }}">
        <i class="fa fa-exchange"></i><span>Transaksi</span>
      </a>
      @endif
      @if(($user_role_id ?? 0) != 1)
      <a href="{{ route('report.index') }}" class="miba-bnav-item {{ request()->routeIs('report.*') ? 'active' : '' }}">
        <i class="fa fa-bar-chart"></i><span>Laporan</span>
      </a>
      @endif
      @if(($user_role_id ?? 0) == 1)
      <a href="{{ route('student.index') }}" class="miba-bnav-item {{ request()->routeIs('student.*') ? 'active' : '' }}">
        <i class="fa fa-users"></i><span>Siswa</span>
      </a>
      @endif
      <a href="{{ route('profile.index') }}" class="miba-bnav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
        <i class="fa fa-user-circle"></i><span>Profil</span>
      </a>
    </div>
  </nav>

</div>{{-- /miba-wrapper --}}

<script src="{{ asset('media/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('media/js/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('media/js/bootstrap-datepicker.min.js') }}"></script>
<script>
$(function(){
  // Datepicker
  $(".input-group.date, .date-pick").datepicker({ format:"yyyy-mm-dd", autoclose:true, todayHighlight:true });

  // Sidebar toggle (mobile)
  $('#sidebarToggle, #sidebarOverlay').on('click', function(){
    $('#mainSidebar').toggleClass('open');
    $('#sidebarOverlay').toggleClass('show');
  });

  // User dropdown
  $('#userMenuBtn').on('click', function(e){
    e.stopPropagation();
    $('#userDropdown').toggleClass('show');
  });
  $(document).on('click', function(){ $('#userDropdown').removeClass('show'); });

  // Sidebar submenu
  $('[data-sub]').on('click', function(){
    var target = $(this).data('sub');
    var $sub = $('#'+target);
    var isOpen = $sub.hasClass('open');
    $('.miba-nav-sub').removeClass('open');
    $('[data-sub]').attr('aria-expanded','false');
    if(!isOpen){
      $sub.addClass('open');
      $(this).attr('aria-expanded','true');
    }
  });

  // Auto-open active submenu
  $('.miba-nav-sub').each(function(){
    if($(this).find('.active').length){
      $(this).addClass('open');
      $('[data-sub="'+$(this).attr('id')+'"]').attr('aria-expanded','true');
    }
  });

  // Alert auto-dismiss
  setTimeout(function(){ $('.miba-alert').fadeOut(400); }, 4000);
});
</script>
@stack('scripts')
</body>
</html>
