@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Edit Pemasukan</h3></div>
      <div class="box-body">
        <form method="POST" action="{{ route('debit.update', $debit->debit_id) }}">
          @csrf @method('PUT')
          <div class="form-group">
            <label>Tanggal</label>
            <div class="input-group date">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              <input type="text" name="debit_date" class="form-control" value="{{ $debit->debit_date }}" required>
            </div>
          </div>
          <div class="form-group">
            <label>Keterangan</label>
            <input type="text" name="debit_desc" class="form-control" value="{{ $debit->debit_desc }}" required>
          </div>
          <div class="form-group">
            <label>Nominal</label>
            <div class="input-group">
              <span class="input-group-addon">Rp</span>
              <input type="number" name="debit_value" class="form-control" value="{{ $debit->debit_value }}" required min="0">
            </div>
          </div>
          <button class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
          <a href="{{ route('debit.index') }}" class="btn btn-default">Batal</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
