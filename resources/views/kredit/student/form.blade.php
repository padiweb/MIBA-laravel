@extends('layouts.app')
@section('content')
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">{{ isset($student) ? 'Edit Siswa' : 'Tambah Siswa' }}</h3>
    <div class="box-tools pull-right">
      <a href="{{ route('student.index') }}" class="btn btn-default btn-sm">
        <i class="fa fa-arrow-left"></i> Kembali
      </a>
    </div>
  </div>
  <div class="box-body">
    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form method="POST" action="{{ isset($student) ? route('student.update', $student->student_id) : route('student.store') }}" enctype="multipart/form-data">
      @csrf
      @if(isset($student)) @method('PUT') @endif

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>NIS <span class="text-danger">*</span></label>
            <input type="text" name="student_nis" class="form-control"
                   value="{{ old('student_nis', $student->student_nis ?? '') }}" required>
          </div>
          <div class="form-group">
            <label>NISN</label>
            <input type="text" name="student_nisn" class="form-control"
                   value="{{ old('student_nisn', $student->student_nisn ?? '') }}">
          </div>
          <div class="form-group">
            <label>Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="student_full_name" class="form-control"
                   value="{{ old('student_full_name', $student->student_full_name ?? '') }}" required>
          </div>
          <div class="form-group">
            <label>Jenis Kelamin</label>
            <select name="student_gender" class="form-control">
              <option value="">-- Pilih --</option>
              <option value="L" {{ old('student_gender', $student->student_gender ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
              <option value="P" {{ old('student_gender', $student->student_gender ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
          </div>
          <div class="form-group">
            <label>Tempat Lahir</label>
            <input type="text" name="student_born_place" class="form-control"
                   value="{{ old('student_born_place', $student->student_born_place ?? '') }}">
          </div>
          <div class="form-group">
            <label>Tanggal Lahir</label>
            <div class="input-group date">
              <input type="text" name="student_born_date" class="form-control"
                     value="{{ old('student_born_date', isset($student) ? \Carbon\Carbon::parse($student->student_born_date)->format('Y-m-d') : '') }}">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
          </div>
          <div class="form-group">
            <label>No. HP Siswa</label>
            <input type="text" name="student_phone" class="form-control"
                   value="{{ old('student_phone', $student->student_phone ?? '') }}">
          </div>
          <div class="form-group">
            <label>No. HP Orang Tua</label>
            <input type="text" name="student_parent_phone" class="form-control"
                   value="{{ old('student_parent_phone', $student->student_parent_phone ?? '') }}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>Kelas <span class="text-danger">*</span></label>
            <select name="class_class_id" class="form-control" required>
              <option value="">-- Pilih Kelas --</option>
              @foreach($classes as $c)
                <option value="{{ $c->class_id }}"
                  {{ old('class_class_id', $student->class_class_id ?? '') == $c->class_id ? 'selected' : '' }}>
                  {{ $c->class_name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Jurusan <span class="text-danger">*</span></label>
            <select name="majors_majors_id" class="form-control" required>
              <option value="">-- Pilih Jurusan --</option>
              @foreach($majors as $m)
                <option value="{{ $m->majors_id }}"
                  {{ old('majors_majors_id', $student->majors_majors_id ?? '') == $m->majors_id ? 'selected' : '' }}>
                  {{ $m->majors_name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Nama Ayah</label>
            <input type="text" name="student_name_of_father" class="form-control"
                   value="{{ old('student_name_of_father', $student->student_name_of_father ?? '') }}">
          </div>
          <div class="form-group">
            <label>Nama Ibu</label>
            <input type="text" name="student_name_of_mother" class="form-control"
                   value="{{ old('student_name_of_mother', $student->student_name_of_mother ?? '') }}">
          </div>
          <div class="form-group">
            <label>Hobi</label>
            <input type="text" name="student_hobby" class="form-control"
                   value="{{ old('student_hobby', $student->student_hobby ?? '') }}">
          </div>
          <div class="form-group">
            <label>Alamat</label>
            <textarea name="student_address" class="form-control" rows="3">{{ old('student_address', $student->student_address ?? '') }}</textarea>
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="student_status" class="form-control">
              <option value="1" {{ old('student_status', $student->student_status ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
              <option value="0" {{ old('student_status', $student->student_status ?? 1) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
          </div>
          <div class="form-group">
            <label>Foto</label>
            @if(isset($student) && $student->student_img)
              <div class="mb-2">
                <img src="{{ asset('uploads/students/'.$student->student_img) }}"
                     style="width:80px;height:80px;object-fit:cover;border-radius:4px">
              </div>
            @endif
            <input type="file" name="student_img" class="form-control" accept="image/*">
          </div>
        </div>
      </div>

      <div class="box-footer">
        <button type="submit" class="btn btn-primary">
          <i class="fa fa-save"></i> {{ isset($student) ? 'Update' : 'Simpan' }}
        </button>
        <a href="{{ route('student.index') }}" class="btn btn-default">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection
