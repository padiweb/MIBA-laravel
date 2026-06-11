@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-md-4">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Tambah Jurusan</h3></div>
      <div class="box-body">
        <form method="POST" action="{{ route('student.majors.store') }}">
          @csrf
          <div class="form-group">
            <label>Nama Jurusan</label>
            <input type="text" name="majors_name" class="form-control" placeholder="Contoh: Teknik Komputer Jaringan" required>
          </div>
          <div class="form-group">
            <label>Singkatan</label>
            <input type="text" name="majors_short_name" class="form-control" placeholder="Contoh: TKJ">
          </div>
          <button class="btn btn-primary btn-block"><i class="fa fa-save"></i> Simpan</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Daftar Jurusan</h3></div>
      <div class="box-body">
        <table class="table table-bordered table-striped">
          <thead><tr><th>No</th><th>Nama Jurusan</th><th>Singkatan</th><th>Aksi</th></tr></thead>
          <tbody>
            @forelse($majors as $i => $m)
            <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ $m->majors_name }}</td>
              <td>{{ $m->majors_short_name }}</td>
              <td>
                <button class="btn btn-warning btn-xs" onclick="editMajors({{ $m->majors_id }}, '{{ $m->majors_name }}', '{{ $m->majors_short_name }}')">
                  <i class="fa fa-edit"></i>
                </button>
                <form method="POST" action="{{ route('student.majors.destroy', $m->majors_id) }}" style="display:inline"
                      onsubmit="return confirm('Hapus jurusan ini?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">Belum ada data</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="editModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" id="editForm">
        @csrf @method('PUT')
        <div class="modal-header"><h4 class="modal-title">Edit Jurusan</h4></div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Jurusan</label>
            <input type="text" name="majors_name" id="edit_name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Singkatan</label>
            <input type="text" name="majors_short_name" id="edit_short" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>
@push('scripts')
<script>
function editMajors(id, name, short) {
  $('#edit_name').val(name);
  $('#edit_short').val(short);
  $('#editForm').attr('action', '/manage/student/jurusan/' + id);
  $('#editModal').modal('show');
}
</script>
@endpush
@endsection
