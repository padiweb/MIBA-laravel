<!DOCTYPE html>
<html>
<head>
<title>Detail Rincian Tagihan - {{ $student->student_full_name }}</title>
<style type="text/css">
  .upper { text-transform: uppercase; }
  .style12 { font-size: 9px }
  .title { font-size: 14pt; text-align: center; font-weight: bold; }
  .tp { font-size: 14pt; text-align: center; font-weight: bold; }
  body { font-family: sans-serif; }
  table { border-collapse: collapse; font-size: 9pt; width: 100%; padding-left: 5px; }
  th, td { border: 1px solid #333; padding: 4px; }
</style>
</head>
<body>
  <p class="title">RINCIAN PEMBAYARAN ADMINISTRASI</p>
  <p class="tp">TAHUN PELAJARAN {{ $period->period_start ?? '' }}/{{ $period->period_end ?? '' }}</p>

  <table width="100%" border="0" style="margin-bottom:10px">
    <tr style="border:none">
      <td width="150" style="border:none">Nomor Induk Santri</td>
      <td width="5" style="border:none">:</td>
      <td style="border:none">{{ $student->student_nis }}</td>
    </tr>
    <tr style="border:none">
      <td style="border:none">Nama Lengkap</td>
      <td style="border:none">:</td>
      <td style="border:none">{{ $student->student_full_name }}</td>
    </tr>
    <tr style="border:none">
      <td style="border:none">Kelas</td>
      <td style="border:none">:</td>
      <td style="border:none">{{ $student->class->class_name ?? '-' }}</td>
    </tr>
    @if(($app_level ?? '') == 'senior')
    <tr style="border:none">
      <td style="border:none">Unit Sekolah</td>
      <td style="border:none">:</td>
      <td style="border:none">{{ $student->majors->majors_name ?? '-' }}</td>
    </tr>
    @endif
  </table>

  <table width="100%">
    <tr>
      <th style="height:30px">NO</th>
      <th>NAMA PEMBAYARAN</th>
      <th>TANGGAL PEMBAYARAN</th>
      <th>BIAYA</th>
      <th>KETERANGAN</th>
    </tr>
    @php $i = 1; @endphp
    @foreach($bulans as $row)
      @php $mont = ($row->month_month_id <= 6) ? ($row->payment->period->period_start ?? '') : ($row->payment->period->period_end ?? ''); @endphp
      <tr>
        <td style="text-align:center">{{ $i }}</td>
        <td style="white-space:nowrap;padding:0 5px">{{ $row->payment->pos->pos_name ?? '-' }} - ({{ $row->month->month_name ?? '' }} {{ $mont }})</td>
        <td style="padding:0 5px">{{ $row->bulan_status==1 ? \Carbon\Carbon::parse($row->bulan_date_pay)->locale('id')->isoFormat('D MMMM Y') : '-' }}</td>
        <td style="padding:0 5px;white-space:nowrap">{{ $row->bulan_status==0 ? 'Rp. '.number_format($row->bulan_bill,0,',','.') : 'Rp. -' }}</td>
        <td style="padding:0 5px">{{ $row->bulan_status==1 ? 'Lunas' : 'Belum Lunas' }}</td>
      </tr>
      @php $i++; @endphp
    @endforeach

    @foreach($bebas as $row)
      @php $sisa = $row->bebas_bill - $row->bebas_total_pay; @endphp
      <tr>
        <td style="text-align:center">{{ $i }}</td>
        <td style="padding:0 5px">{{ $row->payment->pos->pos_name ?? '-' }}</td>
        <td style="padding:0 5px">{{ $row->bebas_total_pay > 0 ? \Carbon\Carbon::parse($row->bebas_last_update)->locale('id')->isoFormat('D MMMM Y') : '-' }}</td>
        <td style="padding:0 5px">{{ $sisa != 0 ? 'Rp. '.number_format($sisa,0,',','.') : 'Rp. -' }}</td>
        <td style="word-break:break-all;word-wrap:break-word;padding:0 5px">
          {{ $sisa == 0 ? 'Lunas' : 'Belum Lunas' }}
          @if($row->bebas_desc)
            <br><b style="font-size:9px"><u>RINCIAN TAGIHAN: </u><br><i>{{ $row->bebas_desc }}</i></b>
          @endif
        </td>
      </tr>
      @php $i++; @endphp
    @endforeach
  </table>

  <table style="width:100%;margin-top:25px;font-size:10pt;border:none">
    <tr style="border:none">
      <td style="border:none" class="upper">{{ $setting['city'] ?? '' }}, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</td>
    </tr>
    <tr style="border:none"><td style="border:none">Petugas</td></tr>
  </table>
  <br><br><br><br>
  <table width="100%" style="font-size:10pt;border:none">
    <tr style="border:none">
      <td style="border:none"><strong><u><span class="upper">( {{ session('user_fullname') }} )</span></u></strong></td>
    </tr>
  </table>
</body>
</html>
