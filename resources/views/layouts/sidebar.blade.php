{{-- Dashboard --}}
<a href="{{ route('dashboard') }}" class="miba-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
  <i class="fa fa-tachometer"></i> Dashboard
</a>

{{-- Transaksi Siswa --}}
@if(($user_role_id ?? 0) != 3)
<a href="{{ route('payout.index') }}" class="miba-nav-item {{ request()->routeIs('payout.*') ? 'active' : '' }}">
  <i class="fa fa-exchange"></i> Transaksi Siswa
</a>
@endif

{{-- Laporan - non-SUPERUSER --}}
@if(($user_role_id ?? 0) != 1)
<div class="miba-nav-section">Laporan</div>
<button class="miba-nav-item" data-sub="sub-laporan-user" aria-expanded="false">
  <i class="fa fa-file-text-o"></i> Laporan
  <i class="fa fa-chevron-down chevron"></i>
</button>
<div class="miba-nav-sub" id="sub-laporan-user">
  <a href="{{ route('report.index') }}" class="miba-nav-item {{ request()->routeIs('report.index') ? 'active' : '' }}">
    <i class="fa fa-circle-o"></i> Total Keuangan
  </a>
  <a href="{{ route('report.bill') }}" class="miba-nav-item {{ request()->routeIs('report.bill') ? 'active' : '' }}">
    <i class="fa fa-circle-o"></i> Per-Kelas
  </a>
</div>
@endif

{{-- Transaksi Umum --}}
@if(($user_role_id ?? 0) != 3)
<div class="miba-nav-section">Keuangan</div>
<button class="miba-nav-item" data-sub="sub-trx" aria-expanded="false">
  <i class="fa fa-money"></i> Transaksi Umum
  <i class="fa fa-chevron-down chevron"></i>
</button>
<div class="miba-nav-sub" id="sub-trx">
  <a href="{{ route('kredit.index') }}" class="miba-nav-item {{ request()->routeIs('kredit.*') ? 'active' : '' }}">
    <i class="fa fa-circle-o"></i> Pengeluaran
  </a>
  <a href="{{ route('debit.index') }}" class="miba-nav-item {{ request()->routeIs('debit.*') ? 'active' : '' }}">
    <i class="fa fa-circle-o"></i> Pemasukan
  </a>
</div>
@endif

{{-- SUPERUSER only --}}
@if(($user_role_id ?? 0) == 1)

<div class="miba-nav-section">Pembayaran</div>
<button class="miba-nav-item" data-sub="sub-payment" aria-expanded="false">
  <i class="fa fa-cog"></i> Pengaturan Pembayaran
  <i class="fa fa-chevron-down chevron"></i>
</button>
<div class="miba-nav-sub" id="sub-payment">
  <a href="{{ route('pos.index') }}" class="miba-nav-item {{ request()->routeIs('pos.*') ? 'active' : '' }}">
    <i class="fa fa-circle-o"></i> Nama Pembayaran
  </a>
  <a href="{{ route('payment.index') }}" class="miba-nav-item {{ request()->routeIs('payment.*') ? 'active' : '' }}">
    <i class="fa fa-circle-o"></i> Jenis Pembayaran
  </a>
</div>

<div class="miba-nav-section">Akademik</div>
<button class="miba-nav-item" data-sub="sub-akademik" aria-expanded="false">
  <i class="fa fa-university"></i> Akademik
  <i class="fa fa-chevron-down chevron"></i>
</button>
<div class="miba-nav-sub" id="sub-akademik">
  <a href="{{ route('setting.index') }}" class="miba-nav-item {{ request()->routeIs('setting.*') ? 'active' : '' }}">
    <i class="fa fa-circle-o"></i> Profil Yayasan
  </a>
  <a href="{{ route('month.index') }}" class="miba-nav-item {{ request()->routeIs('month.*') ? 'active' : '' }}">
    <i class="fa fa-circle-o"></i> Bulan
  </a>
  <a href="{{ route('period.index') }}" class="miba-nav-item {{ request()->routeIs('period.*') ? 'active' : '' }}">
    <i class="fa fa-circle-o"></i> Tahun Pelajaran
  </a>
  <a href="{{ route('class.index') }}" class="miba-nav-item {{ request()->routeIs('class.*') ? 'active' : '' }}">
    <i class="fa fa-circle-o"></i> Kelas
  </a>
  @if(($app_level ?? '') == 'senior')
  <a href="{{ route('student.majors') }}" class="miba-nav-item {{ request()->routeIs('student.majors') ? 'active' : '' }}">
    <i class="fa fa-circle-o"></i> Unit Pendidikan
  </a>
  @endif
  <a href="{{ route('student.index') }}" class="miba-nav-item {{ request()->routeIs('student.index') || request()->routeIs('student.show') || request()->routeIs('student.edit') || request()->routeIs('student.create') ? 'active' : '' }}">
    <i class="fa fa-circle-o"></i> Peserta Didik
  </a>
  @if(($app_level ?? '') == 'senior')
  <a href="{{ route('student.upgrade') }}" class="miba-nav-item {{ request()->routeIs('student.upgrade') ? 'active' : '' }}">
    <i class="fa fa-circle-o"></i> Kenaikan Kelas
  </a>
  <a href="{{ route('student.pass') }}" class="miba-nav-item {{ request()->routeIs('student.pass') ? 'active' : '' }}">
    <i class="fa fa-circle-o"></i> Kelulusan
  </a>
  @endif
</div>

<div class="miba-nav-section">Laporan</div>
<button class="miba-nav-item" data-sub="sub-laporan" aria-expanded="false">
  <i class="fa fa-bar-chart"></i> Laporan
  <i class="fa fa-chevron-down chevron"></i>
</button>
<div class="miba-nav-sub" id="sub-laporan">
  <a href="{{ route('report.index') }}" class="miba-nav-item {{ request()->routeIs('report.index') ? 'active' : '' }}">
    <i class="fa fa-circle-o"></i> Total Keuangan
  </a>
  <a href="{{ route('report.bill') }}" class="miba-nav-item {{ request()->routeIs('report.bill') ? 'active' : '' }}">
    <i class="fa fa-circle-o"></i> Per-Kelas
  </a>
</div>

<div class="miba-nav-section">Sistem</div>
<a href="{{ route('users.index') }}" class="miba-nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
  <i class="fa fa-users"></i> Pengguna
</a>
<a href="{{ route('maintenance.index') }}" class="miba-nav-item {{ request()->routeIs('maintenance.*') ? 'active' : '' }}">
  <i class="fa fa-database"></i> Backup Database
</a>
@endif

<div class="miba-nav-section">Umum</div>
<a href="{{ route('information.index') }}" class="miba-nav-item {{ request()->routeIs('information.*') ? 'active' : '' }}">
  <i class="fa fa-newspaper-o"></i> Informasi
</a>
<a href="{{ route('holiday.index') }}" class="miba-nav-item {{ request()->routeIs('holiday.*') ? 'active' : '' }}">
  <i class="fa fa-calendar"></i> Hari Libur
</a>
<a href="{{ route('logs.index') }}" class="miba-nav-item {{ request()->routeIs('logs.*') ? 'active' : '' }}">
  <i class="fa fa-history"></i> Log Aktivitas
</a>

<div style="height:16px"></div>
