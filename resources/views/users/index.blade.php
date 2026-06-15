@extends('layouts.app')
@section('content')
<div class="miba-card">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-users"></i> Pengguna Aplikasi</div>
    <div style="display:flex;gap:8px">
      <a href="{{ route('users.roles') }}" class="btn-miba btn-miba-sm btn-ghost-miba"><i class="fa fa-shield"></i> Kelola Role</a>
      <a href="#addUser" data-toggle="modal" class="btn-miba btn-miba-sm btn-primary-miba"><i class="fa fa-plus"></i> Tambah Pengguna</a>
    </div>
  </div>
  <div class="miba-filter-bar">
    <form method="GET" style="display:flex;gap:8px;width:100%">
      <div class="miba-input-icon" style="flex:1;max-width:300px">
        <i class="fa fa-search icon"></i>
        <input type="text" name="n" class="miba-input" placeholder="Cari nama / email..." value="{{ request('n') }}">
      </div>
      <button class="btn-miba btn-miba-sm btn-primary-miba"><i class="fa fa-search"></i> Cari</button>
      <a href="{{ route('users.index') }}" class="btn-miba btn-miba-sm btn-ghost-miba">Reset</a>
    </form>
  </div>
  <div class="miba-table-wrap">
    <table class="miba-table">
      <thead><tr><th>No</th><th>Pengguna</th><th>Email</th><th>Role</th><th>Aksi</th></tr></thead>
      <tbody>
        @forelse($users as $i => $u)
        <tr>
          <td style="color:var(--text-muted)">{{ $users->firstItem()+$i }}</td>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              @if($u->user_image)
                <img src="{{ asset('uploads/users/'.$u->user_image) }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover">
              @else
                <div style="width:36px;height:36px;border-radius:50%;background:var(--primary-xlight);display:flex;align-items:center;justify-content:center;color:var(--primary);font-weight:700">{{ strtoupper(substr($u->user_full_name,0,1)) }}</div>
              @endif
              <div>
                <div style="font-weight:600">{{ $u->user_full_name }}</div>
                <div style="font-size:11px;color:var(--text-muted)">{{ $u->user_description }}</div>
              </div>
            </div>
          </td>
          <td style="font-size:13px">{{ $u->user_email }}</td>
          <td><span class="badge-miba badge-info">{{ $u->role->role_name??'-' }}</span></td>
          <td>
            <div style="display:flex;gap:4px">
              <a href="{{ route('users.show',$u->user_id) }}" class="btn-miba btn-miba-xs btn-ghost-miba"><i class="fa fa-eye"></i></a>
              <button class="btn-miba btn-miba-xs btn-accent-miba" onclick="editUser({{ $u->user_id }},'{{ addslashes($u->user_full_name) }}','{{ $u->user_email }}',{{ $u->user_role_role_id }})"><i class="fa fa-edit"></i></button>
              <a href="{{ route('users.resetPasswordForm',$u->user_id) }}" class="btn-miba btn-miba-xs btn-outline-miba"><i class="fa fa-key"></i></a>
              @if(session('user_id')!=$u->user_id)
              <form method="POST" action="{{ route('users.destroy',$u->user_id) }}" style="display:inline" onsubmit="return confirm('Hapus pengguna ini?')">@csrf @method('DELETE')
                <button class="btn-miba btn-miba-xs btn-danger-miba"><i class="fa fa-trash"></i></button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;padding:32px;color:var(--text-muted)">Belum ada pengguna</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="padding:12px 16px">{{ $users->links() }}</div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="addUser">
  <div class="modal-dialog"><div class="modal-content" style="border-radius:12px;overflow:hidden">
    <form method="POST" action="{{ route('users.store') }}">
      @csrf
      <div class="modal-header" style="border-bottom:1px solid var(--border)"><h4 class="modal-title">Tambah Pengguna</h4><button class="close" data-dismiss="modal">&times;</button></div>
      <div class="modal-body">
        <div class="miba-form-group"><label class="miba-label">Nama Lengkap <span class="req">*</span></label><input type="text" name="user_full_name" class="miba-input" required></div>
        <div class="miba-form-group"><label class="miba-label">Email <span class="req">*</span></label><input type="email" name="user_email" class="miba-input" required></div>
        <div class="miba-form-group"><label class="miba-label">Password <span class="req">*</span></label><input type="password" name="user_password" class="miba-input" required minlength="6"></div>
        <div class="miba-form-group"><label class="miba-label">Role</label>
          <select name="user_role_role_id" class="miba-select">
            @foreach($roles as $r)<option value="{{ $r->role_id }}">{{ $r->role_name }}</option>@endforeach
          </select>
        </div>
        <div class="miba-form-group"><label class="miba-label">Deskripsi</label><input type="text" name="user_description" class="miba-input"></div>
      </div>
      <div class="modal-footer"><button type="submit" class="btn-miba btn-primary-miba">Simpan</button><button type="button" class="btn-miba btn-ghost-miba" data-dismiss="modal">Batal</button></div>
    </form>
  </div></div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="editUser">
  <div class="modal-dialog"><div class="modal-content" style="border-radius:12px;overflow:hidden">
    <form method="POST" id="editUserForm" action="">
      @csrf @method('PUT')
      <div class="modal-header" style="border-bottom:1px solid var(--border)"><h4 class="modal-title">Edit Pengguna</h4><button class="close" data-dismiss="modal">&times;</button></div>
      <div class="modal-body">
        <div class="miba-form-group"><label class="miba-label">Nama Lengkap</label><input type="text" name="user_full_name" id="editName" class="miba-input"></div>
        <div class="miba-form-group"><label class="miba-label">Email</label><input type="email" name="user_email" id="editEmail" class="miba-input"></div>
        <div class="miba-form-group"><label class="miba-label">Role</label>
          <select name="user_role_role_id" id="editRole" class="miba-select">
            @foreach($roles as $r)<option value="{{ $r->role_id }}">{{ $r->role_name }}</option>@endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer"><button type="submit" class="btn-miba btn-primary-miba">Update</button><button type="button" class="btn-miba btn-ghost-miba" data-dismiss="modal">Batal</button></div>
    </form>
  </div></div>
</div>
@endsection
@push('scripts')
<script>
function editUser(id,name,email,role){
  $('#editUserForm').attr('action','/manage/users/'+id);
  $('#editName').val(name);$('#editEmail').val(email);$('#editRole').val(role);
  $('#editUser').modal('show');
}
</script>
@endpush