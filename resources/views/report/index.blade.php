@extends('layouts.app')
@section('content')
<div class="box box-primary">
  <div class="box-header with-border"><h3 class="box-title">Laporan Total Keuangan</h3></div>
  <div class="box-body">
    <form method="GET" class="form-inline" style="margin-bottom:15px">
      <label>Dari Tanggal</label>
      <div class="input-group date" style="margin:0 8px">
        <input type="text" name="ds" class="form-control" value="{{ request('ds') }}">
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
      </div>
      <label>Sampai Tanggal</label>
      <div class="input-group date" style="margin:0 8px">
        <input type="text" name="de" class="form-control" value="{{ request('de') }}">
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
      </div>
      <button class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
      <a href="{{ route('report.index') }}" class="btn btn-default">Reset</a>
    </form>

    <div class="row">
      <div class="col-md-3">
        <div class="info-box bg-green">
          <span class="info-box-icon"><i class="fa fa-money"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Pembayaran Bulanan</span>
            <span class="info-box-number">Rp {{ number_format($totalBulan,0,',','.') }}</span>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="info-box bg-aqua">
          <span class="info-box-icon"><i class="fa fa-money"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Pembayaran Bebas</span>
            <span class="info-box-number">Rp {{ number_format($totalBebas,0,',','.') }}</span>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="info-box bg-yellow">
          <span class="info-box-icon"><i class="fa fa-arrow-down"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Pemasukan Lain (Debit)</span>
            <span class="info-box-number">Rp {{ number_format($totalDebit,0,',','.') }}</span>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="info-box bg-red">
          <span class="info-box-icon"><i class="fa fa-arrow-up"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Pengeluaran (Kredit)</span>
            <span class="info-box-number">Rp {{ number_format($totalKredit,0,',','.') }}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="small-box bg-green">
          <div class="inner">
            <h3>Rp {{ number_format($pemasukan,0,',','.') }}</h3>
            <p>Total Pemasukan</p>
          </div>
          <div class="icon"><i class="fa fa-plus"></i></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="small-box bg-red">
          <div class="inner">
            <h3>Rp {{ number_format($pengeluaran,0,',','.') }}</h3>
            <p>Total Pengeluaran</p>
          </div>
          <div class="icon"><i class="fa fa-minus"></i></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="small-box bg-blue">
          <div class="inner">
            <h3>Rp {{ number_format($saldo,0,',','.') }}</h3>
            <p>Saldo</p>
          </div>
          <div class="icon"><i class="fa fa-balance-scale"></i></div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="box box-default">
  <div class="box-header with-border"><h3 class="box-title">Cetak Detail Pembayaran (PDF)</h3></div>
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
