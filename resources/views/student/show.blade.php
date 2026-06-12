@extends('layouts.app')
@section('content')
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Detail Siswa</h3>
    <div class="box-tools pull-right">
      <a href="{{ route('student.edit', $student->student_id) }}" class="btn btn-warning btn-sm">
        <i class="fa fa-edit"></i> Edit
      </a>
      <a href="{{ route('student.index') }}" class="btn btn-default btn-sm">
        <i class="fa fa-arrow-left"></i> Kembali
      </a>
    </div>
  </div>
  <div class="box-body">
    <div class="row">
      <div class="col-md-3 text-center">
        @if($student->student_img)
          <img src="{{ asset('uploads/student/'.$student->student_img) }}"
               class="img-circle" style="width:120px;height:120px;object-fit:cover">
        @else
          <img src="{{ asset('media/img/user.png') }}"
               class="img-circle" style="width:120px;height:120px">
        @endif
        <h4>{{ $student->student_full_name }}</h4>
        <span class="label label-{{ $student->student_status ? 'success' : 'default' }}">
          {{ $student->student_status ? 'Aktif' : 'Tidak Aktif' }}
        </span>
      </div>
      <div class="col-md-9">
        <table class="table table-bordered">
          <tr><th style="width:30%">NIS</th><td>{{ $student->student_nis }}</td></tr>
          <tr><th>NISN</th><td>{{ $student->student_nisn ?? '-' }}</td></tr>
          <tr><th>Jenis Kelamin</th><td>{{ $student->student_gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
          <tr><th>Tempat, Tgl Lahir</th>
            <td>{{ $student->student_born_place ?? '-' }}, {{ $student->student_born_date ? \Carbon\Carbon::parse($student->student_born_date)->format('d/m/Y') : '-' }}</td>
          </tr>
          <tr><th>Kelas</th><td>{{ $student->class->class_name ?? '-' }}</td></tr>
          <tr><th>Unit Pendidikan</th><td>{{ $student->majors->majors_name ?? '-' }}</td></tr>
          <tr><th>No. HP</th><td>{{ $student->student_phone ?? '-' }}</td></tr>
          <tr><th>No. HP Orang Tua</th><td>{{ $student->student_parent_phone ?? '-' }}</td></tr>
          <tr><th>Nama Ayah</th><td>{{ $student->student_name_of_father ?? '-' }}</td></tr>
          <tr><th>Nama Ibu</th><td>{{ $student->student_name_of_mother ?? '-' }}</td></tr>
          <tr><th>Hobi</th><td>{{ $student->student_hobby ?? '-' }}</td></tr>
          <tr><th>Alamat</th><td>{{ $student->student_address ?? '-' }}</td></tr>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
