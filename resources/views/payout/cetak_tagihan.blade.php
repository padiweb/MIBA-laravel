<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: Arial, sans-serif; font-size: 11px; }
    h2, h3 { text-align: center; margin: 2px; }
    .header { border-bottom: 2px solid #333; padding-bottom: 8px; margin-bottom: 10px; text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 8px; }
    th, td { border: 1px solid #333; padding: 4px 6px; }
    th { background: #f0f0f0; }
    .lunas { background: #d4edda; }
    .belum { background: #f8d7da; }
    .total-row { font-weight: bold; background: #f9f9f9; }
  </style>
</head>
<body>
<div class="header">
  <h2>{{ strtoupper($setting['school'] ?? '') }}</h2>
  <h3>RINCIAN TAGIHAN PEMBAYARAN</h3>
  <p>Tahun Pelajaran: {{ $period->period_start ?? '-' }}/{{ $period->period_end ?? '-' }}</p>
</div>

<table style="width:60%;margin-bottom:10px;border:none">
  <tr><td style="border:none">NIS</td><td style="border:none">: {{ $student->student_nis }}</td></tr>
  <tr><td style="border:none">Nama</td><td style="border:none">: <strong>{{ $student->student_full_name }}</strong></td></tr>
  <tr><td style="border:none">Kelas</td><td style="border:none">: {{ $student->class->class_name ?? '-' }}</td></tr>
  <tr><td style="border:none">Unit Pendidikan</td><td style="border:none">: {{ $student->majors->majors_name ?? '-' }}</td></tr>
</table>

<table>
  <thead>
    <tr><th>No</th><th>Jenis</th><th>Bulan</th><th>Tagihan</th><th>Status</th><th>Tgl Bayar</th><th>No. Bukti</th></tr>
  </thead>
  <tbody>
    @php $total = 0; $totalBayar = 0; @endphp
    @foreach($bulans as $i => $b)
    <tr class="{{ $b->bulan_status ? 'lunas' : 'belum' }}">
      <td>{{ $i+1 }}</td>
      <td>{{ $b->payment->pos->pos_name ?? '-' }}</td>
      <td>{{ $b->month->month_name ?? '-' }}</td>
      <td style="text-align:right">Rp {{ number_format($b->bulan_bill, 0, ',', '.') }}</td>
      <td style="text-align:center">{{ $b->bulan_status ? 'LUNAS' : 'BELUM' }}</td>
      <td>{{ $b->bulan_date_pay ? \Carbon\Carbon::parse($b->bulan_date_pay)->format('d/m/Y') : '-' }}</td>
      <td>{{ $b->bulan_number_pay ?? '-' }}</td>
    </tr>
    @php $total += $b->bulan_bill; if($b->bulan_status) $totalBayar += $b->bulan_bill; @endphp
    @endforeach
  </tbody>
  <tfoot>
    <tr class="total-row">
      <td colspan="3" style="text-align:right">TOTAL TAGIHAN</td>
      <td style="text-align:right">Rp {{ number_format($total, 0, ',', '.') }}</td>
      <td colspan="3"></td>
    </tr>
    <tr class="total-row">
      <td colspan="3" style="text-align:right">SUDAH DIBAYAR</td>
      <td style="text-align:right">Rp {{ number_format($totalBayar, 0, ',', '.') }}</td>
      <td colspan="3"></td>
    </tr>
    <tr class="total-row">
      <td colspan="3" style="text-align:right">SISA TAGIHAN</td>
      <td style="text-align:right">Rp {{ number_format($total-$totalBayar, 0, ',', '.') }}</td>
      <td colspan="3"></td>
    </tr>
  </tfoot>
</table>
<p style="text-align:right;margin-top:10px">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>
