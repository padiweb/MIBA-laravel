@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-md-4">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Tambah Kelas</h3></div>
      <div class="box-body">
        <form method="POST" action="{{ route('student.classes.store') }}">
          @csrf
          <div class="form-group">
            <label>Nama Kelas</label>
            <input type="text" name="class_name" class="form-control" placeholder="Contoh: X IPA 1" required>
          </div>
          <button class="btn btn-primary btn-block"><i class="fa fa-save"></i> Simpan</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Daftar Kelas</h3></div>
      <div class="box-body">
        <table class="table table-bordered table-striped">
          <thead><tr><th>No</th><th>Nama Kelas</th><th>Jumlah Siswa</th><th>Aksi</th></tr></thead>
          <tbody>
            @forelse($classes as $i => $c)
            <tr>
              <td>{{ $i+1 }}</td>
              <td>
                <form method="POST" action="{{ route('student.classes.update', $c->class_id) }}" class="form-inline">
                  @csrf @method('PUT')
                  <input type="text" name="class_name" value="{{ $c->class_name }}"
                         class="form-control form-control-sm" style="width:150px">
                  <button class="btn btn-warning btn-xs ml-1"><i class="fa fa-save"></i></button>
                </form>
              </td>
              <td>{{ $c->students->count() }}</td>
              <td>
                <form method="POST" action="{{ route('student.classes.destroy', $c->class_id) }}"
                      onsubmit="return confirm('Hapus kelas {{ $c->class_name }}?')">
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
@endsection
