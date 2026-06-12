<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: Arial, sans-serif; font-size: 12px; }
    .bukti { border: 2px solid #333; padding: 15px; max-width: 700px; margin: 0 auto; }
    .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 12px; }
    .header h3 { margin: 2px 0; font-size: 15px; }
    .header p { margin: 2px 0; font-size: 11px; }
    table { width: 100%; }
    td { padding: 4px 6px; vertical-align: top; }
    td:first-child { width: 38%; font-weight: bold; }
    .nominal { text-align: center; margin: 15px 0; }
    .nominal span { font-size: 18px; font-weight: bold; border: 2px solid #333; padding: 6px 20px; }
    .ttd { text-align: right; margin-top: 20px; }
    .footer { margin-top: 12px; border-top: 1px dashed #666; padding-top: 8px; text-align: center; font-size: 10px; color: #666; }
  </style>
</head>
<body>
<div class="bukti">
  <div class="header">
    <h3>BUKTI PEMBAYARAN</h3>
    <h3>{{ strtoupper($setting['school'] ?? '') }}</h3>
    <p>{{ $setting['address'] ?? '' }}</p>
  </div>
  <table>
    <tr><td>No. Bukti</td><td>: {{ $bulan->bulan_number_pay ?? '-' }}</td></tr>
    <tr><td>Tanggal Bayar</td><td>: {{ $bulan->bulan_date_pay ? \Carbon\Carbon::parse($bulan->bulan_date_pay)->format('d/m/Y') : '-' }}</td></tr>
    <tr><td>NIS</td><td>: {{ $bulan->student->student_nis ?? '-' }}</td></tr>
    <tr><td>Nama Siswa</td><td>: <strong>{{ $bulan->student->student_full_name ?? '-' }}</strong></td></tr>
    <tr><td>Kelas</td><td>: {{ $bulan->student->class->class_name ?? '-' }}</td></tr>
    <tr><td>Unit Pendidikan</td><td>: {{ $bulan->student->majors->majors_name ?? '-' }}</td></tr>
    <tr><td>Jenis Pembayaran</td><td>: {{ $bulan->payment->pos->pos_name ?? '-' }}</td></tr>
    <tr><td>Bulan</td><td>: {{ $bulan->month->month_name ?? '-' }}</td></tr>
    <tr><td>Tahun Pelajaran</td><td>: {{ $bulan->payment->period->period_start ?? '-' }}/{{ $bulan->payment->period->period_end ?? '-' }}</td></tr>
    <tr><td>Keterangan</td><td>: {{ $bulan->bulan_pay_desc ?? '-' }}</td></tr>
    <tr><td>Petugas</td><td>: {{ $bulan->user->user_full_name ?? '-' }}</td></tr>
  </table>
  <div class="nominal">
    <span>Rp {{ number_format($bulan->bulan_bill, 0, ',', '.') }}</span>
  </div>
  <div class="ttd">
    <p>{{ $setting['school'] ?? '' }}, {{ \Carbon\Carbon::parse($bulan->bulan_date_pay)->locale('id')->isoFormat('D MMMM Y') }}</p>
    <br><br><br>
    <p><strong>{{ $bulan->user->user_full_name ?? 'Petugas' }}</strong></p>
  </div>
  <div class="footer">Bukti ini sah sebagai tanda pembayaran resmi dari {{ $setting['school'] ?? '' }}</div>
</div>
</body>
</html>
