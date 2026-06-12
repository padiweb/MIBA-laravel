@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-lg-3 col-xs-6">
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3>{{ $totalSiswa }}</h3>
        <p>Total Siswa Aktif</p>
      </div>
      <div class="icon"><i class="fa fa-graduation-cap"></i></div>
      <a href="{{ route('student.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div class="col-lg-3 col-xs-6">
    <div class="small-box bg-green">
      <div class="inner">
        <h3>{{ $totalSiswaBayar }}</h3>
        <p>Siswa Sudah Bayar</p>
      </div>
      <div class="icon"><i class="fa fa-check-circle"></i></div>
      <a href="{{ route('payout.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div class="col-lg-3 col-xs-6">
    <div class="small-box bg-yellow">
      <div class="inner">
        <h3>Rp {{ number_format($totalDebit, 0, ',', '.') }}</h3>
        <p>Debit Bulan Ini</p>
      </div>
      <div class="icon"><i class="fa fa-arrow-down"></i></div>
      <a href="{{ route('debit.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div class="col-lg-3 col-xs-6">
    <div class="small-box bg-red">
      <div class="inner">
        <h3>Rp {{ number_format($totalKredit, 0, ',', '.') }}</h3>
        <p>Kredit Bulan Ini</p>
      </div>
      <div class="icon"><i class="fa fa-arrow-up"></i></div>
      <a href="{{ route('kredit.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-3 col-xs-6">
    <div class="small-box bg-purple">
      <div class="inner">
        <h3>Rp {{ number_format($pemasukanHariIni, 0, ',', '.') }}</h3>
        <p>Pemasukan Hari Ini</p>
      </div>
      <div class="icon"><i class="fa fa-money"></i></div>
      <a href="{{ route('payout.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div class="col-lg-3 col-xs-6">
    <div class="small-box bg-navy">
      <div class="inner">
        <h3>{{ $totalUser }}</h3>
        <p>Total Pengguna</p>
      </div>
      <div class="icon"><i class="fa fa-users"></i></div>
      <a href="{{ route('users.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
</div>

@if($period)
<div class="row">
  <div class="col-md-12">
    <div class="alert alert-info">
      <i class="fa fa-calendar"></i>
      Tahun Pelajaran Aktif: <strong>{{ $period->period_start }}/{{ $period->period_end }}</strong>
    </div>
  </div>
</div>
@endif

<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-newspaper-o"></i> Informasi Terbaru</h3>
      </div>
      <div class="box-body">
        @forelse($infos as $info)
          <div class="media">
            <div class="media-body">
              <h4 class="media-heading">{{ $info->information_title }}</h4>
              <p>{{ Str::limit($info->information_desc, 150) }}</p>
              <small class="text-muted">{{ \Carbon\Carbon::parse($info->information_input_date)->diffForHumans() }}</small>
            </div>
          </div>
          <hr>
        @empty
          <p class="text-muted">Belum ada informasi.</p>
        @endforelse
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-7">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-history"></i> Aktivitas Terbaru</h3>
        <a href="{{ route('logs.index') }}" class="btn btn-default btn-xs pull-right">Lihat Semua</a>
      </div>
      <div class="box-body table-responsive" style="padding:0">
        <table class="table table-striped">
          <thead><tr><th>Waktu</th><th>Pengguna</th><th>Aksi</th><th>Modul</th><th>Keterangan</th></tr></thead>
          <tbody>
            @forelse($recentLogs as $log)
            <tr>
              <td>{{ \Carbon\Carbon::parse($log->log_date)->format('d/m/Y H:i') }}</td>
              <td>{{ $log->user->user_full_name ?? '-' }}</td>
              <td>{{ $log->log_action }}</td>
              <td>{{ $log->log_module }}</td>
              <td>{{ $log->log_info }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center">Belum ada aktivitas</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-5">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-calendar"></i> Agenda / Hari Libur Terdekat</h3>
        <a href="{{ route('holiday.index') }}" class="btn btn-default btn-xs pull-right">Kelola</a>
      </div>
      <div class="box-body">
        <ul class="list-group">
          @forelse($holidays as $h)
            <li class="list-group-item">
              <span class="badge bg-red">{{ \Carbon\Carbon::parse($h->date)->locale('id')->isoFormat('D MMM Y') }}</span>
              {{ $h->info }}
            </li>
          @empty
            <li class="list-group-item text-muted">Belum ada agenda</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
