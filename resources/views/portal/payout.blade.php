@extends('portal.layout')
@section('content')

<div class="box box-success">
  <div class="box-header with-border"><h3 class="box-title">Cek Pembayaran Siswa</h3></div>
  <div class="box-body">
    <form method="GET" class="form-inline" style="margin-bottom:15px">
      <label>Tahun Pelajaran</label>
      <select name="n" class="form-control" onchange="this.form.submit()">
        @foreach($periods as $p)
          <option value="{{ $p->period_id }}" {{ $periodId==$p->period_id?'selected':'' }}>{{ $p->period_start }}/{{ $p->period_end }}</option>
        @endforeach
      </select>
    </form>

    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab"><b>Bulanan</b></a></li>
        <li><a href="#tab_2" data-toggle="tab"><b>Bebas</b></a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <table class="table table-bordered table-striped">
            <thead>
              <tr><th>No.</th><th>Jenis Pembayaran</th><th>Bulan</th><th>Tagihan</th><th>Status</th><th>Tanggal Bayar</th></tr>
            </thead>
            <tbody>
              @forelse($bulans as $i => $row)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $row->payment->pos->pos_name ?? '-' }}</td>
                <td>{{ $row->month->month_name ?? '' }}</td>
                <td>Rp. {{ number_format($row->bulan_bill,0,',','.') }}</td>
                <td>
                  @if($row->bulan_status)
                    <label class="label label-success">Lunas</label>
                  @else
                    <label class="label label-warning">Belum Lunas</label>
                  @endif
                </td>
                <td>{{ $row->bulan_status && $row->bulan_date_pay ? \Carbon\Carbon::parse($row->bulan_date_pay)->locale('id')->isoFormat('D MMMM Y') : '-' }}</td>
              </tr>
              @empty
              <tr><td colspan="6" class="text-center">Tidak ada data tagihan bulanan</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="tab-pane" id="tab_2">
          <table class="table table-bordered table-striped">
            <thead>
              <tr><th>No.</th><th>Jenis Pembayaran</th><th>Total Tagihan</th><th>Dibayar</th><th>Sisa</th><th>Status</th></tr>
            </thead>
            <tbody>
              @forelse($bebasList as $i => $row)
              @php $sisa = $row->bebas_bill - $row->bebas_total_pay; $lunas = $sisa <= 0; @endphp
              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $row->payment->pos->pos_name ?? '-' }} - T.A {{ $row->payment->period->period_start ?? '' }}/{{ $row->payment->period->period_end ?? '' }}</td>
                <td>Rp. {{ number_format($row->bebas_bill,0,',','.') }}</td>
                <td>Rp. {{ number_format($row->bebas_total_pay,0,',','.') }}</td>
                <td>Rp. {{ number_format($sisa,0,',','.') }}</td>
                <td><label class="label {{ $lunas?'label-success':'label-warning' }}">{{ $lunas?'Lunas':'Belum Lunas' }}</label></td>
              </tr>
              @empty
              <tr><td colspan="6" class="text-center">Tidak ada data tagihan lain</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
