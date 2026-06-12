@extends('layouts.app')
@section('content')
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Kenaikan Kelas</h3>
  </div>
  <div class="box-body">
    <form method="GET" class="form-inline" style="margin-bottom:15px">
      <select name="pr" class="form-control">
        <option value="">Semua Kelas</option>
        @foreach($classes as $c)
          <option value="{{ $c->class_id }}" {{ request('pr')==$c->class_id?'selected':'' }}>{{ $c->class_name }}</option>
        @endforeach
      </select>
      <button class="btn btn-default"><i class="fa fa-filter"></i> Filter</button>
      <a href="{{ route('student.upgrade') }}" class="btn btn-default">Reset</a>
    </form>

    <form method="POST" action="{{ route('student.multiple') }}">
      @csrf
      <input type="hidden" name="action" value="upgrade">

      <div class="form-group" style="margin-bottom:15px">
        <label>Naikkan ke Kelas:</label>
        <select name="class_id" class="form-control" style="width:250px;display:inline-block" required>
          <option value="">-- Pilih Kelas Tujuan --</option>
          @foreach($classes as $c)
            <option value="{{ $c->class_id }}">{{ $c->class_name }}</option>
          @endforeach
        </select>
        <button type="submit" class="btn btn-primary" onclick="return confirm('Proses kenaikan kelas untuk siswa terpilih?')">
          <i class="fa fa-arrow-up"></i> Proses Kenaikan Kelas
        </button>
      </div>

      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th><input type="checkbox" id="checkAll"></th>
            <th>No</th><th>NIS</th><th>Nama</th><th>Kelas Sekarang</th><th>Unit Pendidikan</th>
          </tr>
        </thead>
        <tbody>
          @forelse($students as $i => $s)
          <tr>
            <td><input type="checkbox" name="msg[]" value="{{ $s->student_id }}" class="chk"></td>
            <td>{{ $students->firstItem() + $i }}</td>
            <td>{{ $s->student_nis }}</td>
            <td>{{ $s->student_full_name }}</td>
            <td>{{ $s->class->class_name ?? '-' }}</td>
            <td>{{ $s->majors->majors_name ?? '-' }}</td>
          </tr>
          @empty
          <tr><td colspan="6" class="text-center">Tidak ada data</td></tr>
          @endforelse
        </tbody>
      </table>
      <div class="text-center">{{ $students->links() }}</div>
    </form>
  </div>
</div>
@push('scripts')
<script>
$('#checkAll').change(function(){
  $('.chk').prop('checked', $(this).prop('checked'));
});
</script>
@endpush
@endsection
