@extends('layouts.app')
@section('content')
<div class="miba-card" style="max-width:500px">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-edit"></i> Edit Profil</div>
    <a href="{{ route('profile.index') }}" class="btn-miba btn-miba-sm btn-ghost-miba"><i class="fa fa-arrow-left"></i> Kembali</a>
  </div>
  <div class="miba-card-body">
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">@csrf
      <div style="text-align:center;margin-bottom:16px">
        @if(session('user_image'))
          <img src="{{ asset('uploads/users/'.session('user_image')) }}" style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin-bottom:8px">
        @else
          <div style="width:80px;height:80px;border-radius:50%;background:var(--primary-xlight);display:flex;align-items:center;justify-content:center;margin:0 auto 8px;color:var(--primary);font-size:28px"><i class="fa fa-user"></i></div>
        @endif
      </div>
      <div class="miba-form-group"><label class="miba-label">Nama Lengkap</label><input type="text" name="user_full_name" class="miba-input" value="{{ $user->user_full_name }}"></div>
      <div class="miba-form-group"><label class="miba-label">Email</label><input type="email" name="user_email" class="miba-input" value="{{ $user->user_email }}"></div>
      <div class="miba-form-group"><label class="miba-label">Deskripsi</label><input type="text" name="user_description" class="miba-input" value="{{ $user->user_description }}"></div>
      <div class="miba-form-group"><label class="miba-label">Foto Profil</label><input type="file" name="user_image" class="miba-input" accept="image/*"></div>
      <div style="display:flex;gap:8px">
        <button class="btn-miba btn-primary-miba"><i class="fa fa-save"></i> Simpan</button>
        <a href="{{ route('profile.index') }}" class="btn-miba btn-ghost-miba">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection