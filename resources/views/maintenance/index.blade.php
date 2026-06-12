@extends('layouts.app')
@section('content')
<div class="box box-primary">
  <div class="box-header with-border"><h3 class="box-title">Maintenance / Backup Database</h3></div>
  <div class="box-body">
    <p>Klik tombol di bawah untuk membuat dan mengunduh backup database dalam format ZIP (berisi file .sql).</p>
    <a href="{{ route('maintenance.backup') }}" class="btn btn-success">
      <i class="fa fa-download"></i> Backup &amp; Download Database
    </a>
    <div class="alert alert-info" style="margin-top:15px">
      <i class="fa fa-info-circle"></i>
      Proses backup mungkin memerlukan beberapa saat tergantung ukuran database.
      Simpan file backup di tempat yang aman.
    </div>
  </div>
</div>
@endsection
