@extends('layouts.app')
@section('content')
<div class="miba-card" style="max-width:500px">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-plus"></i> {{ isset($payment)?'Edit':'Tambah' }} Jenis Pembayaran</div>
    <a href="{{ route('payment.index') }}" class="btn-miba btn-miba-sm btn-ghost-miba"><i class="fa fa-arrow-left"></i> Kembali</a>
  </div>
  <div class="miba-card-body">
    <form method="POST" action="{{ isset($payment)?route('payment.update',$payment->payment_id):route('payment.store') }}">
      @csrf @if(isset($payment)) @method('PUT') @endif
      <div class="miba-form-group">
        <label class="miba-label">Nama Pembayaran <span class="req">*</span></label>
        <select name="pos_pos_id" class="miba-select" required>
          <option value="">-- Pilih --</option>
          @foreach($poses as $pos)
            <option value="{{ $pos->pos_id }}" {{ old('pos_pos_id',$payment->pos_pos_id??'')==$pos->pos_id?'selected':'' }}>{{ $pos->pos_name }}</option>
          @endforeach
        </select>
      </div>
      <div class="miba-form-group">
        <label class="miba-label">Tahun Pelajaran <span class="req">*</span></label>
        <select name="period_period_id" class="miba-select" required>
          <option value="">-- Pilih --</option>
          @foreach($periods as $p)
            <option value="{{ $p->period_id }}" {{ old('period_period_id',$payment->period_period_id??'')==$p->period_id?'selected':'' }}>{{ $p->period_start }}/{{ $p->period_end }}</option>
          @endforeach
        </select>
      </div>
      <div class="miba-form-group">
        <label class="miba-label">Tipe Pembayaran <span class="req">*</span></label>
        <select name="payment_type" class="miba-select" required>
          <option value="">-- Pilih Tipe --</option>
          <option value="BULAN" {{ old('payment_type',$payment->payment_type??'')=='BULAN'?'selected':'' }}>Bulanan</option>
          <option value="BEBAS" {{ old('payment_type',$payment->payment_type??'')=='BEBAS'?'selected':'' }}>Bebas / Lainnya</option>
        </select>
      </div>
      <div style="display:flex;gap:8px">
        <button class="btn-miba btn-primary-miba"><i class="fa fa-save"></i> {{ isset($payment)?'Update':'Simpan' }}</button>
        <a href="{{ route('payment.index') }}" class="btn-miba btn-ghost-miba">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection