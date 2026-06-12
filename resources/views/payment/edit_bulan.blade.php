@extends('layouts.app')
@section('content')
<div class="box box-success">
  <div class="box-header with-border">
    <h3 class="box-title">Edit Tarif Pembayaran</h3>
    <a href="{{ route('payment.viewBulan', $payment->payment_id) }}" class="btn btn-default btn-xs pull-right">
      <i class="fa fa-arrow-left"></i> Kembali
    </a>
  </div>
  <div class="box-body">
    <table class="table table-bordered" style="width:60%;margin-bottom:15px">
      <tr><td width="150">Jenis Pembayaran</td><td>: {{ $payment->pos->pos_name ?? '' }} - T.A {{ $payment->period->period_start ?? '' }}/{{ $payment->period->period_end ?? '' }}</td></tr>
      <tr><td>NIS</td><td>: {{ $student->student_nis }}</td></tr>
      <tr><td>Nama</td><td>: {{ $student->student_full_name }}</td></tr>
      <tr><td>Kelas</td><td>: {{ $student->class->class_name ?? '-' }}</td></tr>
    </table>

    <form method="POST" action="{{ route('payment.updateBulan', [$payment->payment_id, $student->student_id]) }}">
      @csrf
      <table class="table table-bordered">
        <thead><tr><th>Bulan</th><th>Status</th><th>Tarif (Rp.)</th></tr></thead>
        <tbody>
          @foreach($bulans as $b)
          <tr>
            <td>
              <strong>{{ $b->month->month_name ?? '' }}</strong>
              <input type="hidden" name="bulan_id[]" value="{{ $b->bulan_id }}">
            </td>
            <td>
              @if($b->bulan_status)
                <span class="label label-success">Lunas ({{ \Carbon\Carbon::parse($b->bulan_date_pay)->format('d/m/Y') }})</span>
              @else
                <span class="label label-default">Belum Bayar</span>
              @endif
            </td>
            <td><input type="number" name="bulan_bill[]" class="form-control" value="{{ $b->bulan_bill }}" required></td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
      <a href="{{ route('payment.viewBulan', $payment->payment_id) }}" class="btn btn-default">Batal</a>
    </form>
  </div>
</div>
@endsection
