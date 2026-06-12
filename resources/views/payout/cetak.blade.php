<!DOCTYPE html>
<html>
<head>
<title>{{ $student->student_full_name }}</title>
<style type="text/css">
  @page { margin: 1.2cm 1.5cm; }
  body { font-family: Verdana, Arial, sans-serif; color: #333; }
  .name-school { font-size: 13pt; font-weight: bold; text-transform: uppercase; margin: 0; }
  .alamat { font-size: 9pt; margin: 2px 0 0; line-height: 1.4; }
  .detail { font-size: 10pt; font-weight: bold; margin: 10px 0 4px; }
  table { font-size: 11px; border-collapse: collapse; width: 100%; }
  th { padding: 6px 8px; border-top: 1px solid #666; border-bottom: 1px solid #666; background-color: #dedede; text-align: left; }
  td { padding: 5px 8px; text-align: left; }
  hr { border: none; border-top: 1px solid #333; margin: 6px 0; }
  .container { position: relative; }
  .topright { position: absolute; top: 0; right: 0; font-size: 13px; border: 1px solid #333; padding: 6px 12px; font-weight: bold; }
  .info-table td { padding: 3px 8px; border: none; }
  .rincian td { border-bottom: 1px solid #999; }
  .total-row td { background-color: #dedede; font-weight: bold; border-bottom: 1px solid #999; }
  .ttd-row td { border: none; padding-top: 6px; }
  .signature-name { text-align: center; padding-top: 55px; border-bottom: 1px solid #333; display: inline-block; min-width: 200px; font-weight: bold; }
</style>
</head>
<body>

<div class="container">
  <div class="topright">Kwitansi Pembayaran</div>
  <p class="name-school">{{ $setting['school'] ?? '' }}</p>
  <p class="alamat">
    {{ $setting['address'] ?? '' }}<br>
    Telp. {{ $setting['phone'] ?? '' }}
  </p>
</div>
<hr>

<table class="info-table" style="margin-top:6px;margin-bottom:6px">
  <tbody>
    <tr>
      <td style="width:160px">Nomor Induk Santri (NIS)</td>
      <td style="width:8px">:</td>
      <td style="width:170px"><strong>{{ $student->student_nis }}</strong></td>
      <td style="width:130px">Tanggal Pembayaran</td>
      <td style="width:8px">:</td>
      <td>{{ \Carbon\Carbon::parse($request->d)->locale('id')->isoFormat('D MMMM Y') }}</td>
    </tr>
    <tr>
      <td>Nama</td>
      <td>:</td>
      <td><strong>{{ $student->student_full_name }}</strong></td>
      <td>Tahun Pelajaran</td>
      <td>:</td>
      <td>{{ $period->period_start ?? '' }}/{{ $period->period_end ?? '' }}</td>
    </tr>
    <tr>
      <td>Kelas</td>
      <td>:</td>
      <td>{{ $student->class->class_name ?? '' }}&nbsp;{{ $student->majors->majors_name ?? '' }}</td>
      <td colspan="3"></td>
    </tr>
  </tbody>
</table>
<hr>

<p class="detail">Rincian Pembayaran:</p>

<table>
  <tr>
    <th style="width:6%;text-align:center">No.</th>
    <th style="width:44%">Pembayaran</th>
    <th style="width:20%">Total Nominal</th>
    <th colspan="2" style="width:30%;text-align:center">Jumlah Pembayaran</th>
  </tr>

  @php $i = 1; @endphp
  @foreach($bulans as $row)
    @php
      $namePay = ($row->payment->pos->pos_name ?? '') . ' - T.P ' . ($row->payment->period->period_start ?? '') . '/' . ($row->payment->period->period_end ?? '');
      $mont = ($row->month_month_id <= 6) ? ($row->payment->period->period_start ?? '') : ($row->payment->period->period_end ?? '');
    @endphp
    <tr class="rincian">
      <td style="text-align:center">{{ $i }}</td>
      <td>
        {{ $namePay }} - ({{ $row->month->month_name ?? '' }} {{ $mont }})
        @if($row->bulan_pay_desc)
          <br><span style="font-size:9px">Keterangan: {{ $row->bulan_pay_desc }}</span>
        @endif
      </td>
      <td>Rp. {{ number_format($row->bulan_bill,0,',','.') }}</td>
      <td style="width:8%">Rp.</td>
      <td style="text-align:right">{{ number_format($row->bulan_bill,0,',','.') }}</td>
    </tr>
    @php $i++; @endphp
  @endforeach

  @foreach($free as $row)
    @php
      $bebas = $row->bebas;
      $namePayFree = ($bebas->payment->pos->pos_name ?? '') . ' - T.P ' . ($bebas->payment->period->period_start ?? '') . '/' . ($bebas->payment->period->period_end ?? '');
    @endphp
    <tr class="rincian">
      <td style="text-align:center">{{ $i }}</td>
      <td>
        {{ $namePayFree }}
        @if($row->bebas_pay_desc)
          <br><span style="font-size:9px">Keterangan: {{ $row->bebas_pay_desc }}</span>
        @endif
      </td>
      <td>
        Rp. {{ number_format($bebas->bebas_bill ?? 0,0,',','.') }}<br>
        <span style="font-size:9px">Sisa Pembayaran: Rp. {{ number_format(($bebas->bebas_bill ?? 0)-($bebas->bebas_total_pay ?? 0),0,',','.') }}</span>
      </td>
      <td>Rp.</td>
      <td style="text-align:right">
        {{ number_format($row->bebas_pay_bill,0,',','.') }}<br>
        <span style="font-size:9px">Total Bayar: Rp. {{ number_format($bebas->bebas_total_pay ?? 0,0,',','.') }}</span>
      </td>
    </tr>
    @php $i++; @endphp
  @endforeach

  <tr class="total-row">
    <td colspan="2" style="text-align:center;font-weight:normal">
      {{ $setting['city'] ?? '' }}, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}
    </td>
    <td>Total Pembayaran</td>
    <td>Rp.</td>
    <td style="text-align:right">{{ number_format($summonth+$sumbeb,0,',','.') }}</td>
  </tr>
</table>

<table style="margin-top:0">
  <tr class="ttd-row">
    <td colspan="2" style="text-align:center;width:40%"></td>
    <td colspan="3" style="text-align:center;width:60%">
      <div class="signature-name">{{ ucfirst($petugas ?? '') }}</div>
    </td>
  </tr>
</table>

</body>
</html>
