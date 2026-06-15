@extends('portal.layout')
@section('content')
<form method="POST" action="{{ route('portal.profile.update') }}">
  @csrf @method('PUT')
  <div style="display:grid;grid-template-columns:1fr 280px;gap:16px;align-items:start">
    <div class="miba-card">
      <div class="miba-card-header">
        <div class="miba-card-title"><i class="fa fa-edit"></i> Edit Profil</div>
        <a href="{{ route('portal.profile') }}" class="btn-miba btn-miba-sm btn-ghost-miba"><i class="fa fa-arrow-left"></i> Kembali</a>
      </div>
      <div class="miba-card-body">
        <div class="miba-tabs">
          <a class="miba-tab active" href="#" onclick="switchTab(this,'tab-pribadi')">Data Pribadi</a>
          <a class="miba-tab" href="#" onclick="switchTab(this,'tab-sekolah')">Data Sekolah</a>
          <a class="miba-tab" href="#" onclick="switchTab(this,'tab-keluarga')">Data Keluarga</a>
        </div>
        <div id="tab-pribadi" class="miba-tab-content active">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <div class="miba-form-group" style="grid-column:1/3"><label class="miba-label">Nama Lengkap</label><input readonly class="miba-input" value="{{ $student->student_full_name }}" style="background:var(--bg)"></div>
            <div class="miba-form-group"><label class="miba-label">Jenis Kelamin</label>
              <select name="student_gender" class="miba-select">
                <option value="L" {{ $student->student_gender=='L'?'selected':'' }}>Laki-laki</option>
                <option value="P" {{ $student->student_gender=='P'?'selected':'' }}>Perempuan</option>
              </select>
            </div>
            <div class="miba-form-group"><label class="miba-label">Tempat Lahir</label><input type="text" name="student_born_place" class="miba-input" value="{{ $student->student_born_place }}"></div>
            <div class="miba-form-group"><label class="miba-label">Tanggal Lahir</label><input type="text" name="student_born_date" class="miba-input date-pick" value="{{ $student->student_born_date }}"></div>
            <div class="miba-form-group"><label class="miba-label">NIK</label><input type="text" name="student_hobby" class="miba-input" value="{{ $student->student_hobby }}"></div>
            <div class="miba-form-group"><label class="miba-label">No. HP Siswa</label><input type="text" name="student_phone" class="miba-input" value="{{ $student->student_phone }}"></div>
            <div class="miba-form-group" style="grid-column:1/3"><label class="miba-label">Alamat</label><textarea name="student_address" class="miba-textarea" rows="2">{{ $student->student_address }}</textarea></div>
          </div>
        </div>
        <div id="tab-sekolah" class="miba-tab-content">
          <div class="miba-form-group"><label class="miba-label">NIS</label><input readonly class="miba-input" value="{{ $student->student_nis }}" style="background:var(--bg)"></div>
          <div class="miba-form-group"><label class="miba-label">NISN</label><input readonly class="miba-input" value="{{ $student->student_nisn }}" style="background:var(--bg)"></div>
          <div class="miba-form-group"><label class="miba-label">Kelas</label><input readonly class="miba-input" value="{{ $student->class->class_name??'' }}" style="background:var(--bg)"></div>
          @if(($app_level??'')=='senior')<div class="miba-form-group"><label class="miba-label">Unit Sekolah</label><input readonly class="miba-input" value="{{ $student->majors->majors_name??'' }}" style="background:var(--bg)"></div>@endif
        </div>
        <div id="tab-keluarga" class="miba-tab-content">
          <div class="miba-form-group"><label class="miba-label">Nama Ibu Kandung</label><input type="text" name="student_name_of_mother" class="miba-input" value="{{ $student->student_name_of_mother }}"></div>
          <div class="miba-form-group"><label class="miba-label">Nama Ayah Kandung</label><input type="text" name="student_name_of_father" class="miba-input" value="{{ $student->student_name_of_father }}"></div>
          <div class="miba-form-group"><label class="miba-label">No. HP Orang Tua</label><input type="text" name="student_parent_phone" class="miba-input" value="{{ $student->student_parent_phone }}"></div>
        </div>
      </div>
    </div>
    <div class="miba-card">
      <div class="miba-card-body" style="text-align:center">
        @if($student->student_img)
          <img src="{{ asset('uploads/student/'.$student->student_img) }}" style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin-bottom:12px">
        @else
          <div style="width:80px;height:80px;border-radius:50%;background:var(--primary-xlight);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;color:var(--primary);font-size:28px"><i class="fa fa-user"></i></div>
        @endif
        <button type="submit" class="btn-miba btn-primary-miba" style="width:100%;justify-content:center"><i class="fa fa-save"></i> Simpan</button>
        <a href="{{ route('portal.profile') }}" class="btn-miba btn-ghost-miba" style="width:100%;justify-content:center;margin-top:8px">Batal</a>
      </div>
    </div>
  </div>
</form>
@endsection
@push('scripts')
<script>function switchTab(el,id){$('.miba-tab').removeClass('active');$('.miba-tab-content').removeClass('active');$(el).addClass('active');$('#'+id).addClass('active');return false;}</script>
@endpush