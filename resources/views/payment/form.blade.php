@extends('layouts.app')
@section('content')
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">{{ isset($payment) ? 'Edit' : 'Tambah' }} Jenis Pembayaran</h3>
    <div class="box-tools pull-right">
      <a href="{{ route('payment.index') }}" class="btn btn-default btn-sm">
        <i class="fa fa-arrow-left"></i> Kembali
      </a>
    </div>
  </div>
  <div class="box-body">
    @if($errors->any())
      <div class="alert alert-danger">
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif
    <form method="POST" action="{{ isset($payment) ? route('payment.update', $payment->payment_id) : route('payment.store') }}">
      @csrf
      @if(isset($payment)) @method('PUT') @endif
      <div class="form-group">
        <label>Jenis Biaya <span class="text-danger">*</span></label>
        <select name="pos_pos_id" class="form-control" required>
          <option value="">-- Pilih Jenis Biaya --</option>
          @foreach($poses as $pos)
            <option value="{{ $pos->pos_id }}"
              {{ old('pos_pos_id', $payment->pos_pos_id ?? '') == $pos->pos_id ? 'selected' : '' }}>
              {{ $pos->pos_name }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label>Tahun Pelajaran <span class="text-danger">*</span></label>
        <select name="period_period_id" class="form-control" required>
          <option value="">-- Pilih Tahun Pelajaran --</option>
          @foreach($periods as $p)
            <option value="{{ $p->period_id }}"
              {{ old('period_period_id', $payment->period_period_id ?? '') == $p->period_id ? 'selected' : '' }}>
              {{ $p->period_start }}/{{ $p->period_end }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label>Tipe <span class="text-danger">*</span></label>
        <select name="payment_type" class="form-control" required>
          <option value="">-- Pilih Tipe --</option>
          <option value="bulanan" {{ old('payment_type', $payment->payment_type ?? '') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
          <option value="bebas" {{ old('payment_type', $payment->payment_type ?? '') == 'bebas' ? 'selected' : '' }}>Bebas</option>
        </select>
      </div>
      <div class="box-footer">
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
        <a href="{{ route('payment.index') }}" class="btn btn-default">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection
