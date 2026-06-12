<aside class="main-sidebar">
  <section class="sidebar">
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MENU UTAMA</li>

      <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}">
          <i class="fa fa-dashboard"></i><span>Dashboard</span>
        </a>
      </li>

      <li class="treeview {{ request()->routeIs('student.*') ? 'active' : '' }}">
        <a href="#">
          <i class="fa fa-graduation-cap"></i><span>Data Siswa</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->routeIs('student.index') ? 'active' : '' }}">
            <a href="{{ route('student.index') }}"><i class="fa fa-circle-o"></i> Semua Siswa</a>
          </li>
          <li class="{{ request()->routeIs('student.classes') ? 'active' : '' }}">
            <a href="{{ route('student.classes') }}"><i class="fa fa-circle-o"></i> Kelas</a>
          </li>
          <li class="{{ request()->routeIs('student.majors') ? 'active' : '' }}">
            <a href="{{ route('student.majors') }}"><i class="fa fa-circle-o"></i> Jurusan</a>
          </li>
        </ul>
      </li>

      <li class="header">KEUANGAN</li>

      <li class="treeview {{ request()->routeIs('payout.*') ? 'active' : '' }}">
        <a href="#">
          <i class="fa fa-money"></i><span>Pembayaran Siswa</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ route('payout.index') }}"><i class="fa fa-circle-o"></i> Daftar Pembayaran</a></li>
          <li><a href="{{ route('payout.create') }}"><i class="fa fa-circle-o"></i> Bayar</a></li>
        </ul>
      </li>

      <li class="{{ request()->routeIs('payment.*') ? 'active' : '' }}">
        <a href="{{ route('payment.index') }}">
          <i class="fa fa-list"></i><span>Jenis Pembayaran</span>
        </a>
      </li>

      <li class="{{ request()->routeIs('debit.*') ? 'active' : '' }}">
        <a href="{{ route('debit.index') }}">
          <i class="fa fa-arrow-down"></i><span>Debit</span>
        </a>
      </li>

      <li class="{{ request()->routeIs('kredit.*') ? 'active' : '' }}">
        <a href="{{ route('kredit.index') }}">
          <i class="fa fa-arrow-up"></i><span>Kredit</span>
        </a>
      </li>

      <li class="{{ request()->routeIs('report.*') ? 'active' : '' }}">
        <a href="{{ route('report.index') }}">
          <i class="fa fa-file-pdf-o"></i><span>Laporan</span>
        </a>
      </li>

      <li class="header">MASTER DATA</li>

      <li class="{{ request()->routeIs('period.*') ? 'active' : '' }}">
        <a href="{{ route('period.index') }}">
          <i class="fa fa-calendar"></i><span>Tahun Pelajaran</span>
        </a>
      </li>

      <li class="{{ request()->routeIs('pos.*') ? 'active' : '' }}">
        <a href="{{ route('pos.index') }}">
          <i class="fa fa-tags"></i><span>Jenis Biaya</span>
        </a>
      </li>

      <li class="header">LAINNYA</li>

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

      <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
        <a href="{{ route('users.index') }}">
          <i class="fa fa-users"></i><span>Pengguna</span>
        </a>
      </li>

      <li class="{{ request()->routeIs('setting.*') ? 'active' : '' }}">
        <a href="{{ route('setting.index') }}">
          <i class="fa fa-cog"></i><span>Pengaturan</span>
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
