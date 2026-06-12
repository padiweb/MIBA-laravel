@extends('layouts.app')
@section('content')
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Input Pembayaran</h3>
    <div class="box-tools pull-right">
      <a href="{{ route('payout.index') }}" class="btn btn-default btn-sm">
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
    <form method="POST" action="{{ route('payout.store') }}">
      @csrf
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Siswa <span class="text-danger">*</span></label>
            <select name="student_student_id" class="form-control" required>
              <option value="">-- Pilih Siswa --</option>
              @foreach($students as $s)
                <option value="{{ $s->student_id }}" {{ old('student_student_id')==$s->student_id?'selected':'' }}>
                  {{ $s->student_nis }} - {{ $s->student_full_name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Jenis Pembayaran <span class="text-danger">*</span></label>
            <select name="payment_payment_id" class="form-control" required>
              <option value="">-- Pilih Jenis --</option>
              @foreach($payments as $p)
                <option value="{{ $p->payment_id }}" {{ old('payment_payment_id')==$p->payment_id?'selected':'' }}>
                  {{ $p->pos->pos_name ?? '-' }} ({{ $p->period->period_start ?? '-' }}/{{ $p->period->period_end ?? '-' }})
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Bulan <span class="text-danger">*</span></label>
            <select name="month_month_id" class="form-control" required>
              <option value="">-- Pilih Bulan --</option>
              @foreach($months as $m)
                <option value="{{ $m->month_id }}" {{ old('month_month_id')==$m->month_id?'selected':'' }}>
                  {{ $m->month_name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>Nominal <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-addon">Rp</span>
              <input type="number" name="bulan_bill" class="form-control"
                     value="{{ old('bulan_bill') }}" required min="0">
            </div>
          </div>
          <div class="form-group">
            <label>No. Bukti Bayar</label>
            <input type="text" name="bulan_number_pay" class="form-control"
                   value="{{ old('bulan_number_pay') }}" placeholder="Otomatis jika kosong">
          </div>
          <div class="form-group">
            <label>Tanggal Bayar</label>
            <div class="input-group date">
              <input type="text" name="bulan_date_pay" class="form-control"
                     value="{{ old('bulan_date_pay', date('Y-m-d')) }}">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
          </div>
          <div class="form-group">
            <label>Keterangan</label>
            <textarea name="bulan_pay_desc" class="form-control" rows="2">{{ old('bulan_pay_desc') }}</textarea>
          </div>
        </div>
      </div>
      <div class="box-footer">
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan & Cetak</button>
        <a href="{{ route('payout.index') }}" class="btn btn-default">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection
