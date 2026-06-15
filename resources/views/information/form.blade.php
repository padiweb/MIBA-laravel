@extends('layouts.app')
@section('content')
<div class="miba-card">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-newspaper-o"></i> {{ isset($information)?'Edit Informasi':'Buat Informasi' }}</div>
    <a href="{{ route('information.index') }}" class="btn-miba btn-miba-sm btn-ghost-miba"><i class="fa fa-arrow-left"></i> Kembali</a>
  </div>
  <div class="miba-card-body">
    <form method="POST" action="{{ isset($information)?route('information.update',$information->information_id):route('information.store') }}" enctype="multipart/form-data">
      @csrf @if(isset($information)) @method('PUT') @endif
      <div class="miba-form-group">
        <label class="miba-label">Judul <span class="req">*</span></label>
        <input type="text" name="information_title" class="miba-input" value="{{ old('information_title',$information->information_title??'') }}" required>
      </div>
      <div class="miba-form-group">
        <label class="miba-label">Isi Informasi</label>
        <textarea name="information_desc" class="miba-textarea" rows="6">{{ old('information_desc',$information->information_desc??'') }}</textarea>
      </div>
      <div class="miba-form-group">
        <label class="miba-label">Gambar</label>
        @if(isset($information) && $information->information_img)
          <img src="{{ asset('uploads/information/'.$information->information_img) }}" style="height:80px;border-radius:8px;margin-bottom:8px;display:block">
        @endif
        <input type="file" name="information_img" class="miba-input" accept="image/*">
      </div>
      <div class="miba-form-group">
        <label class="miba-label">Status Publikasi</label>
        <select name="information_publish" class="miba-select">
          <option value="1" {{ old('information_publish',$information->information_publish??1)==1?'selected':'' }}>Dipublikasi</option>
          <option value="0" {{ old('information_publish',$information->information_publish??1)==0?'selected':'' }}>Draft</option>
        </select>
      </div>
      <div style="display:flex;gap:8px">
        <button class="btn-miba btn-primary-miba"><i class="fa fa-save"></i> {{ isset($information)?'Update':'Simpan' }}</button>
        <a href="{{ route('information.index') }}" class="btn-miba btn-ghost-miba">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection