@extends('portal.layout')
@section('content')

<div class="p-card">
  <div class="p-card-header">
    <div class="p-card-title"><i class="fa fa-lock"></i> Ganti Password</div>
  </div>
  <div class="p-card-body">
    <form method="POST" action="{{ route('portal.profile.cpw.update') }}">
      @csrf

      <div class="p-form-group">
        <label class="p-label">Password Lama <span class="req">*</span></label>
        <div class="p-input-icon">
          <i class="fa fa-lock"></i>
          <input type="password" name="student_current_password" class="p-input" required placeholder="••••••••">
        </div>
      </div>

      <div class="p-form-group">
        <label class="p-label">Password Baru <span class="req">*</span></label>
        <div class="p-input-icon">
          <i class="fa fa-key"></i>
          <input type="password" name="student_password" class="p-input" required minlength="6" placeholder="Min. 6 karakter">
        </div>
      </div>

      <div class="p-form-group" style="margin-bottom:0">
        <label class="p-label">Konfirmasi Password Baru <span class="req">*</span></label>
        <div class="p-input-icon">
          <i class="fa fa-check"></i>
          <input type="password" name="passconf" class="p-input" required placeholder="Ulangi password baru">
        </div>
      </div>

      <div class="p-form-footer" style="margin-top:var(--p-space-5)">
        <button type="submit" class="p-btn p-btn-primary p-btn-block">
          <i class="fa fa-save"></i> Simpan Password
        </button>
      </div>
      <div style="margin-top:var(--p-space-2)">
        <a href="{{ route('portal.profile') }}" class="p-btn p-btn-secondary p-btn-block">Batal</a>
      </div>
    </form>
  </div>
</div>

<div style="background:var(--p-gray-50);border-radius:var(--p-radius);padding:var(--p-space-4);font-size:var(--p-text-sm);color:var(--p-text-muted)">
  <div style="font-weight:600;margin-bottom:6px;color:var(--p-text-secondary)"><i class="fa fa-info-circle"></i> Tips Keamanan</div>
  <ul style="padding-left:16px;margin:0;line-height:1.8">
    <li>Password minimal 6 karakter</li>
    <li>Gunakan kombinasi huruf dan angka</li>
    <li>Jangan gunakan tanggal lahir sebagai password</li>
  </ul>
</div>

@endsection
