<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Riwayat Angsuran</title>
  <link rel="stylesheet" href="{{ asset('media/css/bootstrap.min.css') }}">
</head>
<body style="padding:10px">
@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif
<table class="table table-bordered table-striped">
  <thead>
    <tr><th>No</th><th>Tanggal</th><th>Jumlah Bayar</th><th>Keterangan</th><th>Aksi</th></tr>
  </thead>
  <tbody>
    @forelse($bills as $i => $row)
    <tr>
      <td>{{ $i+1 }}</td>
      <td>{{ \Carbon\Carbon::parse($row->bebas_pay_input_date)->locale('id')->isoFormat('D MMMM Y') }}</td>
      <td class="text-right">Rp. {{ number_format($row->bebas_pay_bill,0,',','.') }}</td>
      <td>{{ $row->bebas_pay_desc }}</td>
      <td>
        <a class="btn btn-danger btn-xs"
           onclick="return confirm('Anda akan menghapus pembayaran {{ $row->bebas_pay_desc }}?')"
           title="Hapus"
           href="{{ route('payout.deletePayFree', [$bebas->payment_payment_id, $bebas->student_student_id, $bebas->bebas_id, $row->bebas_pay_id]) }}">
          <span class="glyphicon glyphicon-trash"></span> Hapus
        </a>
      </td>
    </tr>
    @empty
    <tr><td colspan="5" class="text-center">Belum ada angsuran</td></tr>
    @endforelse
    @if($bills->count())
    <tr class="success">
      <td colspan="2"><b>Total Sudah Bayar</b></td>
      <td class="text-right"><b>Rp. {{ number_format($totalPay,0,',','.') }}</b></td>
      <td colspan="2"><b>Tunggakan: Rp. {{ number_format($bebas->bebas_bill - $totalPay,0,',','.') }}</b></td>
    </tr>
    @endif
  </tbody>
</table>
</body>
</html>
