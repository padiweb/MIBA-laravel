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
@endsection
