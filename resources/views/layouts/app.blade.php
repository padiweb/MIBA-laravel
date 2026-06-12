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
  <link rel="stylesheet" href="{{ asset('media/css/daterangepicker.css') }}">
  <script src="{{ asset('media/js/jquery.min.js') }}"></script>
</head>
<body class="hold-transition skin-purple-light fixed sidebar-mini">
<div class="wrapper">

  {{-- Header --}}
  <header class="main-header">
    <a href="{{ route('dashboard') }}" class="logo">
      <span class="logo-lg pull-left">
        <span class="fa fa-home">
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
              @if(session('user_image'))
                <img src="{{ asset('uploads/users/'.session('user_image')) }}" class="user-image">
              @else
                <img src="{{ asset('media/img/user.png') }}" class="user-image">
              @endif
              <span class="hidden-xs">{{ ucfirst(session('user_fullname')) }}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                @if(session('user_image'))
                  <img src="{{ asset('uploads/users/'.session('user_image')) }}" class="img-circle">
                @else
                  <img src="{{ asset('media/img/user.png') }}" class="img-circle">
                @endif
                <p>
                  {{ ucfirst(session('user_fullname')) }}
                  <small>{{ ucfirst(session('user_rolename')) }}</small>
                  <small>{{ session('user_email') }}</small>
                </p>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{ route('profile.index') }}" class="btn btn-default btn-flat">Profil</a>
                </div>
                <div class="pull-right">
                  <a href="{{ route('logout') }}" class="btn btn-default btn-flat">Keluar</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  {{-- Sidebar --}}
  @include('layouts.sidebar')

  {{-- Content --}}
  <div class="content-wrapper">
    <section class="content-header">
      <h1>{{ $title ?? 'Dashboard' }}</h1>
    </section>
    <section class="content">
      @yield('content')
    </section>
  </div>

  <footer class="main-footer">
    <div class="pull-right hidden-xs">{{ $app_name ?? '' }} v1.0</div>
    &copy; {{ date('Y') }} Padiweb Labs. All rights reserved.
  </footer>
</div>

<script src="{{ asset('media/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('media/js/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('media/js/adminlte.min.js') }}"></script>
<script src="{{ asset('media/js/jquery.toast.js') }}"></script>
<script src="{{ asset('media/js/bootstrap-datepicker.min.js') }}"></script>
<script>
  $(".input-group.date").datepicker({ format:"yyyy-mm-dd", autoclose:true, todayHighlight:true });
  $(".years").datepicker({ format:"yyyy", viewMode:"years", minViewMode:"years", autoclose:true });
</script>

@if(session('success'))
<script>
  $(function(){ $.toast({ heading:'Berhasil', text:'{!! session("success") !!}', position:'top-right', icon:'success', hideAfter:3500 }); });
</script>
@endif
@if(session('failed'))
<script>
  $(function(){ $.toast({ heading:'Gagal', text:'{!! session("failed") !!}', position:'top-right', icon:'error', hideAfter:3500 }); });
</script>
@endif

@stack('scripts')
</body>
</html>
