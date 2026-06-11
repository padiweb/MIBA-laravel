@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-md-4">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Tambah Tahun Pelajaran</h3></div>
      <div class="box-body">
        <form method="POST" action="{{ route('period.store') }}">
          @csrf
          <div class="form-group">
            <label>Tahun Mulai</label>
            <input type="text" name="period_start" class="form-control years" placeholder="2025" required>
          </div>
          <div class="form-group">
            <label>Tahun Selesai</label>
            <input type="text" name="period_end" class="form-control years" placeholder="2026" required>
          </div>
          <button class="btn btn-primary btn-block"><i class="fa fa-save"></i> Simpan</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Daftar Tahun Pelajaran</h3></div>
      <div class="box-body">
        <table class="table table-bordered table-striped">
          <thead><tr><th>No</th><th>Tahun Pelajaran</th><th>Status</th><th>Aksi</th></tr></thead>
          <tbody>
            @forelse($periods as $i => $p)
            <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ $p->period_start }}/{{ $p->period_end }}</td>
              <td>
                @if($p->period_status)
                  <span class="label label-success">Aktif</span>
                @else
                  <a href="{{ route('period.active', $p->period_id) }}" class="btn btn-xs btn-default"
                     onclick="return confirm('Set tahun pelajaran ini sebagai aktif?')">
                    Set Aktif
                  </a>
                @endif
              </td>
              <td>
                <button class="btn btn-warning btn-xs" onclick="editPeriod({{ $p->period_id }}, {{ $p->period_start }}, {{ $p->period_end }})">
                  <i class="fa fa-edit"></i>
                </button>
                <form action="{{ route('period.destroy', $p->period_id) }}" method="POST" style="display:inline"
                      onsubmit="return confirm('Hapus tahun pelajaran ini?')">
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
<div class="modal fade" id="editModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" id="editForm">
        @csrf @method('PUT')
        <div class="modal-header"><h4 class="modal-title">Edit Tahun Pelajaran</h4></div>
        <div class="modal-body">
          <div class="form-group">
            <label>Tahun Mulai</label>
            <input type="text" name="period_start" id="edit_start" class="form-control years" required>
          </div>
          <div class="form-group">
            <label>Tahun Selesai</label>
            <input type="text" name="period_end" id="edit_end" class="form-control years" required>
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
function editPeriod(id, start, end) {
  $('#edit_start').val(start);
  $('#edit_end').val(end);
  $('#editForm').attr('action', '/manage/period/' + id);
  $('#editModal').modal('show');
}
</script>
@endpush
@endsection
