@extends('layouts.app')
@section('content')
<div class="box box-success">
  <div class="box-header with-border"><h3 class="box-title">Import Data Siswa</h3></div>
  <div class="box-body table-responsive">
    @if(session('failed'))
      <div class="alert alert-danger">{{ session('failed') }}</div>
    @endif
    <h4>Petunjuk Singkat</h4>
    <p>
      Penginputan data Siswa bisa dilakukan dengan mengcopy data dari file Ms. Excel.
      Format file excel harus sesuai kebutuhan aplikasi. Silahkan download formatnya
      <a href="{{ route('student.downloadTemplate') }}"><span class="label label-success">Disini</span></a>
      <br><br>
      <strong>CATATAN :</strong>
      <ol>
        <li>Pengisian jenis data <strong>TANGGAL</strong> diisi dengan format <strong>YYYY-MM-DD</strong>, contoh <strong>2017-12-21</strong>.<br>
        Cara ubah: blok semua kolom tanggal di Excel, pilih Format Cell &rarr; Date dengan format tahun di depan.</li>
        <li>Kolom <strong>ID Kelas</strong> harus sesuai dengan ID kelas yang sudah ada di menu Akademik &rarr; Kelas.</li>
        @if(($app_level ?? '')=='senior')
        <li>Kolom <strong>ID Unit Pendidikan</strong> harus sesuai dengan ID unit pada menu Akademik &rarr; Unit Pendidikan.</li>
        @endif
      </ol>
    </p>
    <hr>
    <form method="POST" action="{{ route('student.importStore') }}">
      @csrf
      <div class="form-group">
        <textarea placeholder="Copy data yang akan dimasukan dari file excel, dan paste disini" rows="8" class="form-control" name="rows"></textarea>
      </div>
      <button type="submit" class="btn btn-success btn-sm btn-flat"><i class="fa fa-upload"></i> Import Data</button>
      <a href="{{ route('student.index') }}" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-repeat"></i> Kembali</a>
    </form>
  </div>
</div>
@endsection
