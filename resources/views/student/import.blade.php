@extends('layouts.app')
@section('content')
<div class="miba-card" style="max-width:700px">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-upload"></i> Import Data Siswa</div>
    <a href="{{ route('student.index') }}" class="btn-miba btn-miba-sm btn-ghost-miba"><i class="fa fa-arrow-left"></i> Kembali</a>
  </div>
  <div class="miba-card-body">
    <div class="miba-alert miba-alert-info">
      <i class="fa fa-info-circle"></i>
      <div>
        <strong>Petunjuk:</strong> Copy data dari Ms. Excel dan paste ke kotak di bawah.<br>
        Download template: <a href="{{ route('student.downloadTemplate') }}" style="color:var(--primary);font-weight:600">Template Excel Data Siswa</a><br>
        <small>Format tanggal: YYYY-MM-DD. Kolom ID Kelas sesuai dengan ID di menu Kelas.</small>
      </div>
    </div>
    <form method="POST" action="{{ route('student.importStore') }}">@csrf
      <div class="miba-form-group">
        <label class="miba-label">Paste Data dari Excel</label>
        <textarea name="rows" class="miba-textarea" rows="10" placeholder="Copy data dari Excel dan paste di sini..."></textarea>
      </div>
      <div style="display:flex;gap:8px">
        <button class="btn-miba btn-primary-miba"><i class="fa fa-upload"></i> Import Data</button>
        <a href="{{ route('student.index') }}" class="btn-miba btn-ghost-miba">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection