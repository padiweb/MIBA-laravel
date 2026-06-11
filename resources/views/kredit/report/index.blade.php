@extends('layouts.app')
@section('content')
<div class="box box-primary">
  <div class="box-header with-border"><h3 class="box-title">Laporan Pembayaran</h3></div>
  <div class="box-body">
    <form method="GET" action="{{ route('report.cetak') }}" target="_blank">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label>Tahun Pelajaran</label>
            <select name="period_id" class="form-control">
              <option value="">Semua Tahun</option>
              @foreach($periods as $p)
                <option value="{{ $p->period_id }}" {{ isset($period) && $period->period_id==$p->period_id?'selected':'' }}>
                  {{ $p->period_start }}/{{ $p->period_end }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Dari Tanggal</label>
            <div class="input-group date">
              <input type="text" name="date_start" class="form-control">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Sampai Tanggal</label>
            <div class="input-group date">
              <input type="text" name="date_end" class="form-control">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-danger">
        <i class="fa fa-file-pdf-o"></i> Cetak PDF
      </button>
    </form>
  </div>
</div>
@endsection
