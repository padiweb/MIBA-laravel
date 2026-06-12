@extends('layouts.app')
@section('content')
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Daftar Siswa</h3>
    <div class="box-tools pull-right">
      <a href="{{ route('student.create') }}" class="btn btn-primary btn-sm">
        <i class="fa fa-plus"></i> Tambah Siswa
      </a>
    </div>
  </div>
  <div class="box-body">
    {{-- Filter --}}
    <form method="GET" class="form-inline" style="margin-bottom:15px">
      <input type="text" name="n" class="form-control" placeholder="Cari nama / NIS..."
             value="{{ request('n') }}" style="width:200px">
      <select name="class_id" class="form-control">
        <option value="">Semua Kelas</option>
        @foreach($classes as $class)
          <option value="{{ $class->class_id }}" {{ request('class_id')==$class->class_id?'selected':'' }}>
            {{ $class->class_name }}
          </option>
        @endforeach
      </select>
      <select name="majors_id" class="form-control">
        <option value="">Semua Jurusan</option>
        @foreach($majors as $m)
          <option value="{{ $m->majors_id }}" {{ request('majors_id')==$m->majors_id?'selected':'' }}>
            {{ $m->majors_name }}
          </option>
        @endforeach
      </select>
      <button class="btn btn-default"><i class="fa fa-search"></i> Cari</button>
      <a href="{{ route('student.index') }}" class="btn btn-default">Reset</a>
    </form>

    <table class="table table-bordered table-striped table-hover">
      <thead>
        <tr>
          <th>No</th><th>NIS</th><th>Nama Lengkap</th>
          <th>Kelas</th><th>Jurusan</th><th>Status</th><th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($students as $i => $s)
        <tr>
          <td>{{ $students->firstItem() + $i }}</td>
          <td>{{ $s->student_nis }}</td>
          <td>{{ $s->student_full_name }}</td>
          <td>{{ $s->class->class_name ?? '-' }}</td>
          <td>{{ $s->majors->majors_short_name ?? '-' }}</td>
          <td>
            <span class="label label-{{ $s->student_status ? 'success' : 'default' }}">
              {{ $s->student_status ? 'Aktif' : 'Tidak Aktif' }}
            </span>
          </td>
          <td>
            <a href="{{ route('student.show', $s->student_id) }}" class="btn btn-info btn-xs">
              <i class="fa fa-eye"></i>
            </a>
            <a href="{{ route('student.edit', $s->student_id) }}" class="btn btn-warning btn-xs">
              <i class="fa fa-edit"></i>
            </a>
            <form action="{{ route('student.destroy', $s->student_id) }}" method="POST" style="display:inline"
                  onsubmit="return confirm('Hapus siswa {{ $s->student_full_name }}?')">
              @csrf @method('DELETE')
              <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center">Tidak ada data</td></tr>
        @endforelse
      </tbody>
    </table>
    <div class="text-center">{{ $students->links() }}</div>
  </div>
</div>
@endsection
