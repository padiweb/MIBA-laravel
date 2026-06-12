@extends('portal.layout')
@section('content')
<form method="POST" action="{{ route('portal.profile.update') }}">
  @csrf
  @method('PUT')
  <div class="row">
    <div class="col-md-9">
      <div class="box">
        <div class="box-body">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Data Pribadi</a></li>
              <li><a href="#tab_2" data-toggle="tab">Data Sekolah</a></li>
              <li><a href="#tab_3" data-toggle="tab">Data Keluarga</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="form-group">
                  <label>Nama lengkap</label>
                  <input readonly type="text" class="form-control" value="{{ $student->student_full_name }}">
                </div>
                <div class="form-group">
                  <label>Jenis Kelamin</label>
                  <div class="radio">
                    <label><input type="radio" name="student_gender" value="L" {{ $student->student_gender=='L'?'checked':'' }}> Laki-laki</label>&nbsp;&nbsp;
                    <label><input type="radio" name="student_gender" value="P" {{ $student->student_gender=='P'?'checked':'' }}> Perempuan</label>
                  </div>
                </div>
                <div class="form-group">
                  <label>Tempat Lahir</label>
                  <input name="student_born_place" type="text" class="form-control" value="{{ $student->student_born_place }}" placeholder="Tempat Lahir">
                </div>
                <div class="form-group">
                  <label>Tanggal Lahir</label>
                  <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input class="form-control" type="text" name="student_born_date" value="{{ $student->student_born_date }}" placeholder="YYYY-MM-DD">
                  </div>
                </div>
                <div class="form-group">
                  <label>NIK</label>
                  <input name="student_hobby" type="text" class="form-control" value="{{ $student->student_hobby }}" placeholder="NIK">
                </div>
                <div class="form-group">
                  <label>No. Handphone</label>
                  <input name="student_phone" type="text" class="form-control" value="{{ $student->student_phone }}" placeholder="No Handphone">
                </div>
                <div class="form-group">
                  <label>Alamat</label>
                  <textarea class="form-control" name="student_address" placeholder="Alamat Tempat Tinggal">{{ $student->student_address }}</textarea>
                </div>
              </div>

              <div class="tab-pane" id="tab_2">
                <div class="form-group">
                  <label>NIS</label>
                  <input readonly type="text" class="form-control" value="{{ $student->student_nis }}">
                </div>
                <div class="form-group">
                  <label>NISN</label>
                  <input readonly type="text" class="form-control" value="{{ $student->student_nisn }}">
                </div>
                @if(($app_level ?? '')=='senior')
                <div class="form-group">
                  <label>Unit Sekolah</label>
                  <input readonly type="text" class="form-control" value="{{ $student->majors->majors_name ?? '' }}">
                </div>
                @endif
                <div class="form-group">
                  <label>Kelas</label>
                  <input readonly type="text" class="form-control" value="{{ $student->class->class_name ?? '' }}">
                </div>
              </div>

              <div class="tab-pane" id="tab_3">
                <div class="form-group">
                  <label>Nama Ibu Kandung</label>
                  <input name="student_name_of_mother" type="text" class="form-control" value="{{ $student->student_name_of_mother }}" placeholder="Nama Ibu">
                </div>
                <div class="form-group">
                  <label>Nama Ayah Kandung</label>
                  <input name="student_name_of_father" type="text" class="form-control" value="{{ $student->student_name_of_father }}" placeholder="Nama Ayah">
                </div>
                <div class="form-group">
                  <label>No. Handphone Orang Tua</label>
                  <input name="student_parent_phone" type="text" class="form-control" value="{{ $student->student_parent_phone }}" placeholder="No Handphone Orang Tua">
                </div>
              </div>
            </div>
          </div>
          <p class="text-muted">*) Kolom wajib diisi.</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="box box-primary">
        <div class="box-body">
          <label>Foto</label><br>
          @if($student->student_img)
            <img src="{{ asset('uploads/student/'.$student->student_img) }}" class="img-responsive avatar img-thumbnail">
          @else
            <img src="{{ asset('media/img/missing.png') }}" class="img-responsive">
          @endif
          <br><br>
          <button type="submit" class="btn btn-block btn-success"><i class="fa fa-save"></i> Simpan</button>
          <a href="{{ route('portal.profile') }}" class="btn btn-block btn-info"><i class="fa fa-close"></i> Batal</a>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection
