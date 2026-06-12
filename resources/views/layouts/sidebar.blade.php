<aside class="main-sidebar">
  <section class="sidebar">
    <ul class="sidebar-menu" data-widget="tree">

      {{-- Dashboard - semua role --}}
      <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}">
          <i class="fa fa-tachometer"></i><span>Dashboard</span>
        </a>
      </li>

      {{-- Transaksi Siswa - semua kecuali BENDAHARA(3) --}}
      @if(($user_role_id ?? 0) != 3)
      <li class="{{ request()->routeIs('payout.*') ? 'active' : '' }}">
        <a href="{{ route('payout.index') }}">
          <i class="fa fa-google-wallet"></i><span>Transaksi Siswa</span>
        </a>
      </li>
      @endif

      {{-- Laporan - untuk non-SUPERUSER(1) --}}
      @if(($user_role_id ?? 0) != 1)
      <li class="treeview {{ request()->routeIs('report.*') ? 'active' : '' }}">
        <a href="#">
          <i class="fa fa-file-text"></i><span>Laporan</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->routeIs('report.index') ? 'active' : '' }}">
            <a href="{{ route('report.index') }}"><i class="fa fa-circle-o"></i> Laporan Total Keuangan</a>
          </li>
          <li class="{{ request()->routeIs('report.bill') ? 'active' : '' }}">
            <a href="{{ route('report.bill') }}"><i class="fa fa-circle-o"></i> Laporan Per-Kelas</a>
          </li>
        </ul>
      </li>
      @endif

      {{-- Transaksi Umum - semua kecuali BENDAHARA(3) --}}
      @if(($user_role_id ?? 0) != 3)
      <li class="treeview {{ request()->routeIs('kredit.*') || request()->routeIs('debit.*') ? 'active' : '' }}">
        <a href="#">
          <i class="fa fa-shopping-cart"></i><span>Transaksi Umum</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->routeIs('kredit.*') ? 'active' : '' }}">
            <a href="{{ route('kredit.index') }}"><i class="fa fa-circle-o"></i> Pengeluaran</a>
          </li>
          <li class="{{ request()->routeIs('debit.*') ? 'active' : '' }}">
            <a href="{{ route('debit.index') }}"><i class="fa fa-circle-o"></i> Pemasukan</a>
          </li>
        </ul>
      </li>
      @endif

      {{-- Pengaturan Pembayaran - SUPERUSER(1) only --}}
      @if(($user_role_id ?? 0) == 1)
      <li class="treeview {{ request()->routeIs('pos.*') || request()->routeIs('payment.*') ? 'active' : '' }}">
        <a href="#">
          <i class="fa fa-cog"></i><span>Pengaturan Pembayaran</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->routeIs('pos.*') ? 'active' : '' }}">
            <a href="{{ route('pos.index') }}"><i class="fa fa-circle-o"></i> Nama Pembayaran</a>
          </li>
          <li class="{{ request()->routeIs('payment.*') ? 'active' : '' }}">
            <a href="{{ route('payment.index') }}"><i class="fa fa-circle-o"></i> Jenis Pembayaran</a>
          </li>
        </ul>
      </li>
      @endif

      {{-- Akademik - SUPERUSER(1) only --}}
      @if(($user_role_id ?? 0) == 1)
      <li class="treeview {{ request()->routeIs('setting.*') || request()->routeIs('month.*') || request()->routeIs('period.*') || request()->routeIs('class.*') || request()->routeIs('student.*') ? 'active' : '' }}">
        <a href="#">
          <i class="fa fa-users"></i><span>Akademik</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->routeIs('setting.*') ? 'active' : '' }}">
            <a href="{{ route('setting.index') }}"><i class="fa fa-circle-o"></i> Profil Yayasan</a>
          </li>
          <li class="{{ request()->routeIs('month.*') ? 'active' : '' }}">
            <a href="{{ route('month.index') }}"><i class="fa fa-circle-o"></i> Bulan</a>
          </li>
          <li class="{{ request()->routeIs('period.*') ? 'active' : '' }}">
            <a href="{{ route('period.index') }}"><i class="fa fa-circle-o"></i> Tahun Pelajaran</a>
          </li>
          <li class="{{ request()->routeIs('class.*') ? 'active' : '' }}">
            <a href="{{ route('class.index') }}"><i class="fa fa-circle-o"></i> Kelas</a>
          </li>

          @if(($app_level ?? '') == 'senior')
          <li class="{{ request()->routeIs('student.majors') ? 'active' : '' }}">
            <a href="{{ route('student.majors') }}"><i class="fa fa-circle-o"></i> Unit Pendidikan</a>
          </li>
          @endif

          <li class="{{ request()->routeIs('student.index') || request()->routeIs('student.show') || request()->routeIs('student.edit') ? 'active' : '' }}">
            <a href="{{ route('student.index') }}"><i class="fa fa-circle-o"></i> Peserta Didik</a>
          </li>

          @if(($app_level ?? '') == 'senior')
          <li class="{{ request()->routeIs('student.upgrade') ? 'active' : '' }}">
            <a href="{{ route('student.upgrade') }}"><i class="fa fa-circle-o"></i> Kenaikan Kelas</a>
          </li>
          <li class="{{ request()->routeIs('student.pass') ? 'active' : '' }}">
            <a href="{{ route('student.pass') }}"><i class="fa fa-circle-o"></i> Kelulusan</a>
          </li>
          @endif
        </ul>
      </li>

      {{-- Laporan (untuk SUPERUSER) --}}
      <li class="treeview {{ request()->routeIs('report.*') ? 'active' : '' }}">
        <a href="#">
          <i class="fa fa-file-text"></i><span>Laporan</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->routeIs('report.index') ? 'active' : '' }}">
            <a href="{{ route('report.index') }}"><i class="fa fa-circle-o"></i> Laporan Total Keuangan</a>
          </li>
          <li class="{{ request()->routeIs('report.bill') ? 'active' : '' }}">
            <a href="{{ route('report.bill') }}"><i class="fa fa-circle-o"></i> Laporan Per-Kelas</a>
          </li>
        </ul>
      </li>

      {{-- Pengguna Aplikasi --}}
      <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
        <a href="{{ route('users.index') }}">
          <i class="fa fa-user"></i><span>Pengguna Aplikasi</span>
        </a>
      </li>

      {{-- Backup Database --}}
      <li class="{{ request()->routeIs('maintenance.*') ? 'active' : '' }}">
        <a href="{{ route('maintenance.index') }}">
          <i class="fa fa-database"></i><span>Backup Database</span>
        </a>
      </li>
      @endif

      {{-- Modul tambahan: tetap ditampilkan untuk semua role --}}
      <li class="{{ request()->routeIs('information.*') ? 'active' : '' }}">
        <a href="{{ route('information.index') }}">
          <i class="fa fa-newspaper-o"></i><span>Informasi</span>
        </a>
      </li>

      <li class="{{ request()->routeIs('holiday.*') ? 'active' : '' }}">
        <a href="{{ route('holiday.index') }}">
          <i class="fa fa-sun-o"></i><span>Hari Libur</span>
        </a>
      </li>

      <li class="{{ request()->routeIs('logs.*') ? 'active' : '' }}">
        <a href="{{ route('logs.index') }}">
          <i class="fa fa-history"></i><span>Log Aktivitas</span>
        </a>
      </li>
    </ul>
  </section>
</aside>
