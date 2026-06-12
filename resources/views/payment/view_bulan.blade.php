@extends('layouts.app')
@section('content')
<div class="box box-success">
  <div class="box-header with-border">
    <h3 class="box-title">Nominal - {{ $payment->pos->pos_name ?? '' }} - T.A {{ $payment->period->period_start ?? '' }}/{{ $payment->period->period_end ?? '' }}</h3>
  </div>
  <div class="box-body">
    @if(session('failed'))
      <div class="alert alert-danger">{{ session('failed') }}</div>
    @endif
    <form method="GET" class="form-horizontal">
      <div class="row">
        <div class="col-md-3">
          <label>Tahun</label>
          <input type="text" class="form-control" value="{{ $payment->period->period_start ?? '' }}/{{ $payment->period->period_end ?? '' }}" readonly>
        </div>
        <div class="col-md-3">
          <label>Kelas</label>
          <select class="form-control" name="pr">
            <option value="">-- Semua Kelas --</option>
            @foreach($classes as $c)
              <option value="{{ $c->class_id }}" {{ request('pr')==$c->class_id?'selected':'' }}>{{ $c->class_name }}</option>
            @endforeach
          </select>
        </div>
        @if(($app_level ?? '')=='senior')
        <div class="col-md-3">
          <label>Unit Pendidikan</label>
          <select class="form-control" name="k">
            <option value="">-- Semua Unit --</option>
            @foreach($majorsList as $m)
              <option value="{{ $m->majors_id }}" {{ request('k')==$m->majors_id?'selected':'' }}>{{ $m->majors_name }}</option>
            @endforeach
          </select>
        </div>
        @endif
        <div class="col-md-3">
          <label>&nbsp;</label><br>
          <button class="btn btn-success"><i class="fa fa-search"></i> Tampilkan Data</button>
        </div>
      </div>
    </form>
    <hr>
    <label>Setting Nominal</label><br>
    <a class="btn btn-primary btn-sm" href="{{ route('payment.addBulanForm', [$payment->payment_id, 'class']) }}">
      <i class="fa fa-plus"></i> Berdasarkan Kelas
    </a>
    @if(($app_level ?? '')=='senior')
    <a class="btn btn-warning btn-sm" href="{{ route('payment.addBulanForm', [$payment->payment_id, 'majors']) }}">
      <i class="fa fa-plus"></i> Berdasarkan Unit Pendidikan
    </a>
    @endif
    <a class="btn btn-info btn-sm" href="{{ route('payment.addBulanForm', [$payment->payment_id, 'student']) }}">
      <i class="fa fa-plus"></i> Berdasarkan Siswa
    </a>
    <a class="btn btn-default btn-sm" href="{{ route('payment.index') }}">
      <i class="fa fa-repeat"></i> Kembali
    </a>
  </div>
</div>

@if(request()->hasAny(['pr','k','q']))
<div class="box box-success">
  <div class="box-body table-responsive">
    <table class="table table-hover table-striped table-bordered">
      <tr>
        <th>No</th><th>NIS</th><th>Nama</th><th>Kelas</th>
        @if(($app_level ?? '')=='senior')<th>Unit Pendidikan</th>@endif
        <th>Aksi</th>
      </tr>
      @forelse($students as $i => $s)
      <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $s->student_nis }}</td>
        <td>{{ $s->student_full_name }}</td>
        <td>{{ $s->class->class_name ?? '-' }}</td>
        @if(($app_level ?? '')=='senior')<td>{{ $s->majors->majors_name ?? '-' }}</td>@endif
        <td>
          <a href="{{ route('payment.editBulan', [$payment->payment_id, $s->student_id]) }}" class="btn btn-xs btn-warning" title="Ubah Nominal">
            <i class="fa fa-edit"></i>
          </a>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" class="text-center">Belum ada data tarif untuk filter ini</td></tr>
      @endforelse
    </table>
  </div>
</div>
@endif
@endsection
