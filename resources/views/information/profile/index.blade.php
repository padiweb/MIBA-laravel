@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Edit Profil</h3></div>
      <div class="box-body">
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
          @csrf
          <div class="text-center" style="margin-bottom:15px">
            @if(session('user_image'))
              <img src="{{ asset('uploads/users/'.session('user_image')) }}" class="img-circle" style="width:80px;height:80px;object-fit:cover">
            @else
              <img src="{{ asset('media/img/user.png') }}" class="img-circle" style="width:80px">
            @endif
          </div>
          <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="user_full_name" class="form-control" value="{{ $user->user_full_name }}" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="user_email" class="form-control" value="{{ $user->user_email }}" required>
          </div>
          <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="user_description" class="form-control" rows="3">{{ $user->user_description }}</textarea>
          </div>
          <div class="form-group">
            <label>Foto Profil</label>
            <input type="file" name="user_image" class="form-control" accept="image/*">
          </div>
          <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-save"></i> Simpan</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="box box-warning">
      <div class="box-header with-border"><h3 class="box-title">Ganti Password</h3></div>
      <div class="box-body">
        <form method="POST" action="{{ route('profile.password') }}">
          @csrf
          <div class="form-group">
            <label>Password Lama</label>
            <input type="password" name="old_password" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Password Baru</label>
            <input type="password" name="new_password" class="form-control" required minlength="6">
          </div>
          <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" name="confirm_password" class="form-control" required minlength="6">
          </div>
          <button type="submit" class="btn btn-warning btn-block"><i class="fa fa-lock"></i> Ganti Password</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
