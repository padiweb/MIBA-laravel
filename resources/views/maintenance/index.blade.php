@extends('layouts.app')
@section('content')
<div class="miba-card" style="max-width:500px">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-database"></i> Backup Database</div>
  </div>
  <div class="miba-card-body">
    <div class="miba-alert miba-alert-info">
      <i class="fa fa-info-circle"></i>
      Backup akan mengunduh semua data dalam format file SQL yang dikompres (ZIP).
    </div>
    <div style="background:var(--bg);border-radius:var(--radius-sm);padding:16px;margin-bottom:16px">
      <div style="font-size:13px;font-weight:600;margin-bottom:8px">Informasi Database</div>
      <div style="font-size:12px;color:var(--text-muted)">Database: <strong>{{ config('database.connections.mysql.database') }}</strong></div>
      <div style="font-size:12px;color:var(--text-muted)">Host: <strong>{{ config('database.connections.mysql.host') }}</strong></div>
    </div>
    <a href="{{ route('maintenance.backup') }}" class="btn-miba btn-primary-miba" style="width:100%;justify-content:center">
      <i class="fa fa-download"></i> Download Backup Sekarang
    </a>
  </div>
</div>
@endsection
