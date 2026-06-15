<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>{{ $setting['school'] ?? 'MIBA' }} · Masuk</title>
  <link rel="stylesheet" href="{{ asset('media/css/font-awesome.min.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --blue: #2563eb;
      --blue-dark: #1d4ed8;
      --teal: #0f766e;
      --teal-dark: #0d5c56;
      --white: #ffffff;
      --gray-50: #f8fafc;
      --gray-100: #f1f5f9;
      --gray-300: #cbd5e1;
      --gray-400: #94a3b8;
      --gray-500: #64748b;
      --gray-700: #334155;
      --gray-900: #0f172a;
      --radius: 16px;
      --radius-sm: 12px;
    }
    body {
      font-family: 'Inter', sans-serif;
      background: #f0f4ff;
      min-height: 100vh;
      min-height: 100dvh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 16px;
    }

    /* ── Desktop: Split layout ── */
    .shell {
      display: grid;
      grid-template-columns: 1fr 1fr;
      max-width: 900px;
      width: 100%;
      background: var(--white);
      border-radius: 24px;
      box-shadow: 0 24px 64px rgba(0,0,0,.12);
      overflow: hidden;
      min-height: 540px;
    }

    /* ── Left panel ── */
    .left {
      background: linear-gradient(145deg, #1e40af 0%, #1e3a8a 100%);
      padding: 48px 40px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      position: relative;
      overflow: hidden;
    }
    .left::before {
      content: '';
      position: absolute;
      width: 320px; height: 320px;
      border-radius: 50%;
      background: rgba(255,255,255,.06);
      top: -100px; right: -100px;
    }
    .left::after {
      content: '';
      position: absolute;
      width: 200px; height: 200px;
      border-radius: 50%;
      background: rgba(255,255,255,.05);
      bottom: -60px; left: -60px;
    }
    .left-logo { display:flex; align-items:center; gap:12px; position:relative; z-index:1; }
    .left-logo-box {
      width:48px; height:48px; border-radius:14px;
      background:rgba(255,255,255,.2);
      display:flex; align-items:center; justify-content:center;
      font-size:22px; color:#fff; overflow:hidden; flex-shrink:0;
    }
    .left-logo-box img { width:100%; height:100%; object-fit:cover; }
    .left-logo-text .name { font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:15px; color:#fff; line-height:1.2; }
    .left-logo-text .sub  { font-size:11px; color:rgba(255,255,255,.6); }
    .left-body { position:relative; z-index:1; }
    .left-body h2 { font-size:28px; font-weight:800; color:#fff; line-height:1.3; margin-bottom:12px; }
    .left-body p  { color:rgba(255,255,255,.7); font-size:13px; line-height:1.7; }
    .left-pills   { display:flex; gap:8px; position:relative; z-index:1; flex-wrap:wrap; }
    .pill {
      display:inline-flex; align-items:center; gap:6px;
      background:rgba(255,255,255,.15); border-radius:99px;
      padding:5px 12px; font-size:12px; color:rgba(255,255,255,.9);
    }
    .pill i { font-size:11px; }

    /* ── Right panel ── */
    .right {
      padding: 48px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .tab-switcher {
      display: flex;
      background: var(--gray-100);
      border-radius: 12px;
      padding: 4px;
      margin-bottom: 28px;
    }
    .tab-btn {
      flex: 1;
      padding: 9px;
      border: none;
      border-radius: 9px;
      background: transparent;
      font-family: 'Inter', sans-serif;
      font-size: 13px;
      font-weight: 600;
      color: var(--gray-500);
      cursor: pointer;
      transition: all .2s;
    }
    .tab-btn.active {
      background: var(--white);
      color: var(--gray-900);
      box-shadow: 0 2px 8px rgba(0,0,0,.1);
    }
    .tab-pane { display: none; }
    .tab-pane.active { display: block; }
    .right h3 { font-size:22px; font-weight:700; color:var(--gray-900); margin-bottom:4px; }
    .right p  { font-size:13px; color:var(--gray-500); margin-bottom:24px; }
    .field { margin-bottom:16px; }
    .field label { display:block; font-size:12px; font-weight:600; color:var(--gray-700); margin-bottom:6px; }
    .field-wrap { position:relative; }
    .field-wrap .fi { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:var(--gray-400); font-size:14px; pointer-events:none; }
    .field input {
      width:100%; padding:11px 12px 11px 38px;
      border:1.5px solid var(--gray-300); border-radius:var(--radius-sm);
      font-family:'Inter',sans-serif; font-size:14px; color:var(--gray-900);
      background:var(--white); outline:none;
      transition:border .2s, box-shadow .2s;
    }
    .field input:focus { border-color:#60a5fa; box-shadow:0 0 0 3px rgba(96,165,250,.15); }
    .btn-login {
      width:100%; padding:13px; border:none; border-radius:var(--radius-sm);
      font-family:'Inter',sans-serif; font-size:14px; font-weight:700;
      cursor:pointer; transition:all .2s; margin-top:4px;
    }
    .btn-siswa { background: linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; }
    .btn-siswa:hover { box-shadow:0 6px 20px rgba(37,99,235,.35); transform:translateY(-1px); }
    .btn-admin { background: linear-gradient(135deg,#0f766e,#0d5c56); color:#fff; }
    .btn-admin:hover { box-shadow:0 6px 20px rgba(15,118,110,.35); transform:translateY(-1px); }
    .err-box {
      background:#fee2e2; border:1px solid #fca5a5; color:#991b1b;
      border-radius:10px; padding:10px 14px; font-size:12px;
      margin-bottom:16px; display:flex; align-items:flex-start; gap:8px;
    }

    /* ── Mobile only ── */
    @media (max-width: 640px) {
      body { padding: 0; align-items: stretch; background: var(--white); }
      .shell { grid-template-columns: 1fr; border-radius:0; min-height:100vh; min-height:100dvh; box-shadow:none; }
      .left { display:none; }
      .right { padding: 0; justify-content: flex-start; }

      /* Mobile hero header */
      .mobile-hero {
        background: linear-gradient(145deg, #1e40af 0%, #1e3a8a 100%);
        padding: 52px 28px 60px;
        text-align: center;
        position: relative;
        overflow: hidden;
      }
      .mobile-hero::before {
        content:'';
        position:absolute; width:300px; height:300px; border-radius:50%;
        background:rgba(255,255,255,.07);
        top:-120px; left:-80px;
      }
      .mobile-hero::after {
        content:'';
        position:absolute; width:200px; height:200px; border-radius:50%;
        background:rgba(255,255,255,.05);
        bottom:-60px; right:-50px;
      }
      .mobile-hero-logo {
        width:68px; height:68px; border-radius:20px;
        background:rgba(255,255,255,.2);
        display:flex; align-items:center; justify-content:center;
        margin:0 auto 16px; font-size:28px; color:#fff;
        overflow:hidden; position:relative; z-index:1;
      }
      .mobile-hero-logo img { width:100%; height:100%; object-fit:cover; }
      .mobile-hero h1 { font-size:24px; font-weight:800; color:#fff; margin-bottom:6px; position:relative; z-index:1; font-family:'Plus Jakarta Sans',sans-serif; }
      .mobile-hero p  { font-size:13px; color:rgba(255,255,255,.7); position:relative; z-index:1; }

      /* Mobile card */
      .mobile-card {
        background: var(--white);
        border-radius: 28px 28px 0 0;
        margin-top: -28px;
        padding: 28px 24px 40px;
        position: relative;
        z-index: 2;
        flex: 1;
      }
      .tab-switcher { margin-bottom: 24px; }
      .right h3, .right p { display: none; }
    }
    @media (min-width: 641px) {
      .mobile-hero { display: none; }
      .mobile-card { background: none; border-radius: 0; margin-top: 0; padding: 0; }
    }
  </style>
</head>
<body>
<div class="shell">
  {{-- Desktop Left --}}
  <div class="left">
    <div class="left-logo">
      <div class="left-logo-box">
        @if(!empty($setting['logo']))
          <img src="{{ asset('uploads/school/'.$setting['logo']) }}">
        @else
          <i class="fa fa-graduation-cap"></i>
        @endif
      </div>
      <div class="left-logo-text">
        <div class="name">{{ $setting['school'] ?? 'MIBA' }}</div>
        <div class="sub">Sistem Informasi</div>
      </div>
    </div>
    <div class="left-body">
      <h2>Kelola keuangan sekolah lebih mudah</h2>
      <p>Platform administrasi pembayaran siswa, laporan keuangan, dan data akademik dalam satu sistem terintegrasi.</p>
    </div>
    <div class="left-pills">
      <span class="pill"><i class="fa fa-check"></i> Laporan Real-time</span>
      <span class="pill"><i class="fa fa-check"></i> Multi-role</span>
      <span class="pill"><i class="fa fa-check"></i> Portal Siswa</span>
    </div>
  </div>

  {{-- Right Panel --}}
  <div class="right">
    {{-- Mobile hero (hanya tampil di mobile) --}}
    <div class="mobile-hero">
      <div class="mobile-hero-logo">
        @if(!empty($setting['logo']))
          <img src="{{ asset('uploads/school/'.$setting['logo']) }}">
        @else
          <i class="fa fa-graduation-cap"></i>
        @endif
      </div>
      <h1>{{ $setting['school'] ?? 'MIBA' }}</h1>
      <p>Sistem Informasi Sekolah</p>
    </div>

    <div class="mobile-card">
      {{-- Tab switcher --}}
      <div class="tab-switcher">
        <button class="tab-btn active" onclick="switchTab('siswa',this)">
          <i class="fa fa-graduation-cap"></i> Santri
        </button>
        <button class="tab-btn" onclick="switchTab('admin',this)">
          <i class="fa fa-building"></i> Admin
        </button>
      </div>

      {{-- Error --}}
      @if(session('failed'))
      <div class="err-box"><i class="fa fa-exclamation-circle"></i><span>{{ session('failed') }}</span></div>
      @endif
      @if($errors->any())
      <div class="err-box"><i class="fa fa-exclamation-circle"></i><div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div></div>
      @endif

      {{-- Tab Siswa --}}
      <div class="tab-pane active" id="pane-siswa">
        <h3>Masuk sebagai Santri</h3>
        <p>Gunakan NIS dan password Anda</p>
        <form method="POST" action="{{ route('portal.doLogin') }}">
          @csrf
          <div class="field">
            <label>Nomor Induk Siswa (NIS)</label>
            <div class="field-wrap">
              <i class="fa fa-id-card fi"></i>
              <input type="text" name="nis" value="{{ old('nis') }}" placeholder="Masukkan NIS Anda" required autofocus>
            </div>
          </div>
          <div class="field">
            <label>Password</label>
            <div class="field-wrap">
              <i class="fa fa-lock fi"></i>
              <input type="password" name="password" placeholder="••••••••" required>
            </div>
          </div>
          <button type="submit" class="btn-login btn-siswa">Masuk sebagai Santri</button>
        </form>
      </div>

      {{-- Tab Admin --}}
      <div class="tab-pane" id="pane-admin">
        <h3>Masuk sebagai Admin</h3>
        <p>Gunakan email dan password admin</p>
        <form method="POST" action="{{ route('login.post') }}">
          @csrf
          @if(request('redirect'))
            <input type="hidden" name="redirect" value="{{ request('redirect') }}">
          @endif
          <div class="field">
            <label>Email</label>
            <div class="field-wrap">
              <i class="fa fa-envelope fi"></i>
              <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
            </div>
          </div>
          <div class="field">
            <label>Password</label>
            <div class="field-wrap">
              <i class="fa fa-lock fi"></i>
              <input type="password" name="password" placeholder="••••••••" required>
            </div>
          </div>
          <button type="submit" class="btn-login btn-admin">Masuk sebagai Admin</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function switchTab(name, btn) {
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('pane-' + name).classList.add('active');
}

// Auto-switch ke tab admin jika ada error dari form admin
@if(session('_old_input.email') || $errors->has('email'))
  document.addEventListener('DOMContentLoaded', function() {
    switchTab('admin', document.querySelectorAll('.tab-btn')[1]);
  });
@endif
</script>
</body>
</html>
