@extends('portal.layout')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-success">
      <div class="box-body">
        <div class="row">
          <div class="col-md-2">
            @if($student->student_img)
              <img src="{{ asset('uploads/student/'.$student->student_img) }}" class="img-responsive avatar img-thumbnail">
            @else
              <img src="{{ asset('media/img/user.png') }}" class="img-responsive avatar img-thumbnail">
            @endif
          </div>
          <div class="col-md-10">
            <table class="table table-hover">
              <tbody>
                <tr><td>Nomor Induk Siswa (NIS)</td><td>:</td><td>{{ $student->student_nis }}</td></tr>
                <tr><td>NISN</td><td>:</td><td>{{ $student->student_nisn }}</td></tr>
                <tr><td>Nama lengkap</td><td>:</td><td>{{ $student->student_full_name }}</td></tr>
                <tr><td>Jenis Kelamin</td><td>:</td><td>{{ $student->student_gender=='L'?'Laki-laki':'Perempuan' }}</td></tr>
                <tr><td>Tempat, Tanggal Lahir</td><td>:</td><td>{{ $student->student_born_place }}, {{ $student->student_born_date ? \Carbon\Carbon::parse($student->student_born_date)->locale('id')->isoFormat('D MMMM Y') : '' }}</td></tr>
                <tr><td>NIK</td><td>:</td><td>{{ $student->student_hobby }}</td></tr>
                <tr><td>No. Handphone</td><td>:</td><td>{{ $student->student_phone }}</td></tr>
                <tr><td>Alamat</td><td>:</td><td>{{ $student->student_address }}</td></tr>
                <tr><td>Nama Ibu Kandung</td><td>:</td><td>{{ $student->student_name_of_mother }}</td></tr>
                <tr><td>Nama Ayah Kandung</td><td>:</td><td>{{ $student->student_name_of_father }}</td></tr>
                <tr><td>No. Handphone Orang Tua</td><td>:</td><td>{{ $student->student_parent_phone }}</td></tr>
                <tr><td>Kelas</td><td>:</td><td>{{ $student->class->class_name ?? '-' }}</td></tr>
                @if(($app_level ?? '')=='senior')
                <tr><td>Unit Sekolah</td><td>:</td><td>{{ $student->majors->majors_name ?? '-' }}</td></tr>
                @endif
              </tbody>
            </table>
          </div>
          <div class="col-md-4">
            <a href="{{ route('portal.dashboard') }}" class="btn btn-default"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
            <a href="{{ route('portal.profile.edit') }}" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
            <a href="{{ route('portal.profile.cpw') }}" class="btn btn-warning"><i class="fa fa-refresh"></i> Ganti Password</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
