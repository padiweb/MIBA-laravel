@extends('layouts.app')
@section('content')
<div style="display:grid;grid-template-columns:340px 1fr;gap:16px;align-items:start">
  <div class="miba-card">
    <div class="miba-card-header"><div class="miba-card-title"><i class="fa fa-calendar-plus-o"></i> Tambah Agenda</div></div>
    <div class="miba-card-body">
      <form method="POST" action="{{ route('holiday.store') }}">@csrf
        <div class="miba-form-group"><label class="miba-label">Tanggal <span class="req">*</span></label><input type="text" name="date" class="miba-input date-pick" required value="{{ date('Y-m-d') }}"></div>
        <div class="miba-form-group"><label class="miba-label">Keterangan <span class="req">*</span></label><input type="text" name="info" class="miba-input" required placeholder="Nama hari libur / agenda"></div>
        <button class="btn-miba btn-primary-miba" style="width:100%;justify-content:center"><i class="fa fa-save"></i> Simpan</button>
      </form>
    </div>
  </div>
  <div class="miba-card">
    <div class="miba-card-header"><div class="miba-card-title"><i class="fa fa-calendar"></i> Daftar Hari Libur & Agenda</div></div>
    <div class="miba-table-wrap">
      <table class="miba-table">
        <thead><tr><th>Tanggal</th><th>Hari</th><th>Keterangan</th><th>Aksi</th></tr></thead>
        <tbody>
          @forelse($holidays as $h)
          <tr>
            <td style="white-space:nowrap;font-weight:600">{{ \Carbon\Carbon::parse($h->date)->locale('id')->isoFormat('D MMMM Y') }}</td>
            <td style="color:var(--text-muted)">{{ \Carbon\Carbon::parse($h->date)->locale('id')->isoFormat('dddd') }}</td>
            <td>{{ $h->info }}</td>
            <td>
              <form method="POST" action="{{ route('holiday.destroy',$h->id) }}" onsubmit="return confirm('Hapus agenda ini?')">@csrf @method('DELETE')
                <button class="btn-miba btn-miba-xs btn-danger-miba"><i class="fa fa-trash"></i></button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="4" style="text-align:center;padding:32px;color:var(--text-muted)">Belum ada agenda</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection