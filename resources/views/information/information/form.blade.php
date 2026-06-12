@extends('layouts.app')
@section('content')
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">{{ isset($information) ? 'Edit' : 'Tambah' }} Informasi</h3>
    <div class="box-tools pull-right">
      <a href="{{ route('information.index') }}" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
  </div>
  <div class="box-body">
    <form method="POST"
          action="{{ isset($information) ? route('information.update', $information->information_id) : route('information.store') }}"
          enctype="multipart/form-data">
      @csrf
      @if(isset($information)) @method('PUT') @endif
      <div class="form-group">
        <label>Judul</label>
        <input type="text" name="information_title" class="form-control"
               value="{{ old('information_title', $information->information_title ?? '') }}" required>
      </div>
      <div class="form-group">
        <label>Isi</label>
        <textarea name="information_desc" class="form-control" rows="6">{{ old('information_desc', $information->information_desc ?? '') }}</textarea>
      </div>
      <div class="form-group">
        <label>Gambar</label>
        @if(isset($information) && $information->information_img)
          <div class="mb-2">
            <img src="{{ asset('uploads/information/'.$information->information_img) }}" style="height:80px">
          </div>
        @endif
        <input type="file" name="information_img" class="form-control" accept="image/*">
      </div>
      <div class="form-group">
        <label>Status</label>
        <select name="information_publish" class="form-control">
          <option value="1" {{ old('information_publish', $information->information_publish ?? 0) == 1 ? 'selected' : '' }}>Publish</option>
          <option value="0" {{ old('information_publish', $information->information_publish ?? 0) == 0 ? 'selected' : '' }}>Draft</option>
        </select>
      </div>
      <div class="box-footer">
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
        <a href="{{ route('information.index') }}" class="btn btn-default">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection
