@extends('layouts.app')
@section('content')
<div class="box box-primary">
  <div class="box-header with-border"><h3 class="box-title">Pengaturan Sekolah</h3></div>
  <div class="box-body">
    @if($errors->any())
      <div class="alert alert-danger"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif
    <form method="POST" action="{{ route('setting.update') }}" enctype="multipart/form-data">
      @csrf
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Nama Sekolah</label>
            <input type="text" name="school" class="form-control" value="{{ $settings[1]->setting_value ?? '' }}" required>
          </div>
          <div class="form-group">
            <label>Alamat</label>
            <textarea name="address" class="form-control" rows="2">{{ $settings[2]->setting_value ?? '' }}</textarea>
          </div>
          <div class="form-group">
            <label>No. Telepon</label>
            <input type="text" name="phone" class="form-control" value="{{ $settings[3]->setting_value ?? '' }}">
          </div>
          <div class="form-group">
            <label>Kecamatan</label>
            <input type="text" name="district" class="form-control" value="{{ $settings[4]->setting_value ?? '' }}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>Kota / Kabupaten</label>
            <input type="text" name="city" class="form-control" value="{{ $settings[5]->setting_value ?? '' }}">
          </div>
          <div class="form-group">
            <label>Jenjang</label>
            <input type="text" name="level" class="form-control" value="{{ $settings[7]->setting_value ?? '' }}" placeholder="Contoh: SMK">
          </div>
          <div class="form-group">
            <label>Logo Sekolah</label>
            @if(!empty($settings[6]->setting_value))
              <div class="mb-2">
                <img src="{{ asset('uploads/'.$settings[6]->setting_value) }}"
                     style="height:60px;object-fit:contain;border:1px solid #ddd;padding:5px">
              </div>
            @endif
            <input type="file" name="logo" class="form-control" accept="image/*">
          </div>
        </div>
      </div>
      <div class="box-footer">
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Pengaturan</button>
      </div>
    </form>
  </div>
</div>
@endsection
