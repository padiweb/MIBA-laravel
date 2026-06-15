@extends('layouts.app')
@section('content')
<div class="miba-card" style="max-width:480px">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-edit"></i> Edit Pengeluaran</div>
    <a href="{{ route('kredit.index') }}" class="btn-miba btn-miba-sm btn-ghost-miba"><i class="fa fa-arrow-left"></i> Kembali</a>
  </div>
  <div class="miba-card-body">
    <form method="POST" action="{{ route('kredit.update',$kredit->kredit_id) }}">@csrf @method('PUT')
      <div class="miba-form-group"><label class="miba-label">Tanggal</label><input type="text" name="kredit_date" class="miba-input date-pick" value="{{ $kredit->kredit_date }}" required></div>
      <div class="miba-form-group"><label class="miba-label">Keterangan</label><input type="text" name="kredit_desc" class="miba-input" value="{{ $kredit->kredit_desc }}" required></div>
      <div class="miba-form-group"><label class="miba-label">Nominal (Rp)</label><input type="number" name="kredit_value" class="miba-input" value="{{ $kredit->kredit_value }}" required></div>
      <div style="display:flex;gap:8px">
        <button class="btn-miba btn-primary-miba"><i class="fa fa-save"></i> Simpan</button>
        <a href="{{ route('kredit.index') }}" class="btn-miba btn-ghost-miba">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection