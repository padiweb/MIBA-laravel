@extends('portal.layout')
@section('content')
<div class="row">
  <div class="col-md-9">
    <div class="box box-primary">
      <div class="box-body">
        <form method="POST" action="{{ route('portal.profile.cpw.update') }}">
          @csrf
          <div class="form-group">
            <label>Password lama *</label>
            <input type="password" name="student_current_password" class="form-control" placeholder="Password lama">
          </div>
          <div class="form-group">
            <label>Password baru*</label>
            <input type="password" name="student_password" class="form-control" placeholder="Password baru">
          </div>
          <div class="form-group">
            <label>Konfirmasi password baru*</label>
            <input type="password" name="passconf" class="form-control" placeholder="Konfirmasi password baru">
          </div>
          <p class="text-muted">*) Kolom wajib diisi.</p>
          <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
          <a href="{{ route('portal.profile') }}" class="btn btn-default">Batal</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
