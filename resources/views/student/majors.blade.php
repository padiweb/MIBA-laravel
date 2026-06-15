@extends('layouts.app')
@section('content')
<div style="display:grid;grid-template-columns:300px 1fr;gap:16px;align-items:start">
  <div class="miba-card">
    <div class="miba-card-header"><div class="miba-card-title"><i class="fa fa-plus"></i> Tambah Unit Pendidikan</div></div>
    <div class="miba-card-body">
      <form method="POST" action="{{ route('student.majors.store') }}">@csrf
        <div class="miba-form-group"><label class="miba-label">Nama Unit <span class="req">*</span></label><input type="text" name="majors_name" class="miba-input" required placeholder="Contoh: IPA"></div>
        <div class="miba-form-group"><label class="miba-label">Singkatan</label><input type="text" name="majors_short_name" class="miba-input" placeholder="Contoh: IPA"></div>
        <button class="btn-miba btn-primary-miba" style="width:100%;justify-content:center"><i class="fa fa-save"></i> Simpan</button>
      </form>
    </div>
  </div>
  <div class="miba-card">
    <div class="miba-card-header"><div class="miba-card-title"><i class="fa fa-university"></i> Daftar Unit Pendidikan</div></div>
    <div class="miba-table-wrap">
      <table class="miba-table">
        <thead><tr><th>No</th><th>Nama Unit</th><th>Singkatan</th><th>Aksi</th></tr></thead>
        <tbody>
          @forelse($majors as $i => $m)
          <tr>
            <td style="color:var(--text-muted)">{{ $i+1 }}</td>
            <td style="font-weight:600">{{ $m->majors_name }}</td>
            <td style="color:var(--text-muted)">{{ $m->majors_short_name ?: '-' }}</td>
            <td>
              <div style="display:flex;gap:4px">
                <button class="btn-miba btn-miba-xs btn-accent-miba" onclick="editMajors({{ $m->majors_id }},'{{ addslashes($m->majors_name) }}','{{ addslashes($m->majors_short_name??'') }}')"><i class="fa fa-edit"></i></button>
                <form method="POST" action="{{ route('student.majors.destroy',$m->majors_id) }}" onsubmit="return confirm('Hapus unit ini?')">@csrf @method('DELETE')
                  <button class="btn-miba btn-miba-xs btn-danger-miba"><i class="fa fa-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="4" style="text-align:center;padding:24px;color:var(--text-muted)">Belum ada unit pendidikan</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="modal fade" id="editMajors">
  <div class="modal-dialog"><div class="modal-content" style="border-radius:12px;overflow:hidden">
    <form method="POST" id="editMajorsForm" action="">@csrf @method('PUT')
      <div class="modal-header" style="border-bottom:1px solid var(--border)"><h4 class="modal-title">Edit Unit Pendidikan</h4><button class="close" data-dismiss="modal">&times;</button></div>
      <div class="modal-body">
        <div class="miba-form-group"><label class="miba-label">Nama Unit</label><input type="text" name="majors_name" id="editMajorsName" class="miba-input" required></div>
        <div class="miba-form-group"><label class="miba-label">Singkatan</label><input type="text" name="majors_short_name" id="editMajorsShort" class="miba-input"></div>
      </div>
      <div class="modal-footer"><button type="submit" class="btn-miba btn-primary-miba">Update</button><button type="button" class="btn-miba btn-ghost-miba" data-dismiss="modal">Batal</button></div>
    </form>
  </div></div>
</div>
@endsection
@push('scripts')
<script>function editMajors(id,name,short){$('#editMajorsForm').attr('action','/manage/student/majors/'+id);$('#editMajorsName').val(name);$('#editMajorsShort').val(short);$('#editMajors').modal('show');}</script>
@endpush