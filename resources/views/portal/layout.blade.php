<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ $app_name ?? config('app.name') }} {{ isset($title) ? '| '.$title : '' }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <link rel="stylesheet" href="{{ asset('media/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/skin-purple-light.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/jquery.toast.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/bootstrap-datepicker.min.css') }}">
  <script src="{{ asset('media/js/jquery.min.js') }}"></script>
  <style>
    .bott-bar{display:flex;width:100%;background:#fff;border-top:1px solid #ddd;}
    .content-bar{flex:1;text-align:center;padding:6px 0;color:#555;text-decoration:none;}
    .content-bar.active{color:#605ca8;}
    .icon-bot-bar{font-size:18px;display:block;}
    .text-bot-bar{font-size:11px;margin:0;}
    @media (min-width:768px){ .navbar-fixed-bottom{display:none !important;} }
    body.sidebar-mini.fixed .content-wrapper{padding-bottom:60px;}
  </style>
</head>
<body class="hold-transition skin-purple-light fixed sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <a href="{{ route('portal.dashboard') }}" class="logo">
      <span class="logo-lg pull-left">
        <span class="fa fa-graduation-cap">
          <b style="font-family:Abel;">&nbsp;{{ $app_name ?? config('app.name') }}</b>
        </span>
      </span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              @if($student_img)
                <img src="{{ asset('uploads/student/'.$student_img) }}" class="user-image">
              @else
                <img src="{{ asset('media/img/user.png') }}" class="user-image">
              @endif
              <span class="hidden-xs">{{ ucfirst($student_name) }}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                @if($student_img)
                  <img src="{{ asset('uploads/student/'.$student_img) }}" class="img-circle">
                @else
                  <img src="{{ asset('media/img/user.png') }}" class="img-circle">
                @endif
                <p>
                  {{ ucfirst($student_name) }}
                  <small>{{ $student_nis }}</small>
                </p>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{ route('portal.profile') }}" class="btn btn-default btn-flat">Profil</a>
                </div>
                <div class="pull-right">
                  <a href="{{ route('portal.logout') }}" class="btn btn-default btn-flat">Keluar</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  {{-- Sidebar --}}
  <aside class="main-sidebar">
    <section class="sidebar">
      <ul class="sidebar-menu" data-widget="tree">
        <li class="{{ request()->routeIs('portal.dashboard')?'active':'' }}">
          <a href="{{ route('portal.dashboard') }}">
            <i class="fa fa-th"></i> <span>DASHBOARD</span>
          </a>
        </li>
        <li class="{{ request()->routeIs('portal.payout')?'active':'' }}">
          <a href="{{ route('portal.payout') }}">
            <i class="fa fa-calendar"></i> <span>CEK PEMBAYARAN</span>
          </a>
        </li>
        <li class="{{ request()->routeIs('portal.profile*')?'active':'' }}">
          <a href="{{ route('portal.profile') }}">
            <i class="fa fa-user"></i> <span>PROFIL</span>
          </a>
        </li>
        <li>
          <a href="{{ route('portal.logout') }}">
            <i class="fa fa-sign-out"></i> <span>KELUAR</span>
          </a>
        </li>
      </ul>
    </section>
  </aside>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>{{ $title ?? '' }}</h1>
    </section>
    <section class="content">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('failed'))
        <div class="alert alert-danger">{{ session('failed') }}</div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif
      @yield('content')
    </section>
  </div>

  <footer class="main-footer">
    <div class="pull-right hidden-xs">Portal Siswa</div>
    {{ $app_name ?? '' }}
  </footer>

  {{-- Bottom nav untuk mobile --}}
  <div class="navbar navbar-default navbar-fixed-bottom hidden-lg hidden-md hidden-sm">
    <div class="bott-bar">
      <a class="content-bar {{ request()->routeIs('portal.dashboard')?'active':'' }}" href="{{ route('portal.dashboard') }}">
        <i class="fa fa-th icon-bot-bar"></i>
        <p class="text-bot-bar">Dashboard</p>
      </a>
      <a class="content-bar {{ request()->routeIs('portal.payout')?'active':'' }}" href="{{ route('portal.payout') }}">
        <i class="fa fa-calendar icon-bot-bar"></i>
        <p class="text-bot-bar">Bulanan</p>
      </a>
      <a class="content-bar {{ request()->routeIs('portal.profile*')?'active':'' }}" href="{{ route('portal.profile') }}">
        <i class="fa fa-user icon-bot-bar"></i>
        <p class="text-bot-bar">Profile</p>
      </a>
    </div>
  </div>

</div>

<script src="{{ asset('media/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('media/js/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('media/js/adminlte.min.js') }}"></script>
<script src="{{ asset('media/js/jquery.toast.js') }}"></script>
<script src="{{ asset('media/js/bootstrap-datepicker.min.js') }}"></script>
<script>
  $(".input-group.date").datepicker({ format:"yyyy-mm-dd", autoclose:true, todayHighlight:true });
</script>
@stack('scripts')
</body>
</html>
