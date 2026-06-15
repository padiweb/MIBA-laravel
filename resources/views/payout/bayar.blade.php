@extends('layouts.app')
@section('content')
<div class="miba-card">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-calendar"></i> Transaksi Bulanan — {{ $payment->pos->pos_name??'' }} · {{ $student->student_full_name }}</div>
    <div style="display:flex;gap:8px">
      @if(($user_role_id??0)==1)
      <a href="{{ route('payment.editBulan',[$payment->payment_id,$student->student_id]) }}" class="btn-miba btn-miba-sm btn-accent-miba"><i class="fa fa-edit"></i> Edit Tarif</a>
      @endif
      <a href="{{ route('payout.index',['n'=>$payment->period_period_id,'r'=>$student->student_nis]) }}" class="btn-miba btn-miba-sm btn-ghost-miba"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
  </div>
  <div style="display:grid;grid-template-columns:auto 1fr;gap:0">
    <div style="padding:16px;border-right:1px solid var(--border);min-width:200px">
      @if($student->student_img)
        <img src="{{ asset('uploads/student/'.$student->student_img) }}" style="width:64px;height:64px;border-radius:50%;object-fit:cover;margin-bottom:8px">
      @endif
      <div style="font-weight:700;margin-bottom:4px">{{ $student->student_full_name }}</div>
      <div style="font-size:12px;color:var(--text-muted)">{{ $student->student_nis }}</div>
      <div style="font-size:12px;color:var(--text-muted)">{{ $student->class->class_name??'' }}</div>
    </div>
    <div class="miba-table-wrap">
      <table class="miba-table">
        <thead><tr><th>Bulan</th><th>Tagihan</th><th>Status</th><th>Tanggal Bayar</th><th>Keterangan</th><th style="text-align:center">Aksi</th></tr></thead>
        <tbody>
          @foreach($bulans as $b)
          <tr>
            <td style="font-weight:600">{{ $b->month->month_name }}</td>
            <td>Rp {{ number_format($b->bulan_bill,0,',','.') }}</td>
            <td>
              <span class="badge-miba {{ $b->bulan_status?'badge-success':'badge-muted' }}">{{ $b->bulan_status?'Lunas':'Belum Bayar' }}</span>
            </td>
            <td style="font-size:12px;color:var(--text-muted)">{{ $b->bulan_status&&$b->bulan_date_pay ? \Carbon\Carbon::parse($b->bulan_date_pay)->format('d/m/Y') : '-' }}</td>
            <td style="font-size:12px">{{ $b->bulan_pay_desc ?: '-' }}</td>
            <td style="text-align:center">
              <div style="display:flex;gap:4px;justify-content:center">
                @if($b->bulan_status)
                  <a href="{{ route('payout.cetak',$b->bulan_id) }}" target="_blank" class="btn-miba btn-miba-xs btn-ghost-miba"><i class="fa fa-print"></i></a>
                  <a href="{{ route('payout.unpay',[$payment->payment_id,$student->student_id,$b->bulan_id]) }}" onclick="return confirm('Batalkan pembayaran?')" class="btn-miba btn-miba-xs btn-danger-miba"><i class="fa fa-times"></i></a>
                @else
                  <a href="{{ route('payout.pay',[$payment->payment_id,$student->student_id,$b->bulan_id]) }}" onclick="return confirm('Bayar bulan {{ $b->month->month_name }}?')" class="btn-miba btn-miba-xs btn-primary-miba"><i class="fa fa-check"></i> Bayar</a>
                @endif
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection