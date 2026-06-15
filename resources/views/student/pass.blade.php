@extends('layouts.app')
@section('content')
<div class="miba-card">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-graduation-cap"></i> Kelulusan Siswa</div>
  </div>
  <div class="miba-card-body">
    <div class="miba-alert miba-alert-warning"><i class="fa fa-warning"></i> Siswa yang diluluskan statusnya akan diubah menjadi tidak aktif.</div>
    <form method="POST" action="{{ route('student.pass') }}">@csrf
      <div class="miba-form-group" style="max-width:300px">
        <label class="miba-label">Filter Kelas</label>
        <select name="class_id" class="miba-select" onchange="this.form.submit()">
          <option value="">-- Pilih Kelas --</option>
          @foreach($classes as $c)
            <option value="{{ $c->class_id }}" {{ request('class_id')==$c->class_id?'selected':'' }}>{{ $c->class_name }}</option>
          @endforeach
        </select>
      </div>
      @if(isset($students) && $students->count())
      <div class="miba-table-wrap" style="margin-bottom:16px">
        <table class="miba-table">
          <thead><tr><th><input type="checkbox" id="selectall"></th><th>NIS</th><th>Nama</th><th>Kelas</th></tr></thead>
          <tbody>
            @foreach($students as $s)
            <tr>
              <td><input type="checkbox" name="student_ids[]" value="{{ $s->student_id }}" class="checkbox" checked></td>
              <td>{{ $s->student_nis }}</td>
              <td>{{ $s->student_full_name }}</td>
              <td>{{ $s->class->class_name??'-' }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <button class="btn-miba btn-danger-miba" onclick="return confirm('Yakin luluskan semua siswa terpilih?')"><i class="fa fa-graduation-cap"></i> Proses Kelulusan</button>
      @endif
    </form>
  </div>
</div>
@endsection
@push('scripts')
<script>document.getElementById('selectall')?.addEventListener('change',function(){document.querySelectorAll('.checkbox').forEach(cb=>cb.checked=this.checked);});</script>
@endpush