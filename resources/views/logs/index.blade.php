@extends('layouts.app')
@section('content')
<div class="miba-card">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-history"></i> Log Aktivitas</div>
  </div>
  <div class="miba-table-wrap">
    <table class="miba-table">
      <thead><tr><th>Waktu</th><th>Pengguna</th><th>Aksi</th><th>Modul</th><th>Keterangan</th></tr></thead>
      <tbody>
        @forelse($logs as $log)
        <tr>
          <td style="white-space:nowrap;color:var(--text-muted);font-size:12px">{{ \Carbon\Carbon::parse($log->log_date)->format('d/m/Y H:i') }}</td>
          <td style="font-weight:500">{{ $log->user->user_full_name??'-' }}</td>
          <td>
            @php $c=['ADD'=>'badge-success','DELETE'=>'badge-danger','UPDATE'=>'badge-info','BACKUP'=>'badge-warning','PAY'=>'badge-success'][$log->log_action]??'badge-muted'; @endphp
            <span class="badge-miba {{ $c }}">{{ $log->log_action }}</span>
          </td>
          <td style="font-size:13px">{{ $log->log_module }}</td>
          <td style="font-size:12px;color:var(--text-muted)">{{ $log->log_info }}</td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;padding:32px;color:var(--text-muted)">Belum ada log aktivitas</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="padding:12px 16px">{{ $logs->links() }}</div>
</div>
@endsection