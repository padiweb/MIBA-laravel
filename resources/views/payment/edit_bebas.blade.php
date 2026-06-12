@extends('layouts.app')
@section('content')
<div class="box box-success">
  <div class="box-header with-border">
    <h3 class="box-title">Edit Tarif Tagihan</h3>
    <a href="{{ route('payment.viewBebas', $payment->payment_id) }}" class="btn btn-default btn-xs pull-right">
      <i class="fa fa-arrow-left"></i> Kembali
    </a>
  </div>
  <div class="box-body">
    <table class="table table-bordered" style="width:60%;margin-bottom:15px">
      <tr><td width="150">Jenis Pembayaran</td><td>: {{ $payment->pos->pos_name ?? '' }} - T.A {{ $payment->period->period_start ?? '' }}/{{ $payment->period->period_end ?? '' }}</td></tr>
      <tr><td>NIS</td><td>: {{ $student->student_nis }}</td></tr>
      <tr><td>Nama</td><td>: {{ $student->student_full_name }}</td></tr>
      <tr><td>Sudah Dibayar</td><td>: Rp {{ number_format($bebas->bebas_total_pay,0,',','.') }}</td></tr>
    </table>

    <form method="POST" action="{{ route('payment.updateBebas', [$payment->payment_id, $student->student_id, $bebas->bebas_id]) }}">
      @csrf
      <div class="form-group">
        <label>Total Tagihan (Rp.) <span class="text-danger">*</span></label>
        <input type="number" name="bebas_bill" class="form-control" value="{{ $bebas->bebas_bill }}" required>
      </div>
      <div class="form-group">
        <label>Keterangan</label>
        <textarea name="bebas_desc" class="form-control" rows="4">{{ $bebas->bebas_desc }}</textarea>
      </div>
      <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
      <a href="{{ route('payment.viewBebas', $payment->payment_id) }}" class="btn btn-default">Batal</a>
    </form>
  </div>
</div>
@endsection
