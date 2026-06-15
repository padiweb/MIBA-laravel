@extends('portal.layout')
@section('content')
<div class="miba-card" style="max-width:440px">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-key"></i> Ganti Password</div>
    <a href="{{ route('portal.profile') }}" class="btn-miba btn-miba-sm btn-ghost-miba"><i class="fa fa-arrow-left"></i> Kembali</a>
  </div>
  <div class="miba-card-body">
    <form method="POST" action="{{ route('portal.profile.cpw.update') }}">@csrf
      <div class="miba-form-group"><label class="miba-label">Password Lama <span class="req">*</span></label><input type="password" name="student_current_password" class="miba-input" required></div>
      <div class="miba-form-group"><label class="miba-label">Password Baru <span class="req">*</span></label><input type="password" name="student_password" class="miba-input" required minlength="6"></div>
      <div class="miba-form-group"><label class="miba-label">Konfirmasi Password Baru <span class="req">*</span></label><input type="password" name="passconf" class="miba-input" required></div>
      <div style="display:flex;gap:8px">
        <button class="btn-miba btn-primary-miba"><i class="fa fa-save"></i> Simpan</button>
        <a href="{{ route('portal.profile') }}" class="btn-miba btn-ghost-miba">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection