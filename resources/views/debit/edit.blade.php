@extends('layouts.app')
@section('content')
<div class="miba-card" style="max-width:480px">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-edit"></i> Edit Pemasukan</div>
    <a href="{{ route('debit.index') }}" class="btn-miba btn-miba-sm btn-ghost-miba"><i class="fa fa-arrow-left"></i> Kembali</a>
  </div>
  <div class="miba-card-body">
    <form method="POST" action="{{ route('debit.update',$debit->debit_id) }}">@csrf @method('PUT')
      <div class="miba-form-group"><label class="miba-label">Tanggal</label><input type="text" name="debit_date" class="miba-input date-pick" value="{{ $debit->debit_date }}" required></div>
      <div class="miba-form-group"><label class="miba-label">Keterangan</label><input type="text" name="debit_desc" class="miba-input" value="{{ $debit->debit_desc }}" required></div>
      <div class="miba-form-group"><label class="miba-label">Nominal (Rp)</label><input type="number" name="debit_value" class="miba-input" value="{{ $debit->debit_value }}" required></div>
      <div style="display:flex;gap:8px">
        <button class="btn-miba btn-primary-miba"><i class="fa fa-save"></i> Simpan</button>
        <a href="{{ route('debit.index') }}" class="btn-miba btn-ghost-miba">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection