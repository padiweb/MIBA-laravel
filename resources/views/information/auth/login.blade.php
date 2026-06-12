<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>{{ $setting['school'] ?? 'MIBA' }} | Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{ asset('media/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/skin-purple-light.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <b>{{ $setting['school'] ?? 'MIBA' }}</b>
  </div>
  <div class="login-box-body">
    <p class="login-box-msg">Masuk ke sistem</p>

    @if(session('failed'))
      <div class="alert alert-danger">{{ session('failed') }}</div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
      @csrf
      @if(request('redirect'))
        <input type="hidden" name="redirect" value="{{ request('redirect') }}">
      @endif
      <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
        <input type="email" name="email" class="form-control"
               placeholder="Email" value="{{ old('email') }}" required>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        @error('email')<span class="help-block">{{ $message }}</span>@enderror
      </div>
      <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        @error('password')<span class="help-block">{{ $message }}</span>@enderror
      </div>
      <div class="row">
        <div class="col-xs-12">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Masuk</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script src="{{ asset('media/js/jquery.min.js') }}"></script>
<script src="{{ asset('media/js/bootstrap.min.js') }}"></script>
</body>
</html>
