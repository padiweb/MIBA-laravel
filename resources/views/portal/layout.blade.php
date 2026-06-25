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
<div class="p-app-frame">

  {{-- ════════════════════════════════════════
       TOP BAR (mobile: header biru)
       Desktop: juga sebagai header atas full-width
  ════════════════════════════════════════ --}}
  <header class="p-topbar">
    <div class="p-topbar-title">{{ $title ?? 'Dashboard' }}</div>
    <div style="flex:1"></div>
    {{-- Desktop: info sekolah --}}
    <span class="p-topbar-school">{{ $app_name ?? '' }}</span>
    <a href="{{ route('portal.profile') }}" class="p-topbar-avatar" title="Profil">
      @if($student_img ?? null)
        <img src="{{ asset('uploads/student/'.($student_img ?? '')) }}" alt="Foto">
      @else
        {{ strtoupper(substr($student_name ?? 'S', 0, 1)) }}
      @endif
    </a>
  </header>

  {{-- ════════════════════════════════════════
       DESKTOP BODY = sidebar + main
       Mobile: tidak ada sidebar, hanya content
  ════════════════════════════════════════ --}}
  <div class="p-desktop-body">

    {{-- Sidebar (desktop only — hidden di mobile via CSS) --}}
    <aside class="p-sidebar">
      {{-- User info --}}
      <div style="padding:16px 20px 20px;border-bottom:1px solid rgba(255,255,255,.1);margin-bottom:8px">
        <div style="display:flex;align-items:center;gap:10px">
          @if($student_img ?? null)
            <img src="{{ asset('uploads/student/'.($student_img ?? '')) }}"
                 style="width:40px;height:40px;border-radius:10px;object-fit:cover;border:2px solid rgba(255,255,255,.2)">
          @else
            <div style="width:40px;height:40px;border-radius:10px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:16px;color:#fff;border:2px solid rgba(255,255,255,.2)">
              {{ strtoupper(substr($student_name ?? 'S', 0, 1)) }}
            </div>
          @endif
          <div>
            <div style="font-size:13px;font-weight:700;color:#fff;line-height:1.2">{{ ucwords(strtolower($student_name ?? '')) }}</div>
            <div style="font-size:11px;color:rgba(255,255,255,.55)">{{ $student_nis ?? '' }}</div>
          </div>
        </div>
      </div>

      {{-- Nav items --}}
      <div class="p-sidebar-section">Menu</div>

      <a href="{{ route('portal.dashboard') }}"
         class="p-sidebar-item {{ request()->routeIs('portal.dashboard') ? 'active' : '' }}">
        <i class="fa fa-home"></i> Beranda
      </a>
      <a href="{{ route('portal.payout') }}"
         class="p-sidebar-item {{ request()->routeIs('portal.payout') ? 'active' : '' }}">
        <i class="fa fa-money"></i> Cek Tagihan
      </a>

      <div class="p-sidebar-section">Akun</div>

      <a href="{{ route('portal.profile') }}"
         class="p-sidebar-item {{ request()->routeIs('portal.profile') ? 'active' : '' }}">
        <i class="fa fa-user-circle"></i> Profil Saya
      </a>
      <a href="{{ route('portal.profile.edit') }}"
         class="p-sidebar-item {{ request()->routeIs('portal.profile.edit') ? 'active' : '' }}">
        <i class="fa fa-edit"></i> Edit Profil
      </a>
      <a href="{{ route('portal.profile.cpw') }}"
         class="p-sidebar-item {{ request()->routeIs('portal.profile.cpw') ? 'active' : '' }}">
        <i class="fa fa-lock"></i> Ganti Password
      </a>

      <div style="flex:1"></div>

      <a href="{{ route('portal.logout') }}"
         class="p-sidebar-item danger"
         style="margin-top:auto"
         onclick="return confirm('Yakin ingin keluar?')">
        <i class="fa fa-sign-out"></i> Keluar
      </a>
    </aside>

    {{-- ── Main area ── --}}
    <div class="p-main">

      {{-- Flash messages --}}
      @if(session('success'))
        <div class="p-alert p-alert-success" id="p-flash"
             style="margin:var(--p-space-4) var(--p-space-4) 0">
          <i class="fa fa-check-circle"></i>
          <span>{{ session('success') }}</span>
        </div>
      @endif
      @if(session('failed'))
        <div class="p-alert p-alert-danger" id="p-flash"
             style="margin:var(--p-space-4) var(--p-space-4) 0">
          <i class="fa fa-exclamation-circle"></i>
          <span>{{ session('failed') }}</span>
        </div>
      @endif
      @if($errors->any())
        <div class="p-alert p-alert-danger"
             style="margin:var(--p-space-4) var(--p-space-4) 0">
          <i class="fa fa-exclamation-circle"></i>
          <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
        </div>
      @endif

      {{-- Page content --}}
      <main class="p-content">
        @yield('content')
      </main>

    </div>{{-- /p-main --}}
  </div>{{-- /p-desktop-body --}}

  {{-- ── Mobile Bottom Navigation (hidden di desktop) ── --}}
  <nav class="p-bottomnav">
    <a href="{{ route('portal.dashboard') }}"
       class="p-bnav-item {{ request()->routeIs('portal.dashboard') ? 'active' : '' }}">
      <i class="fa fa-home"></i><span>Beranda</span>
    </a>
    <a href="{{ route('portal.payout') }}"
       class="p-bnav-item {{ request()->routeIs('portal.payout') ? 'active' : '' }}">
      <i class="fa fa-money"></i><span>Tagihan</span>
    </a>
    <a href="{{ route('portal.profile') }}"
       class="p-bnav-item {{ request()->routeIs('portal.profile') ? 'active' : '' }}">
      <i class="fa fa-user-circle"></i><span>Profil</span>
    </a>
    <a href="{{ route('portal.logout') }}"
       class="p-bnav-item"
       onclick="return confirm('Yakin ingin keluar?')">
      <i class="fa fa-sign-out"></i><span>Keluar</span>
    </a>
  </nav>

</div>{{-- /p-app-frame --}}
</div>{{-- /p-shell --}}

<script src="{{ asset('media/js/bootstrap-datepicker.min.js') }}"></script>
<script>
$(function(){
  setTimeout(function(){ $('#p-flash').slideUp(300); }, 3500);
  $('.date-pick').datepicker({ format:'yyyy-mm-dd', autoclose:true, todayHighlight:true });
});
</script>
@stack('scripts')
</body>
</html>
