@extends('portal.layout')
@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="box box-success">
      <div class="box-body bg-success">
        <div class="col-md-3 col-sm-6 col-xs-12" style="margin-top:10px">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-dollar"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Sisa Tagihan Bulanan</span>
              <span class="info-box-number">Rp. {{ number_format($totalBulan,0,',','.') }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12" style="margin-top:10px">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Sisa Tagihan Lainnya</span>
              <span class="info-box-number">Rp. {{ number_format($totalBebas - $totalBebasPay,0,',','.') }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="box box-info box-solid" style="border:1px solid #5568f1 !important">
      <div class="box-header with-border"><h3 class="box-title">IDENTITAS SISWA</h3></div>
      <div class="box-body">
        <table class="table table-striped">
          <tbody>
            <tr>
              <td width="200">Tahun Pelajaran</td><td width="4">:</td>
              <td>{{ $period ? $period->period_start.'/'.$period->period_end : '-' }}</td>
            </tr>
            <tr><td>NIM</td><td>:</td><td>{{ $student->student_nis }}</td></tr>
            <tr><td>Nama</td><td>:</td><td>{{ $student->student_full_name }}</td></tr>
            <tr><td>Nama Ibu Kandung</td><td>:</td><td>{{ $student->student_name_of_mother }}</td></tr>
            <tr><td>Kelas</td><td>:</td><td>{{ $student->class->class_name ?? '-' }}</td></tr>
            @if(($app_level ?? '')=='senior')
            <tr><td>Unit Sekolah</td><td>:</td><td>{{ $student->majors->majors_name ?? '-' }}</td></tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="box box-info box-solid" style="border:1px solid #5568f1 !important">
      <div class="box-header with-border"><h3 class="box-title">TAGIHAN BULANAN</h3></div>
      <div class="box-body table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr><th>No.</th><th>Bulan</th><th>Tahun</th><th>Total Tagihan</th><th>Sudah Dibayar</th><th>Sisa Tagihan</th><th>Status</th></tr>
          </thead>
          <tbody>
            @forelse($bulanUnpaid as $i => $row)
              @php $tahun = ($row->month_month_id <= 6) ? ($row->payment->period->period_start ?? '') : ($row->payment->period->period_end ?? ''); @endphp
              <tr style="color:red">
                <td>{{ $i+1 }}</td>
                <td>{{ $row->month->month_name ?? '' }}</td>
                <td>{{ $tahun }}</td>
                <td>Rp. {{ number_format($row->bulan_bill,0,',','.') }}</td>
                <td>Rp. 0</td>
                <td>Rp. {{ number_format($row->bulan_bill,0,',','.') }}</td>
                <td><label class="label label-warning">Belum Lunas</label></td>
              </tr>
            @empty
              <tr><td colspan="7" class="text-center">Tidak ada tagihan bulanan.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="box box-info box-solid" style="border:1px solid #2ABB9B !important">
      <div class="box-header with-border"><h3 class="box-title">TAGIHAN LAIN</h3></div>
      <div class="box-body table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr><th>No.</th><th>Jenis Pembayaran</th><th>Total Tagihan</th><th>Dibayar</th><th>Status</th></tr>
          </thead>
          <tbody>
            @forelse($bebasList as $i => $row)
              @php
                $sisa = $row->bebas_bill - $row->bebas_total_pay;
                $namePay = ($row->payment->pos->pos_name ?? '') . ' - T.A ' . ($row->payment->period->period_start ?? '') . '/' . ($row->payment->period->period_end ?? '');
                $lunas = $row->bebas_bill == $row->bebas_total_pay;
              @endphp
              <tr style="color:{{ $lunas ? '#00a65a' : 'red' }}">
                <td>{{ $i+1 }}</td>
                <td>{{ $namePay }}</td>
                <td>Rp. {{ number_format($sisa,0,',','.') }}</td>
                <td>Rp. {{ number_format($row->bebas_total_pay,0,',','.') }}</td>
                <td><label class="label {{ $lunas?'label-success':'label-warning' }}">{{ $lunas?'Lunas':'Belum Lunas' }}</label></td>
              </tr>
            @empty
              <tr><td colspan="5" class="text-center">Tidak ada tagihan lain.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-newspaper-o"></i> Informasi</h3></div>
      <div class="box-body">
        @forelse($infos as $info)
          <div class="media">
            <div class="media-body">
              <h4 class="media-heading">{{ $info->information_title }}</h4>
              <p>{{ \Illuminate\Support\Str::limit($info->information_desc, 150) }}</p>
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
  <div class="col-md-6">
    <div class="box box-success">
      <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-calendar"></i> Agenda / Hari Libur</h3></div>
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
