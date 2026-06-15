@extends('layouts.app')
@section('content')
<div style="display:grid;grid-template-columns:320px 1fr;gap:16px;align-items:start">
  <div class="miba-card">
    <div class="miba-card-header"><div class="miba-card-title"><i class="fa fa-plus"></i> Tambah Tahun Pelajaran</div></div>
    <div class="miba-card-body">
      <form method="POST" action="{{ route('period.store') }}">@csrf
        <div class="miba-form-group"><label class="miba-label">Tahun Mulai <span class="req">*</span></label><input type="text" name="period_start" class="miba-input years" required placeholder="2025"></div>
        <div class="miba-form-group"><label class="miba-label">Tahun Selesai <span class="req">*</span></label><input type="text" name="period_end" class="miba-input years" required placeholder="2026"></div>
        <button class="btn-miba btn-primary-miba" style="width:100%;justify-content:center"><i class="fa fa-save"></i> Simpan</button>
      </form>
    </div>
  </div>
  <div class="miba-card">
    <div class="miba-card-header"><div class="miba-card-title"><i class="fa fa-calendar"></i> Daftar Tahun Pelajaran</div></div>
    <div class="miba-table-wrap">
      <table class="miba-table">
        <thead><tr><th>Tahun Pelajaran</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
          @forelse($periods as $p)
          <tr>
            <td style="font-weight:700;font-size:15px">{{ $p->period_start }}/{{ $p->period_end }}</td>
            <td><span class="badge-miba {{ $p->period_status?'badge-success':'badge-muted' }}">{{ $p->period_status?'Aktif':'Nonaktif' }}</span></td>
            <td>
              <div style="display:flex;gap:4px">
                @if(!$p->period_status)
                <a href="{{ route('period.active',$p->period_id) }}" onclick="return confirm('Jadikan tahun pelajaran ini aktif?')" class="btn-miba btn-miba-xs btn-success-miba"><i class="fa fa-check"></i> Aktifkan</a>
                @endif
                <form method="POST" action="{{ route('period.destroy',$p->period_id) }}" onsubmit="return confirm('Hapus tahun pelajaran ini?')">@csrf @method('DELETE')
                  <button class="btn-miba btn-miba-xs btn-danger-miba"><i class="fa fa-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="3" style="text-align:center;padding:24px;color:var(--text-muted)">Belum ada tahun pelajaran</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection