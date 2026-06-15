@extends('layouts.app')
@section('content')
<div style="display:grid;grid-template-columns:300px 1fr;gap:16px;align-items:start">
  <div class="miba-card">
    <div class="miba-card-header"><div class="miba-card-title"><i class="fa fa-plus"></i> Tambah Kelas</div></div>
    <div class="miba-card-body">
      <form method="POST" action="{{ route('class.store') }}">@csrf
        <div class="miba-form-group"><label class="miba-label">Nama Kelas <span class="req">*</span></label><input type="text" name="class_name" class="miba-input" required placeholder="Contoh: Kelas 1"></div>
        <button class="btn-miba btn-primary-miba" style="width:100%;justify-content:center"><i class="fa fa-save"></i> Simpan</button>
      </form>
    </div>
  </div>
  <div class="miba-card">
    <div class="miba-card-header"><div class="miba-card-title"><i class="fa fa-th-list"></i> Daftar Kelas</div></div>
    <div class="miba-table-wrap">
      <table class="miba-table">
        <thead><tr><th>No</th><th>Nama Kelas</th><th>Aksi</th></tr></thead>
        <tbody>
          @forelse($classes as $i => $c)
          <tr>
            <td style="color:var(--text-muted)">{{ $i+1 }}</td>
            <td style="font-weight:600">{{ $c->class_name }}</td>
            <td>
              <div style="display:flex;gap:4px">
                <button class="btn-miba btn-miba-xs btn-accent-miba" onclick="editClass({{ $c->class_id }},'{{ addslashes($c->class_name) }}')"><i class="fa fa-edit"></i></button>
                <form method="POST" action="{{ route('class.destroy',$c->class_id) }}" onsubmit="return confirm('Hapus kelas ini?')">@csrf @method('DELETE')
                  <button class="btn-miba btn-miba-xs btn-danger-miba"><i class="fa fa-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="3" style="text-align:center;padding:24px;color:var(--text-muted)">Belum ada kelas</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="modal fade" id="editClass">
  <div class="modal-dialog"><div class="modal-content" style="border-radius:12px;overflow:hidden">
    <form method="POST" id="editClassForm" action="">@csrf @method('PUT')
      <div class="modal-header" style="border-bottom:1px solid var(--border)"><h4 class="modal-title">Edit Kelas</h4><button class="close" data-dismiss="modal">&times;</button></div>
      <div class="modal-body"><div class="miba-form-group"><label class="miba-label">Nama Kelas</label><input type="text" name="class_name" id="editClassName" class="miba-input" required></div></div>
      <div class="modal-footer"><button type="submit" class="btn-miba btn-primary-miba">Update</button><button type="button" class="btn-miba btn-ghost-miba" data-dismiss="modal">Batal</button></div>
    </form>
  </div></div>
</div>
@endsection
@push('scripts')
<script>
function editClass(id,name){$('#editClassForm').attr('action','/manage/class/'+id);$('#editClassName').val(name);$('#editClass').modal('show');}
</script>
@endpush