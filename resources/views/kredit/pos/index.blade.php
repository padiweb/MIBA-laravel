@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-md-4">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Tambah Jenis Biaya</h3></div>
      <div class="box-body">
        <form method="POST" action="{{ route('pos.store') }}">
          @csrf
          <div class="form-group">
            <label>Nama Jenis Biaya</label>
            <input type="text" name="pos_name" class="form-control" placeholder="Contoh: SPP" required>
          </div>
          <div class="form-group">
            <label>Keterangan</label>
            <input type="text" name="pos_description" class="form-control" placeholder="Keterangan (opsional)">
          </div>
          <button class="btn btn-primary btn-block"><i class="fa fa-save"></i> Simpan</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Daftar Jenis Biaya</h3>
        <div class="box-tools pull-right">
          <form method="GET" class="form-inline">
            <input type="text" name="n" class="form-control input-sm" placeholder="Cari..." value="{{ request('n') }}">
            <button class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
          </form>
        </div>
      </div>
      <div class="box-body">
        <table class="table table-bordered table-striped">
          <thead><tr><th>No</th><th>Nama</th><th>Keterangan</th><th>Aksi</th></tr></thead>
          <tbody>
            @forelse($poses as $i => $p)
            <tr>
              <td>{{ $poses->firstItem() + $i }}</td>
              <td>{{ $p->pos_name }}</td>
              <td>{{ $p->pos_description ?? '-' }}</td>
              <td>
                <button class="btn btn-warning btn-xs" onclick="editPos({{ $p->pos_id }}, '{{ $p->pos_name }}', '{{ $p->pos_description }}')">
                  <i class="fa fa-edit"></i>
                </button>
                <form action="{{ route('pos.destroy', $p->pos_id) }}" method="POST" style="display:inline"
                      onsubmit="return confirm('Hapus jenis biaya ini?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">Tidak ada data</td></tr>
            @endforelse
          </tbody>
        </table>
        <div class="text-center">{{ $poses->links() }}</div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="editModal">
  <div class="modal-dialog"><div class="modal-content">
    <form method="POST" id="editForm">
      @csrf @method('PUT')
      <div class="modal-header"><h4 class="modal-title">Edit Jenis Biaya</h4></div>
      <div class="modal-body">
        <div class="form-group">
          <label>Nama</label>
          <input type="text" name="pos_name" id="edit_name" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Keterangan</label>
          <input type="text" name="pos_description" id="edit_desc" class="form-control">
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
function editPos(id, name, desc) {
  $('#edit_name').val(name);
  $('#edit_desc').val(desc);
  $('#editForm').attr('action', '/manage/pos/' + id);
  $('#editModal').modal('show');
}
</script>
@endpush
@endsection
