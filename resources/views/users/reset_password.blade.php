@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-md-9">
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">Reset Password - {{ $user->user_full_name }}</h3>
      </div>
      <div class="box-body">
        @if($errors->any())
          <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form method="POST" action="{{ route('users.resetPassword', $user->user_id) }}">
          @csrf
          <div class="form-group">
            <label>Password baru*</label>
            <input type="password" name="user_password" class="form-control" placeholder="Password baru">
          </div>
          <div class="form-group">
            <label>Konfirmasi password baru*</label>
            <input type="password" name="passconf" class="form-control" placeholder="Konfirmasi password baru">
          </div>
          <p class="text-muted">*) Kolom wajib diisi.</p>
          <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
          <a href="{{ route('users.index') }}" class="btn btn-default"><i class="fa fa-close"></i> Batal</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
