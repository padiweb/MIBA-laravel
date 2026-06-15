@extends('layouts.app')
@section('content')
<form method="POST" action="{{ isset($student) ? route('student.update',$student->student_id) : route('student.store') }}" enctype="multipart/form-data">
  @csrf @if(isset($student)) @method('PUT') @endif
  <div style="display:grid;grid-template-columns:1fr 320px;gap:16px;align-items:start">
    <div>
      <div class="miba-card">
        <div class="miba-card-header">
          <div class="miba-card-title"><i class="fa fa-user-plus"></i> {{ isset($student)?'Edit Siswa':'Tambah Siswa' }}</div>
          <a href="{{ route('student.index') }}" class="btn-miba btn-miba-sm btn-ghost-miba"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="miba-card-body">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <div class="miba-form-group">
              <label class="miba-label">NIS <span class="req">*</span></label>
              <input type="text" name="student_nis" class="miba-input" value="{{ old('student_nis',$student->student_nis??'') }}" required>
            </div>
            <div class="miba-form-group">
              <label class="miba-label">NISN</label>
              <input type="text" name="student_nisn" class="miba-input" value="{{ old('student_nisn',$student->student_nisn??'') }}">
            </div>
            <div class="miba-form-group" style="grid-column:1/3">
              <label class="miba-label">Nama Lengkap <span class="req">*</span></label>
              <input type="text" name="student_full_name" class="miba-input" value="{{ old('student_full_name',$student->student_full_name??'') }}" required>
            </div>
            <div class="miba-form-group">
              <label class="miba-label">Jenis Kelamin</label>
              <select name="student_gender" class="miba-select">
                <option value="">-- Pilih --</option>
                <option value="L" {{ old('student_gender',$student->student_gender??'')==='L'?'selected':'' }}>Laki-laki</option>
                <option value="P" {{ old('student_gender',$student->student_gender??'')==='P'?'selected':'' }}>Perempuan</option>
              </select>
            </div>
            <div class="miba-form-group">
              <label class="miba-label">Status</label>
              <select name="student_status" class="miba-select">
                <option value="1" {{ old('student_status',$student->student_status??1)==1?'selected':'' }}>Aktif</option>
                <option value="0" {{ old('student_status',$student->student_status??1)==0?'selected':'' }}>Tidak Aktif</option>
              </select>
            </div>
            <div class="miba-form-group">
              <label class="miba-label">Tempat Lahir</label>
              <input type="text" name="student_born_place" class="miba-input" value="{{ old('student_born_place',$student->student_born_place??'') }}">
            </div>
            <div class="miba-form-group">
              <label class="miba-label">Tanggal Lahir</label>
              <input type="text" name="student_born_date" class="miba-input date-pick" value="{{ old('student_born_date',$student->student_born_date??'') }}" placeholder="YYYY-MM-DD">
            </div>
            <div class="miba-form-group">
              <label class="miba-label">No. HP Siswa</label>
              <input type="text" name="student_phone" class="miba-input" value="{{ old('student_phone',$student->student_phone??'') }}">
            </div>
            <div class="miba-form-group">
              <label class="miba-label">NIK</label>
              <input type="text" name="student_hobby" class="miba-input" value="{{ old('student_hobby',$student->student_hobby??'') }}">
            </div>
            <div class="miba-form-group">
              <label class="miba-label">Kelas <span class="req">*</span></label>
              <select name="class_class_id" class="miba-select" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($classes as $c)
                  <option value="{{ $c->class_id }}" {{ old('class_class_id',$student->class_class_id??'')==$c->class_id?'selected':'' }}>{{ $c->class_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="miba-form-group">
              <label class="miba-label">Unit Pendidikan</label>
              <select name="majors_majors_id" class="miba-select">
                <option value="">-- Pilih --</option>
                @foreach($majors as $m)
                  <option value="{{ $m->majors_id }}" {{ old('majors_majors_id',$student->majors_majors_id??'')==$m->majors_id?'selected':'' }}>{{ $m->majors_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="miba-form-group">
              <label class="miba-label">Nama Ayah</label>
              <input type="text" name="student_name_of_father" class="miba-input" value="{{ old('student_name_of_father',$student->student_name_of_father??'') }}">
            </div>
            <div class="miba-form-group">
              <label class="miba-label">Nama Ibu</label>
              <input type="text" name="student_name_of_mother" class="miba-input" value="{{ old('student_name_of_mother',$student->student_name_of_mother??'') }}">
            </div>
            <div class="miba-form-group">
              <label class="miba-label">No. HP Orang Tua</label>
              <input type="text" name="student_parent_phone" class="miba-input" value="{{ old('student_parent_phone',$student->student_parent_phone??'') }}">
            </div>
            <div class="miba-form-group" style="grid-column:1/3">
              <label class="miba-label">Alamat</label>
              <textarea name="student_address" class="miba-textarea" rows="2">{{ old('student_address',$student->student_address??'') }}</textarea>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div>
      <div class="miba-card">
        <div class="miba-card-header"><div class="miba-card-title"><i class="fa fa-camera"></i> Foto</div></div>
        <div class="miba-card-body" style="text-align:center">
          @if(isset($student) && $student->student_img)
            <img src="{{ asset('uploads/student/'.$student->student_img) }}" style="width:100px;height:100px;object-fit:cover;border-radius:50%;margin-bottom:12px;border:3px solid var(--border)">
          @else
            <div style="width:100px;height:100px;border-radius:50%;background:var(--bg);border:2px dashed var(--border-dark);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;color:var(--text-muted)"><i class="fa fa-user" style="font-size:32px"></i></div>
          @endif
          <input type="file" name="student_img" class="miba-input" accept="image/*" style="margin-bottom:12px">
          <button type="submit" class="btn-miba btn-primary-miba" style="width:100%"><i class="fa fa-save"></i> {{ isset($student)?'Update':'Simpan' }}</button>
          <a href="{{ route('student.index') }}" class="btn-miba btn-ghost-miba" style="width:100%;margin-top:8px;justify-content:center">Batal</a>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection
@push('scripts')
<script>
$(window).on('resize load',function(){if($(window).width()<768)$('form > div[style*="grid"]').css({display:'flex','flex-direction':'column'});});
</script>
@endpush
