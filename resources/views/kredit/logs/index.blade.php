@extends('layouts.app')
@section('content')
<div class="box box-primary">
  <div class="box-header with-border"><h3 class="box-title">Log Aktivitas</h3></div>
  <div class="box-body">
    <table class="table table-bordered table-striped">
      <thead><tr><th>No</th><th>Waktu</th><th>Aksi</th><th>Modul</th><th>Info</th><th>Pengguna</th></tr></thead>
      <tbody>
        @forelse($logs as $i => $l)
        <tr>
          <td>{{ $logs->firstItem() + $i }}</td>
          <td>{{ \Carbon\Carbon::parse($l->log_date)->format('d/m/Y H:i') }}</td>
          <td><span class="label label-{{ $l->log_action=='DELETE'?'danger':($l->log_action=='ADD'?'success':'warning') }}">{{ $l->log_action }}</span></td>
          <td>{{ $l->log_module }}</td>
          <td>{{ Str::limit($l->log_info, 60) }}</td>
          <td>{{ $l->user->user_full_name ?? '-' }}</td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center">Tidak ada data</td></tr>
        @endforelse
      </tbody>
    </table>
    <div class="text-center">{{ $logs->links() }}</div>
  </div>
</div>
@endsection
