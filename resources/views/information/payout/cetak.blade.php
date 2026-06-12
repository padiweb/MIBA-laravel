<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; }
    .bukti { border: 2px solid #333; padding: 15px; width: 100%; }
    .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 10px; }
    .header h3 { margin: 0; font-size: 14px; }
    .header p { margin: 2px 0; font-size: 11px; }
    table { width: 100%; border-collapse: collapse; }
    td { padding: 4px 8px; vertical-align: top; }
    td:first-child { width: 35%; font-weight: bold; }
    .ttd { margin-top: 20px; text-align: right; }
    .nominal { font-size: 16px; font-weight: bold; color: #333; border: 1px solid #333; padding: 5px 10px; display: inline-block; }
    .footer { margin-top: 10px; border-top: 1px dashed #333; padding-top: 8px; font-size: 10px; color: #666; text-align: center; }
  </style>
</head>
<body>
<div class="bukti">
  <div class="header">
    <h3>BUKTI PEMBAYARAN</h3>
    <h3>{{ strtoupper($setting['school'] ?? 'SMK') }}</h3>
    <p>{{ $setting['address'] ?? '' }}</p>
  </div>

  <table>
    <tr><td>No. Bukti</td><td>: {{ $payout->bulan_number_pay ?? '-' }}</td></tr>
    <tr><td>Tanggal</td><td>: {{ $payout->bulan_date_pay ? \Carbon\Carbon::parse($payout->bulan_date_pay)->format('d/m/Y') : '-' }}</td></tr>
    <tr><td>NIS</td><td>: {{ $payout->student->student_nis ?? '-' }}</td></tr>
    <tr><td>Nama Siswa</td><td>: {{ $payout->student->student_full_name ?? '-' }}</td></tr>
    <tr><td>Kelas</td><td>: {{ $payout->student->class->class_name ?? '-' }}</td></tr>
    <tr><td>Jurusan</td><td>: {{ $payout->student->majors->majors_name ?? '-' }}</td></tr>
    <tr><td>Jenis Pembayaran</td><td>: {{ $payout->payment->pos->pos_name ?? '-' }}</td></tr>
    <tr><td>Bulan</td><td>: {{ $payout->month->month_name ?? '-' }}</td></tr>
    <tr><td>Tahun Pelajaran</td><td>: {{ $payout->payment->period->period_start ?? '-' }}/{{ $payout->payment->period->period_end ?? '-' }}</td></tr>
    <tr><td>Keterangan</td><td>: {{ $payout->bulan_pay_desc ?? '-' }}</td></tr>
  </table>

  <div style="text-align:center; margin-top:15px;">
    <div class="nominal">Rp {{ number_format($payout->bulan_bill, 0, ',', '.') }}</div>
  </div>

  <div class="ttd">
    <p>{{ $setting['school'] ?? '' }}, {{ \Carbon\Carbon::parse($payout->bulan_date_pay)->locale('id')->isoFormat('D MMMM Y') }}</p>
    <br><br><br>
    <p><strong>{{ $payout->user->user_full_name ?? 'Petugas' }}</strong></p>
  </div>

  <div class="footer">
    Bukti ini sah sebagai tanda pembayaran yang dikeluarkan oleh {{ $setting['school'] ?? '' }}
  </div>
</div>
</body>
</html>
