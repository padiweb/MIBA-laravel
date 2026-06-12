<!DOCTYPE html>
<html>
<head>
<title>{{ $student->student_full_name }}</title>
<style type="text/css">
  @page { margin-top: 0.5cm; margin-left: 1cm; margin-right: 1cm; margin-bottom: 0.1cm; }
  body { font-family: sans-serif; }
  .name-school { font-size: 12pt; font-weight: bold; text-transform: uppercase; margin-bottom: 0; }
  .alamat { font-size: 9pt; margin-top: 2px; margin-bottom: 0; }
  .detail { font-size: 10pt; font-weight: bold; margin-top: 6px; margin-bottom: 4px; }
  table { font-family: verdana, arial, sans-serif; font-size: 11px; color: #333333; border-collapse: collapse; width: 100%; }
  th { padding: 8px; border-color: #666666; background-color: #dedede; text-align: left; }
  td { text-align: left; border-color: #666666; background-color: #ffffff; padding: 4px; }
  hr { border: none; height: 1px; background-color: #333; }
  .container { position: relative; }
  .topright { position: absolute; top: 0; right: 0; font-size: 14px; border: 1px solid #333; padding: 5px; }
</style>
</head>
<body>

<div class="container">
  <div class="topright">Kwitansi Pembayaran</div>
</div>
<p class="name-school">{{ $setting['school'] ?? '' }}</p>
<p class="alamat">
  {{ $setting['address'] ?? '' }}<br>
  Telp. {{ $setting['phone'] ?? '' }}
</p>
<hr>

<table style="margin-top:5px;margin-bottom:5px">
  <tbody>
    <tr>
      <td style="width:100px">Nomor Induk Santri (NIS)</td>
      <td style="width:5px">:</td>
      <td style="width:150px">{{ $student->student_nis }}</td>
      <td style="width:130px">Tanggal Pembayaran</td>
      <td style="width:5px">:</td>
      <td style="width:131px">{{ \Carbon\Carbon::parse($request->d)->locale('id')->isoFormat('D MMMM Y') }}</td>
    </tr>
    <tr>
      <td>Nama</td>
      <td>:</td>
      <td>{{ $student->student_full_name }}</td>
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

<table style="border-style:solid">
  <tr>
    <th style="border-top:1px solid;border-bottom:1px solid;text-align:center">No.</th>
    <th style="border-top:1px solid;border-bottom:1px solid">Pembayaran</th>
    <th style="border-top:1px solid;border-bottom:1px solid">Total Nominal</th>
    <th colspan="2" style="border-top:1px solid;border-bottom:1px solid;text-align:center">Jumlah Pembayaran</th>
  </tr>

  @php $i = 1; @endphp
  @foreach($bulans as $row)
    @php
      $namePay = ($row->payment->pos->pos_name ?? '') . ' - T.P ' . ($row->payment->period->period_start ?? '') . '/' . ($row->payment->period->period_end ?? '');
      $mont = ($row->month_month_id <= 6) ? ($row->payment->period->period_start ?? '') : ($row->payment->period->period_end ?? '');
    @endphp
    <tr>
      <td style="border-bottom:1px solid;padding-top:10px;padding-bottom:10px;text-align:center">{{ $i }}</td>
      <td style="border-bottom:1px solid">
        {{ $namePay }} - ({{ $row->month->month_name ?? '' }} {{ $mont }})
        @if($row->bulan_pay_desc)
          <br><b style="font-size:9px">Keterangan: {{ $row->bulan_pay_desc }}</b>
        @endif
      </td>
      <td style="border-bottom:1px solid">Rp. {{ number_format($row->bulan_bill,0,',','.') }}</td>
      <td style="border-bottom:1px solid">Rp.</td>
      <td style="border-bottom:1px solid;text-align:right">{{ number_format($row->bulan_bill,0,',','.') }}</td>
    </tr>
    @php $i++; @endphp
  @endforeach

  @foreach($free as $row)
    @php
      $bebas = $row->bebas;
      $namePayFree = ($bebas->payment->pos->pos_name ?? '') . ' - T.P ' . ($bebas->payment->period->period_start ?? '') . '/' . ($bebas->payment->period->period_end ?? '');
    @endphp
    <tr>
      <td style="border-bottom:1px solid;padding-top:10px;padding-bottom:10px;text-align:center">{{ $i }}</td>
      <td style="border-bottom:1px solid">
        {{ $namePayFree }}
        @if($row->bebas_pay_desc)
          <br><b style="font-size:9px">Keterangan: {{ $row->bebas_pay_desc }}</b>
        @endif
      </td>
      <td style="border-bottom:1px solid">
        Rp. {{ number_format($bebas->bebas_bill ?? 0,0,',','.') }}<br>
        <b style="font-size:9px">Sisa Pembayaran: Rp. {{ number_format(($bebas->bebas_bill ?? 0)-($bebas->bebas_total_pay ?? 0),0,',','.') }}</b>
      </td>
      <td style="border-bottom:1px solid">Rp.</td>
      <td style="border-bottom:1px solid;text-align:right">
        {{ number_format($row->bebas_pay_bill,0,',','.') }}<br>
        <b style="font-size:9px">Total Bayar: Rp. {{ number_format($bebas->bebas_total_pay ?? 0,0,',','.') }}</b>
      </td>
    </tr>
    @php $i++; @endphp
  @endforeach

  <tr>
    <td colspan="2" style="text-align:center;padding-top:5px;padding-bottom:5px">
      {{ $setting['city'] ?? '' }}, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}
    </td>
    <td style="background-color:#dedede;font-weight:bold;border-bottom:1px solid">Total Pembayaran</td>
    <td style="background-color:#dedede;font-weight:bold;border-bottom:1px solid">Rp.</td>
    <td style="background-color:#dedede;font-weight:bold;border-bottom:1px solid;text-align:right">{{ number_format($summonth+$sumbeb,0,',','.') }}</td>
  </tr>
  <tr><td colspan="2" style="text-align:center"></td></tr>
  <tr><td>&nbsp;</td></tr>
  <tr><td>&nbsp;</td></tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <td colspan="2" style="text-align:center">({{ ucfirst($petugas ?? '') }})</td>
  </tr>
</table>
<br>
</body>
</html>
