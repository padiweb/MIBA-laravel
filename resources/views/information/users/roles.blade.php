@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-md-4">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Tambah Role</h3></div>
      <div class="box-body">
        <form method="POST" action="{{ route('users.roles.store') }}">
          @csrf
          <div class="form-group">
            <label>Nama Role</label>
            <input type="text" name="role_name" class="form-control" placeholder="Contoh: Admin" required>
          </div>
          <button class="btn btn-primary btn-block"><i class="fa fa-save"></i> Simpan</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Daftar Role</h3>
        <div class="box-tools pull-right">
          <a href="{{ route('users.index') }}" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>
      </div>
      <div class="box-body">
        <table class="table table-bordered table-striped">
          <thead><tr><th>No</th><th>Nama Role</th><th>Aksi</th></tr></thead>
          <tbody>
            @forelse($roles as $i => $r)
            <tr>
              <td>{{ $i+1 }}</td>
              <td>
                <form method="POST" action="{{ route('users.roles.update', $r->role_id) }}" class="form-inline">
                  @csrf @method('PUT')
                  <input type="text" name="role_name" value="{{ $r->role_name }}" class="form-control form-control-sm" style="width:200px">
                  <button class="btn btn-warning btn-xs ml-1"><i class="fa fa-save"></i></button>
                </form>
              </td>
              <td>
                <form action="{{ route('users.roles.destroy', $r->role_id) }}" method="POST" style="display:inline"
                      onsubmit="return confirm('Hapus role ini?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center">Belum ada data</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
