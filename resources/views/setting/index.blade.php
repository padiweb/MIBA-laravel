@extends('layouts.app')
@section('content')
<div class="miba-card" style="max-width:640px">
  <div class="miba-card-header"><div class="miba-card-title"><i class="fa fa-building"></i> Profil Yayasan / Sekolah</div></div>
  <div class="miba-card-body">
    <form method="POST" action="{{ route('setting.update') }}" enctype="multipart/form-data">@csrf
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
        <div class="miba-form-group" style="grid-column:1/3">
          <label class="miba-label">Nama Sekolah / Yayasan</label>
          <input type="text" name="school" class="miba-input" value="{{ $settings[1]->setting_value??'' }}">
        </div>
        <div class="miba-form-group" style="grid-column:1/3">
          <label class="miba-label">Alamat</label>
          <textarea name="address" class="miba-textarea" rows="2">{{ $settings[2]->setting_value??'' }}</textarea>
        </div>
        <div class="miba-form-group">
          <label class="miba-label">No. Telepon</label>
          <input type="text" name="phone" class="miba-input" value="{{ $settings[3]->setting_value??'' }}">
        </div>
        <div class="miba-form-group">
          <label class="miba-label">Kecamatan / Distrik</label>
          <input type="text" name="district" class="miba-input" value="{{ $settings[4]->setting_value??'' }}">
        </div>
        <div class="miba-form-group">
          <label class="miba-label">Kota / Kabupaten</label>
          <input type="text" name="city" class="miba-input" value="{{ $settings[5]->setting_value??'' }}">
        </div>
        <div class="miba-form-group">
          <label class="miba-label">Level Aplikasi</label>
          <select name="level" class="miba-select">
            <option value="primary" {{ ($settings[7]->setting_value??'')=='primary'?'selected':'' }}>Primary (SD/MI)</option>
            <option value="junior"  {{ ($settings[7]->setting_value??'')=='junior'?'selected':'' }}>Junior (SMP/MTs)</option>
            <option value="senior"  {{ ($settings[7]->setting_value??'')=='senior'?'selected':'' }}>Senior (SMA/SMK/MA)</option>
          </select>
        </div>
        <div class="miba-form-group" style="grid-column:1/3">
          <label class="miba-label">Logo</label>
          @if(($settings[6]->setting_value??'') && file_exists(public_path('uploads/school/'.($settings[6]->setting_value??''))))
            <img src="{{ asset('uploads/school/'.$settings[6]->setting_value) }}" style="height:60px;border-radius:8px;margin-bottom:8px;display:block">
          @endif
          <input type="file" name="logo" class="miba-input" accept="image/*">
        </div>
      </div>
      <button class="btn-miba btn-primary-miba"><i class="fa fa-save"></i> Simpan Pengaturan</button>
    </form>
  </div>
</div>
@endsection