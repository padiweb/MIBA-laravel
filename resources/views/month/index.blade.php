@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-md-4">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Tambah Bulan</h3></div>
      <div class="box-body">
        @if($errors->any())
          <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form method="POST" action="{{ route('month.store') }}">
          @csrf
          <div class="form-group">
            <label>Nama Bulan</label>
            <input type="text" name="month_name" class="form-control" placeholder="Contoh: Juli" required>
          </div>
          <button class="btn btn-primary btn-block"><i class="fa fa-save"></i> Simpan</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">Daftar Bulan</h3>
        <div class="box-tools pull-right">
          <form method="GET" class="form-inline">
            <input type="text" name="n" class="form-control input-sm" placeholder="Cari nama bulan..." value="{{ request('n') }}">
            <button class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
          </form>
        </div>
      </div>
      <div class="box-body table-responsive">
        <table class="table table-hover table-striped table-bordered">
          <thead><tr><th>No</th><th>Nama Bulan</th><th>ID</th><th>Aksi</th></tr></thead>
          <tbody>
            @forelse($months as $i => $m)
            <tr>
              <td>{{ $months->firstItem() + $i }}</td>
              <td>{{ $m->month_name }}</td>
              <td>{{ $m->month_id }}</td>
              <td>
                <button class="btn btn-warning btn-xs" onclick="editMonth({{ $m->month_id }}, '{{ $m->month_name }}')">
                  <i class="fa fa-edit"></i> Edit
                </button>
                <form action="{{ route('month.destroy', $m->month_id) }}" method="POST" style="display:inline"
                      onsubmit="return confirm('Hapus bulan {{ $m->month_name }}?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Hapus</button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">Tidak ada data</td></tr>
            @endforelse
          </tbody>
        </table>
        <div class="text-center">{{ $months->links() }}</div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editModal">
  <div class="modal-dialog"><div class="modal-content">
    <form method="POST" id="editForm">
      @csrf @method('PUT')
      <div class="modal-header"><h4 class="modal-title">Sunting Bulan</h4></div>
      <div class="modal-body">
        <div class="form-group">
          <label>Nama Bulan</label>
          <input type="text" name="month_name" id="edit_name" class="form-control" required>
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
function editMonth(id, name) {
  $('#edit_name').val(name);
  $('#editForm').attr('action', '/manage/month/' + id);
  $('#editModal').modal('show');
}
</script>
@endpush
@endsection
