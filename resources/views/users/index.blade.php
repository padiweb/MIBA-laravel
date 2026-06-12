@extends('layouts.app')
@section('content')
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Manajemen Pengguna</h3>
    <div class="box-tools pull-right">
      <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
        <i class="fa fa-plus"></i> Tambah
      </button>
      <a href="{{ route('users.roles') }}" class="btn btn-default btn-sm">
        <i class="fa fa-tags"></i> Role
      </a>
    </div>
  </div>
  <div class="box-body">
    <table class="table table-bordered table-striped">
      <thead><tr><th>No</th><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr></thead>
      <tbody>
        @forelse($users as $i => $u)
        <tr>
          <td>{{ $users->firstItem() + $i }}</td>
          <td>
            @if($u->user_image)
              <img src="{{ asset('uploads/users/'.$u->user_image) }}" class="img-circle" style="width:30px;height:30px;object-fit:cover">
            @endif
            {{ $u->user_full_name }}
          </td>
          <td>{{ $u->user_email }}</td>
          <td><span class="label label-info">{{ $u->role->role_name ?? '-' }}</span></td>
          <td>
            <a href="{{ route('users.show', $u->user_id) }}" class="btn btn-info btn-xs" title="Detail">
              <i class="fa fa-eye"></i>
            </a>
            <button class="btn btn-warning btn-xs" onclick="editUser({{ $u->user_id }}, '{{ $u->user_full_name }}', '{{ $u->user_email }}', {{ $u->user_role_role_id }})">
              <i class="fa fa-edit"></i>
            </button>
            <a href="{{ route('users.resetPasswordForm', $u->user_id) }}" class="btn btn-primary btn-xs" title="Reset Password">
              <i class="fa fa-key"></i>
            </a>
            <form action="{{ route('users.destroy', $u->user_id) }}" method="POST" style="display:inline"
                  onsubmit="return confirm('Hapus pengguna ini?')">
              @csrf @method('DELETE')
              <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
        @endforelse
      </tbody>
    </table>
    <div class="text-center">{{ $users->links() }}</div>
  </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="addModal">
  <div class="modal-dialog"><div class="modal-content">
    <form method="POST" action="{{ route('users.store') }}">
      @csrf
      <div class="modal-header"><h4 class="modal-title">Tambah Pengguna</h4></div>
      <div class="modal-body">
        <div class="form-group">
          <label>Nama Lengkap</label>
          <input type="text" name="user_full_name" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="user_email" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="user_password" class="form-control" required minlength="6">
        </div>
        <div class="form-group">
          <label>Role</label>
          <select name="user_role_role_id" class="form-control">
            @foreach($roles as $r)
              <option value="{{ $r->role_id }}">{{ $r->role_name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
      </div>
    </form>
  </div></div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="editModal">
  <div class="modal-dialog"><div class="modal-content">
    <form method="POST" id="editForm">
      @csrf @method('PUT')
      <div class="modal-header"><h4 class="modal-title">Edit Pengguna</h4></div>
      <div class="modal-body">
        <div class="form-group">
          <label>Nama Lengkap</label>
          <input type="text" name="user_full_name" id="edit_name" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="user_email" id="edit_email" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Password Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
          <input type="password" name="user_password" class="form-control" minlength="6">
        </div>
        <div class="form-group">
          <label>Role</label>
          <select name="user_role_role_id" id="edit_role" class="form-control">
            @foreach($roles as $r)
              <option value="{{ $r->role_id }}">{{ $r->role_name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
      </div>
    </form>
  </div></div>
</div>
@push('scripts')
<script>
function editUser(id, name, email, role) {
  $('#edit_name').val(name);
  $('#edit_email').val(email);
  $('#edit_role').val(role);
  $('#editForm').attr('action', '/manage/users/' + id);
  $('#editModal').modal('show');
}
</script>
@endpush
@endsection
