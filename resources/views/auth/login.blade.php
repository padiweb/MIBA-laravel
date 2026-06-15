<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>{{ $setting['school'] ?? 'MIBA' }} · Masuk</title>
  <link rel="stylesheet" href="{{ asset('media/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('media/css/miba-ui.css') }}">
  <style>
    body { background: #f0faf9; display:flex; min-height:100vh; align-items:center; justify-content:center; padding:16px; }
    .login-shell {
      display: grid;
      grid-template-columns: 1fr 1fr;
      max-width: 900px;
      width: 100%;
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(15,118,110,.15);
      overflow: hidden;
    }
    .login-left {
      background: linear-gradient(145deg, #0f766e 0%, #065f46 100%);
      padding: 48px 40px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      position: relative;
      overflow: hidden;
    }
    .login-left::before {
      content: '';
      position: absolute;
      width: 300px; height: 300px;
      border-radius: 50%;
      background: rgba(255,255,255,.06);
      top: -80px; right: -80px;
    }
    .login-left::after {
      content: '';
      position: absolute;
      width: 200px; height: 200px;
      border-radius: 50%;
      background: rgba(255,255,255,.06);
      bottom: -40px; left: -40px;
    }
    .login-logo-wrap { display:flex; align-items:center; gap:12px; position:relative; z-index:1; }
    .login-logo-box {
      width: 48px; height: 48px;
      background: rgba(255,255,255,.2);
      border-radius: 14px;
      display: flex; align-items:center; justify-content:center;
      font-size: 22px; color:#fff;
      overflow: hidden;
    }
    .login-logo-box img { width:100%; height:100%; object-fit:cover; }
    .login-logo-text { color:#fff; }
    .login-logo-text .name { font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:16px; line-height:1.2; }
    .login-logo-text .sub  { font-size:11px; opacity:.7; }
    .login-headline { position:relative; z-index:1; }
    .login-headline h2 { font-size:28px; font-weight:800; color:#fff; line-height:1.3; margin-bottom:12px; }
    .login-headline p  { color:rgba(255,255,255,.7); font-size:13px; line-height:1.6; }
    .login-stats { display:flex; gap:24px; position:relative; z-index:1; }
    .login-stat-item { }
    .login-stat-item .num { font-size:22px; font-weight:700; color:#fff; }
    .login-stat-item .lbl { font-size:11px; color:rgba(255,255,255,.6); }

    .login-right { padding: 48px 40px; display:flex; flex-direction:column; justify-content:center; }
    .login-right h3 { font-size:22px; font-weight:700; color:var(--text-primary); margin-bottom:6px; }
    .login-right p  { font-size:13px; color:var(--text-muted); margin-bottom:28px; }
    .login-form { display:flex; flex-direction:column; gap:16px; }
    .login-field label { display:block; font-size:12px; font-weight:600; color:var(--text-primary); margin-bottom:6px; }
    .login-field-input { position:relative; }
    .login-field-input i { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:14px; }
    .login-field-input input {
      width:100%; padding:10px 12px 10px 38px;
      border:1.5px solid var(--border-dark); border-radius:10px;
      font-size:14px; color:var(--text-primary);
      background:#fff; outline:none;
      transition:border .2s, box-shadow .2s;
    }
    .login-field-input input:focus { border-color:var(--primary-light); box-shadow:0 0 0 3px rgba(20,184,166,.15); }
    .btn-login {
      background: var(--primary);
      color: #fff;
      border: none;
      border-radius: 10px;
      padding: 12px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: background .2s, box-shadow .2s;
      margin-top: 4px;
    }
    .btn-login:hover { background: var(--primary-dark); box-shadow: 0 6px 20px rgba(15,118,110,.35); }
    .login-portal-link {
      margin-top:20px; text-align:center; font-size:13px; color:var(--text-muted);
    }
    .login-portal-link a { color:var(--primary); font-weight:600; text-decoration:none; }
    .login-portal-link a:hover { text-decoration:underline; }
    .login-error { background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; border-radius:8px; padding:10px 14px; font-size:13px; }

    @media(max-width:640px){
      .login-shell { grid-template-columns:1fr; }
      .login-left  { display:none; }
      .login-right { padding:32px 24px; }
      body { padding:0; align-items:flex-start; }
      .login-shell { border-radius:0; min-height:100vh; }
    }
  </style>
</head>
<body>
<div class="login-shell">
  <div class="login-left">
    <div class="login-logo-wrap">
      <div class="login-logo-box">
        @if(!empty($setting['logo']))
          <img src="{{ asset('uploads/school/'.$setting['logo']) }}">
        @else
          <i class="fa fa-graduation-cap"></i>
        @endif
      </div>
      <div class="login-logo-text">
        <div class="name">{{ $setting['school'] ?? 'MIBA' }}</div>
        <div class="sub">Sistem Informasi</div>
      </div>
    </div>
    <div class="login-headline">
      <h2>Kelola keuangan sekolah dengan mudah & efisien</h2>
      <p>Sistem manajemen pembayaran siswa, laporan keuangan, dan administrasi sekolah dalam satu platform.</p>
    </div>
    <div class="login-stats">
      <div class="login-stat-item">
        <div class="num">&#10003;</div>
        <div class="lbl">Laporan Real-time</div>
      </div>
      <div class="login-stat-item">
        <div class="num">&#10003;</div>
        <div class="lbl">Multi-role</div>
      </div>
      <div class="login-stat-item">
        <div class="num">&#10003;</div>
        <div class="lbl">Portal Siswa</div>
      </div>
    </div>
  </div>
  <div class="login-right">
    <h3>Selamat datang kembali</h3>
    <p>Masuk dengan akun administrator Anda</p>

    @if(session('failed'))
      <div class="login-error" style="margin-bottom:16px">
        <i class="fa fa-exclamation-circle"></i> {{ session('failed') }}
      </div>
    @endif
    @if($errors->any())
      <div class="login-error" style="margin-bottom:16px">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
      </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST" class="login-form">
      @csrf
      @if(request('redirect'))
        <input type="hidden" name="redirect" value="{{ request('redirect') }}">
      @endif
      <div class="login-field">
        <label>Email</label>
        <div class="login-field-input">
          <i class="fa fa-envelope"></i>
          <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
        </div>
      </div>
      <div class="login-field">
        <label>Password</label>
        <div class="login-field-input">
          <i class="fa fa-lock"></i>
          <input type="password" name="password" placeholder="••••••••" required>
        </div>
      </div>
      <button type="submit" class="btn-login">Masuk</button>
    </form>

    <div class="login-portal-link">
      Siswa/Santri? <a href="{{ route('portal.login') }}">Login ke Portal Santri</a>
    </div>
  </div>
</div>
</body>
</html>
