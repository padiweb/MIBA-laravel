@extends('layouts.app')
@section('content')

<div class="miba-card">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-users"></i> Peserta Didik</div>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
      <a href="{{ route('student.importForm') }}" class="btn-miba btn-miba-sm btn-ghost-miba"><i class="fa fa-upload"></i> Import</a>
      <a href="{{ route('student.create') }}" class="btn-miba btn-miba-sm btn-primary-miba"><i class="fa fa-plus"></i> Tambah Siswa</a>
    </div>
  </div>

  <div class="miba-filter-bar">
    <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;width:100%">
      <div class="miba-input-icon" style="flex:1;min-width:160px">
        <i class="fa fa-search icon"></i>
        <input type="text" name="n" class="miba-input" placeholder="Cari nama / NIS..." value="{{ request('n') }}">
      </div>
      <select name="class_id" class="miba-select" style="width:150px">
        <option value="">Semua Kelas</option>
        @foreach($classes as $c)
          <option value="{{ $c->class_id }}" {{ request('class_id')==$c->class_id?'selected':'' }}>{{ $c->class_name }}</option>
        @endforeach
      </select>
      @if(($app_level??'')=='senior')
      <select name="majors_id" class="miba-select" style="width:160px">
        <option value="">Semua Unit</option>
        @foreach($majors as $m)
          <option value="{{ $m->majors_id }}" {{ request('majors_id')==$m->majors_id?'selected':'' }}>{{ $m->majors_name }}</option>
        @endforeach
      </select>
      @endif
      <button class="btn-miba btn-miba-sm btn-primary-miba"><i class="fa fa-search"></i> Cari</button>
      <a href="{{ route('student.index') }}" class="btn-miba btn-miba-sm btn-ghost-miba">Reset</a>
    </form>
  </div>

  <form action="{{ route('student.printCards') }}" method="POST" target="_blank">
    @csrf
    <div style="padding:10px 16px;border-bottom:1px solid var(--border)">
      <button type="submit" class="btn-miba btn-miba-sm btn-ghost-miba"><i class="fa fa-print"></i> Cetak Kartu Terpilih</button>
    </div>
    <div class="miba-table-wrap">
      <table class="miba-table">
        <thead>
          <tr>
            <th style="width:36px"><input type="checkbox" id="selectall"></th>
            <th>No</th><th>NIS</th><th>Nama Lengkap</th><th>Kelas</th>
            @if(($app_level??'')=='senior')<th>Unit</th>@endif
            <th>Status</th><th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($students as $i => $s)
          <tr>
            <td><input type="checkbox" class="checkbox" name="msg[]" value="{{ $s->student_id }}"></td>
            <td style="color:var(--text-muted)">{{ $students->firstItem()+$i }}</td>
            <td style="font-weight:600">{{ $s->student_nis }}</td>
            <td>{{ $s->student_full_name }}</td>
            <td>{{ $s->class->class_name ?? '-' }}</td>
            @if(($app_level??'')=='senior')<td>{{ $s->majors->majors_name ?? '-' }}</td>@endif
            <td>
              <span class="badge-miba {{ $s->student_status ? 'badge-success' : 'badge-muted' }}">
                {{ $s->student_status ? 'Aktif' : 'Nonaktif' }}
              </span>
            </td>
            <td>
              <div style="display:flex;gap:4px;flex-wrap:wrap">
                <a href="{{ route('student.show',$s->student_id) }}" class="btn-miba btn-miba-xs btn-ghost-miba" title="Detail"><i class="fa fa-eye"></i></a>
                <a href="{{ route('student.edit',$s->student_id) }}" class="btn-miba btn-miba-xs btn-accent-miba" title="Edit"><i class="fa fa-edit"></i></a>
                <a href="{{ route('student.resetPasswordForm',$s->student_id) }}" class="btn-miba btn-miba-xs btn-outline-miba" title="Reset PW"><i class="fa fa-key"></i></a>
                <a href="{{ route('student.printPdf',$s->student_id) }}" target="_blank" class="btn-miba btn-miba-xs btn-ghost-miba" title="Cetak Kartu"><i class="fa fa-id-card"></i></a>
                <form method="POST" action="{{ route('student.destroy',$s->student_id) }}" style="display:inline" onsubmit="return confirm('Hapus siswa ini?')">
                  @csrf @method('DELETE')
                  <button class="btn-miba btn-miba-xs btn-danger-miba" title="Hapus"><i class="fa fa-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" style="text-align:center;padding:32px;color:var(--text-muted)">
            <i class="fa fa-users" style="font-size:32px;opacity:.3;display:block;margin-bottom:8px"></i>
            Belum ada data siswa
          </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div style="padding:12px 16px">{{ $students->links() }}</div>
  </form>
</div>
@endsection
@push('scripts')
<script>
document.getElementById('selectall')?.addEventListener('change',function(){
  document.querySelectorAll('.checkbox').forEach(cb=>cb.checked=this.checked);
});
</script>
@endpush
