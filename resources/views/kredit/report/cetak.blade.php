<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: Arial, sans-serif; font-size: 10px; }
    h2, h3 { text-align: center; margin: 0; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #333; padding: 4px 6px; }
    th { background: #f0f0f0; text-align: center; }
    .total { font-weight: bold; background: #f9f9f9; }
    .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 8px; margin-bottom: 10px; }
  </style>
</head>
<body>
<div class="header">
  <h2>{{ strtoupper($setting['school'] ?? 'SMK') }}</h2>
  <h3>LAPORAN PEMBAYARAN SISWA</h3>
  <p>{{ $setting['address'] ?? '' }}</p>
  <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
</div>
<table>
  <thead>
    <tr>
      <th>No</th><th>Tgl Bayar</th><th>No. Bukti</th><th>Nama Siswa</th>
      <th>Kelas</th><th>Jenis</th><th>Bulan</th><th>Nominal</th>
    </tr>
  </thead>
  <tbody>
    @php $total = 0; @endphp
    @foreach($payouts as $i => $b)
    <tr>
      <td>{{ $i+1 }}</td>
      <td>{{ $b->bulan_date_pay ? \Carbon\Carbon::parse($b->bulan_date_pay)->format('d/m/Y') : '-' }}</td>
      <td>{{ $b->bulan_number_pay ?? '-' }}</td>
      <td>{{ $b->student->student_full_name ?? '-' }}</td>
      <td>{{ $b->student->class->class_name ?? '-' }}</td>
      <td>{{ $b->payment->pos->pos_name ?? '-' }}</td>
      <td>{{ $b->month->month_name ?? '-' }}</td>
      <td style="text-align:right">Rp {{ number_format($b->bulan_bill, 0, ',', '.') }}</td>
    </tr>
    @php $total += $b->bulan_bill; @endphp
    @endforeach
  </tbody>
  <tfoot>
    <tr class="total">
      <td colspan="7" style="text-align:right"><strong>TOTAL</strong></td>
      <td style="text-align:right"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
    </tr>
  </tfoot>
</table>
</body>
</html>
