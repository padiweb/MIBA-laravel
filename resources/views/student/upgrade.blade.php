@extends('layouts.app')
@section('content')
<div class="miba-card">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-level-up"></i> Kenaikan Kelas</div>
  </div>
  <div class="miba-card-body">
    <div class="miba-alert miba-alert-info"><i class="fa fa-info-circle"></i> Pilih siswa dan kelas tujuan untuk melakukan kenaikan kelas.</div>
    <form method="POST" action="{{ route('student.upgrade') }}">@csrf
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px">
        <div class="miba-form-group">
          <label class="miba-label">Dari Kelas</label>
          <select name="from_class" class="miba-select" onchange="this.form.submit()">
            <option value="">-- Pilih Kelas Asal --</option>
            @foreach($classes as $c)
              <option value="{{ $c->class_id }}" {{ request('from_class')==$c->class_id?'selected':'' }}>{{ $c->class_name }}</option>
            @endforeach
          </select>
        </div>
        <div class="miba-form-group">
          <label class="miba-label">Ke Kelas</label>
          <select name="to_class" class="miba-select" required>
            <option value="">-- Pilih Kelas Tujuan --</option>
            @foreach($classes as $c)
              <option value="{{ $c->class_id }}" {{ request('to_class')==$c->class_id?'selected':'' }}>{{ $c->class_name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      @if(isset($students) && $students->count())
      <div class="miba-table-wrap" style="margin-bottom:16px">
        <table class="miba-table">
          <thead><tr><th><input type="checkbox" id="selectall"></th><th>NIS</th><th>Nama</th><th>Kelas Sekarang</th></tr></thead>
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
      <button class="btn-miba btn-primary-miba"><i class="fa fa-level-up"></i> Proses Kenaikan Kelas</button>
      @endif
    </form>
  </div>
</div>
@endsection
@push('scripts')
<script>document.getElementById('selectall')?.addEventListener('change',function(){document.querySelectorAll('.checkbox').forEach(cb=>cb.checked=this.checked);});</script>
@endpush