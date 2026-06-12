@extends('layouts.app')
@section('content')

<div class="row">
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Siswa Aktif (Proses Kelulusan)</h3></div>
      <div class="box-body">
        <form method="GET" class="form-inline" style="margin-bottom:10px">
          <select name="pr" class="form-control">
            <option value="">Semua Kelas</option>
            @foreach($classes as $c)
              <option value="{{ $c->class_id }}" {{ request('pr')==$c->class_id?'selected':'' }}>{{ $c->class_name }}</option>
            @endforeach
          </select>
          <button class="btn btn-default btn-sm"><i class="fa fa-filter"></i></button>
        </form>

        <form method="POST" action="{{ route('student.multiple') }}">
          @csrf
          <input type="hidden" name="action" value="pass">
          <button type="submit" class="btn btn-success btn-sm" style="margin-bottom:10px"
                  onclick="return confirm('Proses kelulusan untuk siswa terpilih?')">
            <i class="fa fa-graduation-cap"></i> Proses Lulus
          </button>
          <table class="table table-bordered table-striped">
            <thead>
              <tr><th><input type="checkbox" id="checkAllPass"></th><th>NIS</th><th>Nama</th><th>Kelas</th></tr>
            </thead>
            <tbody>
              @forelse($notpass as $s)
              <tr>
                <td><input type="checkbox" name="msg[]" value="{{ $s->student_id }}" class="chkPass"></td>
                <td>{{ $s->student_nis }}</td>
                <td>{{ $s->student_full_name }}</td>
                <td>{{ $s->class->class_name ?? '-' }}</td>
              </tr>
              @empty
              <tr><td colspan="4" class="text-center">Tidak ada data</td></tr>
              @endforelse
            </tbody>
          </table>
          <div class="text-center">{{ $notpass->links() }}</div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="box box-default">
      <div class="box-header with-border"><h3 class="box-title">Siswa Lulus / Tidak Aktif</h3></div>
      <div class="box-body">
        <form method="POST" action="{{ route('student.multiple') }}">
          @csrf
          <input type="hidden" name="action" value="notpass">
          <button type="submit" class="btn btn-warning btn-sm" style="margin-bottom:10px"
                  onclick="return confirm('Kembalikan siswa terpilih menjadi aktif?')">
            <i class="fa fa-undo"></i> Kembalikan Aktif
          </button>
          <table class="table table-bordered table-striped">
            <thead>
              <tr><th><input type="checkbox" id="checkAllNotPass"></th><th>NIS</th><th>Nama</th><th>Kelas</th></tr>
            </thead>
            <tbody>
              @forelse($pass as $s)
              <tr>
                <td><input type="checkbox" name="msg[]" value="{{ $s->student_id }}" class="chkNotPass"></td>
                <td>{{ $s->student_nis }}</td>
                <td>{{ $s->student_full_name }}</td>
                <td>{{ $s->class->class_name ?? '-' }}</td>
              </tr>
              @empty
              <tr><td colspan="4" class="text-center">Tidak ada data</td></tr>
              @endforelse
            </tbody>
          </table>
          <div class="text-center">{{ $pass->links() }}</div>
        </form>
      </div>
    </div>
  </div>
</div>
@push('scripts')
<script>
$('#checkAllPass').change(function(){ $('.chkPass').prop('checked', $(this).prop('checked')); });
$('#checkAllNotPass').change(function(){ $('.chkNotPass').prop('checked', $(this).prop('checked')); });
</script>
@endpush
@endsection
