@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-md-4">
    <div class="box box-success">
      <div class="box-body box-profile">
        @if($user->user_image)
          <img src="{{ asset('uploads/users/'.$user->user_image) }}" class="profile-user-img img-responsive img-circle">
        @else
          <img src="{{ asset('media/img/user.png') }}" class="profile-user-img img-responsive img-circle">
        @endif
        <h3 class="profile-username text-center">{{ $user->user_full_name }}</h3>
        <p class="text-muted text-center">{{ $user->role->role_name ?? '-' }}</p>
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item"><b>Status</b> <span class="pull-right">Aktif</span></li>
          <li class="list-group-item"><b>Email</b> <span class="pull-right">{{ $user->user_email }}</span></li>
        </ul>
        <br>
        @if((session('user_id') ?? null) != $user->user_id)
          <a href="{{ route('users.resetPasswordForm', $user->user_id) }}" class="btn btn-warning btn-block"><i class="fa fa-key"></i> Reset Password</a>
          <form action="{{ route('users.destroy', $user->user_id) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-block"><i class="fa fa-trash"></i> Hapus Pengguna</button>
          </form>
        @endif
        <a href="{{ route('users.index') }}" class="btn btn-default btn-block"><i class="fa fa-arrow-left"></i> Kembali</a>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="box box-success">
      <div class="box-header with-border"><h3 class="box-title">Detail Pengguna</h3></div>
      <div class="box-body">
        <table class="table table-bordered">
          <tr><td width="200">Nama Lengkap</td><td>{{ $user->user_full_name }}</td></tr>
          <tr><td>Email</td><td>{{ $user->user_email }}</td></tr>
          <tr><td>Role</td><td>{{ $user->role->role_name ?? '-' }}</td></tr>
          <tr><td>Deskripsi</td><td>{{ $user->user_description }}</td></tr>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
